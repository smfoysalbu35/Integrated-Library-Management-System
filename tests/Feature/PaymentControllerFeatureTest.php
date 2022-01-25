<?php

namespace Tests\Feature;

use App\Models\Borrow;
use App\Models\Patron;
use App\Models\Penalty;
use App\Models\ReturnBook;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_payment_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('payments.index'))
            ->assertStatus(200)
            ->assertSee('Manage Payment')
            ->assertSee('Payment Transaction');
    }

    public function test_it_can_display_the_payment_page_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('payments.index'))
            ->assertStatus(200)
            ->assertSee('Manage Payment')
            ->assertSee('Payment Transaction');
    }

    public function test_it_redirects_to_login_page_when_displaying_the_payment_page_while_user_still_logged_out()
    {
        $this->get(route('payments.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Payment Data
    public function paymentData()
    {
        $patron = factory(Patron::class)->create();

        $borrow = factory(Borrow::class)->create(['patron_id' => $patron->id]);

        $returnBook = factory(ReturnBook::class)->create([
            'borrow_id' => $borrow->id,
            'patron_id' => $borrow->patron->id,
        ]);

        $penalty = factory(Penalty::class)->create([
            'return_book_id' => $returnBook->id,
            'patron_id' => $returnBook->patron->id,
        ]);

        return [
            'patron_no' => $patron->patron_no,
            'payment' => $penalty->amount,
        ];
    }

    //store
    public function test_it_can_create_the_payment()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('payments.store'), $this->paymentData())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_can_create_the_payment_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('payments.store'), $this->paymentData())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_during_creating_the_payment()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('payments.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_error_when_patron_has_no_penalty_during_creating_the_payment()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $data = [
            'patron_no' => $patron->patron_no,
            'payment' => $this->faker->randomDigitNot(0),
        ];

        $this->actingAs($user)
            ->post(route('payments.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'no-penalty'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_payment_is_insufficient_during_creating_the_payment()
    {
        $user = factory(User::class)->create();

        $data = $this->paymentData();
        $data['payment'] = $data['payment'] - $this->faker->randomDigitNot(0);

        $this->actingAs($user)
            ->post(route('payments.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'insufficient-payment'])
            ->assertStatus(400);
    }

    public function test_it_redirects_to_login_page_when_creating_the_payment_while_user_still_logged_out()
    {
        $this->post(route('payments.store'), $this->paymentData())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
