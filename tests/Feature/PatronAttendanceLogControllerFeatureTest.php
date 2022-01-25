<?php

namespace Tests\Feature;

use App\Models\Patron;
use App\Models\PatronAttendanceLog;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronAttendanceLogControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_patron_attendance_monitoring_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patron-attendance-monitoring.index'))
            ->assertStatus(200)
            ->assertSee('Attendance Monitoring')
            ->assertSee('Patron Attendance Monitoring');
    }

    //store
    public function test_it_can_login_the_patron_in_patron_attendance_log()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->post(route('patron-attendance-monitoring.store'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['status', 'data'])
            ->assertJson(['status' => 'login'])
            ->assertStatus(200);
    }

    public function test_it_can_logout_the_patron_in_patron_attendance_log()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();
        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $this->actingAs($user)
            ->post(route('patron-attendance-monitoring.store'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['status', 'data'])
            ->assertJson(['status' => 'logout'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_creating_the_patron_attendance_log()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('patron-attendance-monitoring.store'), [])
            ->assertSessionHas(['errors']);
    }
}
