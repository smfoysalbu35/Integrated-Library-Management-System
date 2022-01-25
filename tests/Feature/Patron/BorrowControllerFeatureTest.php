<?php

namespace Tests\Feature\Patron;

use App\Models\PatronAccount;
use Tests\TestCase;

class BorrowControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_borrow_list_page()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.borrows.index'))
            ->assertStatus(200)
            ->assertSee('Borrow Record')
            ->assertSee('Borrow List');
    }

    public function test_it_redirects_to_patron_account_login_page_when_displaying_the_borrow_list_page_while_patron_account_still_logged_out()
    {
        $this->get(route('patron-web.borrows.index'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }
}
