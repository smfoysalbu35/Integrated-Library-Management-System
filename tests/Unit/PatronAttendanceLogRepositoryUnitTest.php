<?php

namespace Tests\Unit;

use App\Models\Patron;
use App\Models\PatronAttendanceLog;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepository;
use Illuminate\Support\Collection;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronAttendanceLogRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Patron Attendance Log by field name
    public function test_it_can_get_the_patron_attendance_logs_by_field_name()
    {
        $patronAttendanceLog = factory(PatronAttendanceLog::class)->create();

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $patronAttendanceLogs = $repository->getBy('patron_id', $patronAttendanceLog->patron_id);

        $this->assertInstanceOf(Collection::class, $patronAttendanceLogs);
    }

    //Check Patron Attendance Log
    public function test_it_can_check_the_patron_in_patron_attendance_log()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $count = $repository->checkPatronAttendanceLog($patron->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_checking_the_patron_in_patron_attendance_log()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $repository->checkPatronAttendanceLog();
    }

    //Login
    public function test_it_can_login_the_patron_in_patron_attendance_log()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $patronAttendanceLog = $repository->login($patron->id);

        $this->assertInstanceOf(PatronAttendanceLog::class, $patronAttendanceLog);
    }

    public function test_it_throws_errors_when_patron_try_to_login_to_patron_attendance_log()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $repository->login($this->faker->randomNumber(9));
    }

    //Logout
    public function test_it_can_logout_the_patron_in_patron_attendance_log()
    {
        $patron = factory(Patron::class)->create();
        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $patronAttendanceLog = $repository->logout($patron->id);

        $this->assertInstanceOf(PatronAttendanceLog::class, $patronAttendanceLog);
    }

    public function test_it_throws_errors_when_patron_try_to_logout_to_patron_attendance_log()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $repository->logout($this->faker->randomNumber(9));
    }

    //Get Patron Attendance Log Report
    public function test_it_can_list_the_patron_attendance_log_report()
    {
        factory(PatronAttendanceLog::class, 3)->create(['status' => 0]);

        $data = ['from' => $this->faker->date, 'to' => $this->faker->date];

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $patronAttendanceLogs = $repository->getPatronAttendanceLogReport($data);

        $this->assertInstanceOf(Collection::class, $patronAttendanceLogs);
    }

    public function test_it_throws_errors_when_listing_the_patron_attendance_log_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new PatronAttendanceLogRepository(new PatronAttendanceLog);
        $repository->getPatronAttendanceLogReport([]);
    }
}
