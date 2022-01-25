<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Tests\TestCase;

class AccessionRecordControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_accession_record_report_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.accession-records.index'))
            ->assertStatus(200)
            ->assertSee('Accession Record')
            ->assertSee('Accession Record Report');
    }

    public function test_it_throws_authorization_error_when_displaying_the_accession_record_report_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.accession-records.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_accession_record_report_page_while_user_still_logged_out()
    {
        $this->get(route('report.accession-records.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //print
    public function test_it_can_print_the_accession_record_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.accession-records.print'))
            ->assertStatus(200)
            ->assertSee('Library Management System')
            ->assertSee('Accession Record Report');
    }

    public function test_it_throws_authorization_error_when_printing_the_accession_record_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.accession-records.print'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_printing_the_accession_record_report_while_user_still_logged_out()
    {
        $this->get(route('report.accession-records.print'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
