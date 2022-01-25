<?php

namespace Tests\Unit;

use Carbon\Carbon;
use App\Models\Patron;
use App\Models\Penalty;
use App\Models\Transaction;
use App\Models\User;

use App\Repositories\Patron\PatronRepository;
use App\Repositories\Penalty\PenaltyRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Collection;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentServiceUnitTest extends TestCase
{
    use WithFaker;

    protected function initializePaymentService()
    {
        $paymentService = new PaymentService(
            new PatronRepository(new Patron),
            new PenaltyRepository(new Penalty),
            new TransactionRepository(new Transaction)
        );

        return $paymentService;
    }

    //Process Payment Rules
    public function test_it_can_process_the_payment_rules()
    {
        $patron = factory(Patron::class)->create();
        $penalty = factory(Penalty::class)->create(['patron_id' => $patron->id]);

        $paymentService = $this->initializePaymentService();
        $result = $paymentService->processPaymentRules(['patron_no' => $patron->patron_no, 'payment' => $penalty->amount]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'success');
    }

    public function test_it_throws_argument_count_error_when_processing_the_payment_rules()
    {
        $this->expectException(ArgumentCountError::class);

        $paymentService = $this->initializePaymentService();
        $paymentService->processPaymentRules();
    }

    public function test_it_throws_error_exception_when_processing_the_payment_rules()
    {
        $this->expectException(ErrorException::class);

        $paymentService = $this->initializePaymentService();
        $paymentService->processPaymentRules([]);
    }

    public function test_it_throws_model_not_found_error_when_processing_the_payment_rules()
    {
        $this->expectException(ModelNotFoundException::class);

        $paymentService = $this->initializePaymentService();
        $paymentService->processPaymentRules(['patron_no' => $this->faker->unique()->isbn10, 'payment' => $this->faker->randomNumber(2)]);
    }

    public function test_it_throws_no_penalty_message_when_processing_the_payment_rules()
    {
        $patron = factory(Patron::class)->create();

        $paymentService = $this->initializePaymentService();
        $result = $paymentService->processPaymentRules(['patron_no' => $patron->patron_no, 'payment' => $this->faker->randomNumber(2)]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'no-penalty');
    }

    public function test_it_throws_insufficient_payment_message_when_processing_the_payment_rules()
    {
        $patron = factory(Patron::class)->create();
        $penalty = factory(Penalty::class)->create(['patron_id' => $patron->id]);

        $paymentService = $this->initializePaymentService();
        $result = $paymentService->processPaymentRules(['patron_no' => $patron->patron_no, 'payment' => 0]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'insufficient-payment');
    }

    //Create Payment
    public function test_it_can_create_the_payment()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $patron = factory(Patron::class)->create();
        $penalty = factory(Penalty::class)->create(['patron_id' => $patron->id]);

        $paymentService = $this->initializePaymentService();
        $transaction = $paymentService->create(['patron_no' => $patron->patron_no, 'payment' => $penalty->amount]);

        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function test_it_throws_argument_count_error_when_creating_the_payment()
    {
        $this->expectException(ArgumentCountError::class);

        $paymentService = $this->initializePaymentService();
        $paymentService->create();
    }

    public function test_it_throws_error_exception_when_creating_the_payment()
    {
        $this->expectException(ErrorException::class);

        $paymentService = $this->initializePaymentService();
        $paymentService->create([]);
    }

    public function test_it_throws_model_not_found_error_when_creating_the_payment()
    {
        $this->expectException(ModelNotFoundException::class);

        $paymentService = $this->initializePaymentService();
        $paymentService->create(['patron_no' => $this->faker->unique()->isbn10, 'payment' => $this->faker->randomNumber(2)]);
    }
}
