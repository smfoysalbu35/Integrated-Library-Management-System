<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_transactions_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('transactions.index'))
            ->assertStatus(200)
            ->assertSee('Transaction Record')
            ->assertSee('Transaction List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_transactions_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('transactions.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_transactions_list_page_while_user_still_logged_out()
    {
        $this->get(route('transactions.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_transaction()
    {
        $user = factory(User::class)->create();
        $transaction = factory(Transaction::class)->create();

        $this->actingAs($user)
            ->get(route('transactions.show', ['transaction' => $transaction->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_transaction()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $transaction = factory(Transaction::class)->create();

        $this->actingAs($user)
            ->get(route('transactions.show', ['transaction' => $transaction->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_transaction()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('transactions.show', ['transaction' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_transaction_while_user_still_logged_out()
    {
        $transaction = factory(Transaction::class)->create();

        $this->get(route('transactions.show', ['transaction' => $transaction->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
