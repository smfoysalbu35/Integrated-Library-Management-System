<?php

namespace App\Console\Commands;

use App\Models\Accession;
use App\Models\Reservation;
use Illuminate\Console\Command;

class CheckReservationEnded extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:reservation_ended';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all reservation ended.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reservations = Reservation::select('id', 'accession_id')
            ->whereRaw('reservation_end_date < CURRENT_DATE()')
            ->whereRaw('(SELECT accessions.status FROM accessions WHERE accessions.id = reservations.accession_id) = 0')
            ->get();

        foreach($reservations as $reservation)
            $accession = Accession::findOrFail($reservation->accession_id)->update(['status' => 1]);

        $this->info('All books that already ended the reservation is successfully updated!');
    }
}
