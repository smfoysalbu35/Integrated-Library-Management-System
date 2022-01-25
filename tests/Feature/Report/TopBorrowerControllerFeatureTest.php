<?php

namespace Tests\Feature\Report;

use App\Models\User;
use App\Models\PatronType;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopBorrowerControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_top_borrower_report_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.top-borrowers.index'))
            ->assertStatus(200)
            ->assertSee('Top Borrower')
            ->assertSee('Top Borrower Report');
    }

    public function test_it_throws_authorization_error_when_displaying_the_top_borrower_report_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.top-borrowers.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_top_borrower_report_page_while_user_still_logged_out()
    {
        $this->get(route('report.top-borrowers.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Report Data
    public function data()
    {
        $patronType = factory(PatronType::class)->create();

        return [
            'from' => $this->faker->date,
            'to' => $this->faker->date,
            'patron_type_id' => $patronType->id,
        ];
    }

    //store
    public function test_it_can_list_the_top_borrower_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('report.top-borrowers.store'), $this->data())
            ->assertJsonStructure(['from', 'to', 'patronType', 'topBorrowers'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_listing_the_top_borrower_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('report.top-borrowers.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_listing_the_top_borrower_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('report.top-borrowers.store'), $this->data())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_top_borrower_report_while_user_still_logged_out()
    {
        $this->post(route('report.top-borrowers.store'), $this->data())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //print
    public function test_it_can_print_the_top_borrower_report()
    {
        $user = factory(User::class)->create();
        $patronType = factory(PatronType::class)->create();

        $data = $this->data();
        $data['patron_type_id'] = $patronType->id;

        $this->actingAs($user)
            ->get(route('report.top-borrowers.print', $data))
            ->assertStatus(200)
            ->assertSee('Library Management System')
            ->assertSee('Top ' . $patronType->name . ' Borrower Report');
    }

    public function test_it_throws_validation_error_when_printing_the_top_borrower_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.top-borrowers.print', []))
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_printing_the_top_borrower_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.top-borrowers.print', $this->data()))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_printing_the_top_borrower_report_while_user_still_logged_out()
    {
        $this->get(route('report.top-borrowers.print', $this->data()))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
