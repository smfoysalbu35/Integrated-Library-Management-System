<?php

namespace Tests\Unit;

use Carbon\Carbon;
use App\Models\Accession;
use App\Models\Patron;
use App\Models\Reservation;
use App\Repositories\Reservation\ReservationRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Reservation
    public function test_it_can_get_all_the_reservations()
    {
        factory(Reservation::class, 3)->create();

        $repository = new ReservationRepository(new Reservation);
        $reservations = $repository->get();

        $this->assertInstanceOf(Collection::class, $reservations);
    }

    //Paginate Reservation
    public function test_it_can_paginate_the_reservations()
    {
        factory(Reservation::class, 3)->create();

        $repository = new ReservationRepository(new Reservation);
        $reservations = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $reservations);
    }

    //Get Reservation by field name
    public function test_it_can_get_the_reservations_by_field_name()
    {
        $reservation = factory(Reservation::class)->create();

        $repository = new ReservationRepository(new Reservation);
        $reservations = $repository->getBy('id', $reservation->id);

        $this->assertInstanceOf(Collection::class, $reservations);
    }

    //Get Reservation by patron id
    public function test_it_can_get_the_reservations_by_patron_id()
    {
        $patron = factory(Patron::class)->create();

        $repository = new ReservationRepository(new Reservation);
        $reservations = $repository->getReservationByPatronId($patron->id);

        $this->assertInstanceOf(Collection::class, $reservations);
    }

    public function test_it_throws_errors_when_getting_the_reservations_by_patron_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new ReservationRepository(new Reservation);
        $repository->getReservationByPatronId();
    }

    //Get Latest Reservation Transaction
    public function test_it_can_get_the_latest_reservation_transaction()
    {
        factory(Reservation::class, 3)->create();

        $repository = new ReservationRepository(new Reservation);
        $reservations = $repository->getLatestReservationTransaction();

        $this->assertInstanceOf(Collection::class, $reservations);
    }

    //Reservation Data
    public function reservation()
    {
        $accession = factory(Accession::class)->create();
        $patron = factory(Patron::class)->create();

        return [
            'accession_id' => $accession->id,
            'patron_id' => $patron->id,
            'reservation_date' => Carbon::now(),
            'reservation_time' => Carbon::now(),
            'reservation_end_date' => Carbon::now()->addDays($patron->patron_type->no_of_day_reserve_allowed),
        ];
    }

    //Create Reservation
    public function test_it_can_create_the_reservation()
    {
        $repository = new ReservationRepository(new Reservation);
        $reservation = $repository->create($this->reservation());

        $this->assertInstanceOf(Reservation::class, $reservation);
    }

    public function test_it_throws_errors_when_creating_the_reservation()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new ReservationRepository(new Reservation);
        $repository->create([]);
    }

    //Find Reservation
    public function test_it_can_find_the_reservation()
    {
        $reservation = factory(Reservation::class)->create();

        $repository = new ReservationRepository(new Reservation);
        $found = $repository->find($reservation->id);

        $this->assertInstanceOf(Reservation::class, $found);
        $this->assertEquals($reservation->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_reservation()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new ReservationRepository(new Reservation);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Reservation by field name
    public function test_it_can_find_the_reservation_by_field_name()
    {
        $reservation = factory(Reservation::class)->create();

        $repository = new ReservationRepository(new Reservation);
        $found = $repository->findBy('id', $reservation->id);

        $this->assertInstanceOf(Reservation::class, $found);
        $this->assertEquals($reservation->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_reservation_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new ReservationRepository(new Reservation);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Get Reservation Count by patron id
    public function test_it_can_count_the_reservation_by_patron_id()
    {
        $patron = factory(Patron::class)->create();

        $repository = new ReservationRepository(new Reservation);
        $count = $repository->getReservationCountByPatronId($patron->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_reservation_by_patron_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new ReservationRepository(new Reservation);
        $repository->getReservationCountByPatronId();
    }

    //Get Reservation Count by accession id
    public function test_it_can_count_the_reservation_by_accession_id()
    {
        $accession = factory(Accession::class)->create();

        $repository = new ReservationRepository(new Reservation);
        $count = $repository->getReservationCountByAccessionId($accession->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_reservation_by_accession_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new ReservationRepository(new Reservation);
        $repository->getReservationCountByAccessionId();
    }

    //Check If Patron Reserve Accession
    public function test_it_can_check_if_the_patron_reserve_accession()
    {
        $accession = factory(Accession::class)->create();
        $patron = factory(Patron::class)->create();

        $repository = new ReservationRepository(new Reservation);
        $count = $repository->checkIfPatronReserveAccession($accession->id, $patron->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_checking_if_the_patron_reserve_accession()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new ReservationRepository(new Reservation);
        $repository->checkIfPatronReserveAccession();
    }
}
