<?php

namespace Tests\Feature;

use App\Models\Accession;
use App\Models\Book;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessionControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_accessions_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.index'))
            ->assertStatus(200)
            ->assertSee('Manage Accession');
    }

    public function test_it_throws_authorization_error_when_displaying_the_accessions_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('accessions.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_accessions_list_page_while_user_still_logged_out()
    {
        $this->get(route('accessions.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_accessions()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_accessions()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('accessions.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_accessions_while_user_still_logged_out()
    {
        $this->get(route('accessions.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_accessions()
    {
        $user = factory(User::class)->create();
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.search'), ['search' => $accession->accession_no])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_accessions()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.search'), ['search' => $accession->accession_no])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_accessions_while_user_still_logged_out()
    {
        $accession = factory(Accession::class)->create();

        $this->get(route('accessions.search'), ['search' => $accession->accession_no])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //count
    public function test_it_can_count_the_accessions()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.count'))
            ->assertJsonStructure(['count'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_counting_the_accessions()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('accessions.count'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_counting_the_accessions_while_user_still_logged_out()
    {
        $this->get(route('accessions.count'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Accession Data
    public function accession()
    {
        $book = factory(Book::class)->create();
        $location = factory(Location::class)->create();

        return [
            'accession_no' => $this->faker->unique()->isbn10,
            'book_id' => $book->id,
            'location_id' => $location->id,
            'acquired_date' => $this->faker->date,
            'donnor_name' => $this->faker->name,
            'price' => $this->faker->randomNumber(3),
            'status' => 1,
        ];
    }

    //store
    public function test_it_can_create_the_accession()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('accessions.store'), $this->accession())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_accession()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('accessions.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_accession()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('accessions.store'), $this->accession())
            ->assertStatus(403);
    }

    public function test_it_throws_error_when_the_book_exceed_the_maximum_no_of_copy_when_creating_the_accession()
    {
        $user = factory(User::class)->create();

        $book = factory(Book::class)->create();
        factory(Accession::class, $book->copy)->create(['book_id' => $book->id]);

        $data = $this->accession();
        $data['book_id'] = $book->id;

        $this->actingAs($user)
            ->post(route('accessions.store'), $data)
            ->assertJsonStructure(['error'])
            ->assertStatus(400);
    }

    public function test_it_redirects_to_login_page_when_creating_the_accession_while_user_still_logged_out()
    {
        $this->post(route('accessions.store'), $this->accession())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_accession()
    {
        $user = factory(User::class)->create();
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.show', ['accession' => $accession->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_accession()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.show', ['accession' => $accession->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_accession()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('accessions.show', ['accession' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_accession_while_user_still_logged_out()
    {
        $accession = factory(Accession::class)->create();

        $this->get(route('accessions.show', ['accession' => $accession->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_accession()
    {
        $user = factory(User::class)->create();
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->put(route('accessions.update', ['accession' => $accession->id]), $this->accession())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_accession()
    {
        $user = factory(User::class)->create();
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->put(route('accessions.update', ['accession' => $accession->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_accession()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->put(route('accessions.update', ['accession' => $accession->id]), $this->accession())
            ->assertStatus(403);
    }

    public function test_it_throws_error_when_the_book_exceed_the_maximum_no_of_copy_when_updating_the_accession()
    {
        $user = factory(User::class)->create();
        $accession = factory(Accession::class)->create();

        $book = factory(Book::class)->create();
        factory(Accession::class, $book->copy)->create(['book_id' => $book->id]);

        $data = $this->accession();
        $data['book_id'] = $book->id;

        $this->actingAs($user)
            ->put(route('accessions.update', ['accession' => $accession->id]), $data)
            ->assertJsonStructure(['error'])
            ->assertStatus(400);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_accession()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('accessions.update', ['accession' => $this->faker->randomNumber(9)]), $this->accession())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_accession_while_user_still_logged_out()
    {
        $accession = factory(Accession::class)->create();

        $this->put(route('accessions.update', ['accession' => $accession->id]), $this->accession())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_accession()
    {
        $user = factory(User::class)->create();
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->delete(route('accessions.destroy', ['accession' => $accession->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_accession()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $accession = factory(Accession::class)->create();

        $this->actingAs($user)
            ->delete(route('accessions.destroy', ['accession' => $accession->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_accession()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('accessions.destroy', ['accession' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_accession_while_user_still_logged_out()
    {
        $accession = factory(Accession::class)->create();

        $this->delete(route('accessions.destroy', ['accession' => $accession->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
