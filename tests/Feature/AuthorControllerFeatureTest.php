<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_authors_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('authors.index'))
            ->assertStatus(200)
            ->assertSee('Manage Author');
    }

    public function test_it_throws_authorization_error_when_displaying_the_authors_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('authors.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_authors_list_page_while_user_still_logged_out()
    {
        $this->get(route('authors.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_authors()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('authors.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_authors()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('authors.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_authors_while_user_still_logged_out()
    {
        $this->get(route('authors.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_authors()
    {
        $user = factory(User::class)->create();
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->get(route('authors.search'), ['search' => $author->name])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_authors()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->get(route('authors.search'), ['search' => $author->name])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_authors_while_user_still_logged_out()
    {
        $author = factory(Author::class)->create();

        $this->get(route('authors.search'), ['search' => $author->name])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Author Data
    public function author()
    {
        return [
            'name' => $this->faker->unique()->name,
        ];
    }

    //store
    public function test_it_can_create_the_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('authors.store'), $this->author())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('authors.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('authors.store'), $this->author())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_author_while_user_still_logged_out()
    {
        $this->post(route('authors.store'), $this->author())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_author()
    {
        $user = factory(User::class)->create();
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->get(route('authors.show', ['author' => $author->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->get(route('authors.show', ['author' => $author->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('authors.show', ['author' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_author_while_user_still_logged_out()
    {
        $author = factory(Author::class)->create();

        $this->get(route('authors.show', ['author' => $author->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_author()
    {
        $user = factory(User::class)->create();
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->put(route('authors.update', ['author' => $author->id]), $this->author())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_author()
    {
        $user = factory(User::class)->create();
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->put(route('authors.update', ['author' => $author->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->put(route('authors.update', ['author' => $author->id]), $this->author())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('authors.update', ['author' => $this->faker->randomNumber(9)]), $this->author())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_author_while_user_still_logged_out()
    {
        $author = factory(Author::class)->create();

        $this->put(route('authors.update', ['author' => $author->id]), $this->author())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_author()
    {
        $user = factory(User::class)->create();
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->delete(route('authors.destroy', ['author' => $author->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $author = factory(Author::class)->create();

        $this->actingAs($user)
            ->delete(route('authors.destroy', ['author' => $author->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('authors.destroy', ['author' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_author_while_user_still_logged_out()
    {
        $author = factory(Author::class)->create();

        $this->delete(route('authors.destroy', ['author' => $author->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
