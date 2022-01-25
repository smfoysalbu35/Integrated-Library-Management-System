<?php

namespace Tests\Feature\Patron;

use App\Models\PatronAccount;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_transaction_list_page()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.transactions.index'))
            ->assertStatus(200)
            ->assertSee('Transaction Record')
            ->assertSee('Transaction List');
    }

    public function test_it_redirects_to_patron_account_login_page_when_displaying_the_transaction_list_page_while_patron_account_still_logged_out()
    {
        $this->get(route('patron-web.transactions.index'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }

    //show
    public function test_it_can_show_the_transaction()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $transaction = factory(Transaction::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.transactions.show', ['id' => $transaction->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_transaction()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.transactions.show', ['id' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_patron_account_login_page_when_showing_the_transaction_while_patron_account_still_logged_out()
    {
        $transaction = factory(Transaction::class)->create();

        $this->get(route('patron-web.transactions.show', ['id' => $transaction->id]))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }
}
