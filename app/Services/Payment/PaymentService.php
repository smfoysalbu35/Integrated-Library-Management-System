<?php

namespace App\Services\Payment;

use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;

class PaymentService
{
    protected $patron, $penalty, $transaction;

    public function __construct(PatronRepositoryInterface $patron, PenaltyRepositoryInterface $penalty, TransactionRepositoryInterface $transaction)
    {
        $this->patron = $patron;
        $this->penalty = $penalty;
        $this->transaction = $transaction;
    }

    public function processPaymentRules(array $data)
    {
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);

        if(!($this->penalty->getPenaltyCountByPatronId($patron->id) > 0))
            return 'no-penalty';

        if($this->penalty->findTotalPenaltyByPatronId($patron->id) > $data['payment'])
            return 'insufficient-payment';

        return 'success';
    }

    public function create(array $data)
    {
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);

        $totalPenalty = $this->penalty->findTotalPenaltyByPatronId($patron->id);

        $transaction = $this->transaction->create([
            'patron_id' => $patron->id,
            'user_id' => auth()->guard()->user()->id,
            'transaction_date' => NOW(),
            'transaction_time' => NOW(),
            'total_penalty' => $totalPenalty,
            'payment' => $data['payment'],
            'change' => $data['payment'] - $totalPenalty,
        ]);

        $penalties = $this->penalty->getPenaltyByPatronId($patron->id);
        foreach($penalties as $penalty)
        {
            $transaction->transaction_details()->create([
                'transaction_id' => $transaction->id,
                'accession_id' => $penalty->accession->id,
                'penalty_id' => $penalty->id,
            ]);

            $this->penalty->update(['status' => 0], $penalty->id);
        }

        return $transaction->fresh();
    }
}
