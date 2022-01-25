<?php

namespace Tests\Feature\Report;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_transaction_report_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.index'))
            ->assertStatus(200)
            ->assertSee('Transaction')
            ->assertSee('Transaction Report');
    }

    public function test_it_throws_authorization_error_when_displaying_the_transaction_report_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.transactions.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_transaction_report_page_while_user_still_logged_out()
    {
        $this->get(route('report.transactions.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Report Data
    public function data()
    {
        return [
            'from' => $this->faker->date,
            'to' => $this->faker->date,
        ];
    }

    //store
    public function test_it_can_list_the_transaction_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('report.transactions.store'), $this->data())
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_listing_the_transaction_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('report.transactions.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_listing_the_transaction_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('report.transactions.store'), $this->data())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_transaction_report_while_user_still_logged_out()
    {
        $this->post(route('report.transactions.store'), $this->data())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //print
    public function test_it_can_print_the_transaction_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.print', $this->data()))
            ->assertStatus(200)
            ->assertSee('Library Management System')
            ->assertSee('Transaction Report');
    }

    public function test_it_throws_validation_error_when_printing_the_transaction_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.print', []))
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_printing_the_transaction_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.transactions.print', $this->data()))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_printing_the_transaction_report_while_user_still_logged_out()
    {
        $this->get(route('report.transactions.print', $this->data()))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_transaction()
    {
        $user = factory(User::class)->create();
        $transaction = factory(Transaction::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.show', ['id' => $transaction->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_transaction()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $transaction = factory(Transaction::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.show', ['id' => $transaction->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_transaction()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.show', ['id' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_transaction_while_user_still_logged_out()
    {
        $transaction = factory(Transaction::class)->create();

        $this->get(route('report.transactions.show', ['id' => $transaction->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //print details
    public function test_it_can_print_the_transaction_details()
    {
        $user = factory(User::class)->create();
        $transaction = factory(Transaction::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.print-details', ['id' => $transaction->id]))
            ->assertStatus(200)
            ->assertSee('Library Management System')
            ->assertSee('Transaction Details');
    }

    public function test_it_throws_authorization_error_when_printing_the_transaction_details()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $transaction = factory(Transaction::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.print-details', ['id' => $transaction->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_printing_the_transaction_details()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.transactions.print-details', ['id' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_printing_the_transaction_details_while_user_still_logged_out()
    {
        $transaction = factory(Transaction::class)->create();

        $this->get(route('report.transactions.print-details', ['id' => $transaction->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
