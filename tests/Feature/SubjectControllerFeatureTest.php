<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_subjects_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('subjects.index'))
            ->assertStatus(200)
            ->assertSee('Manage Subject');
    }

    public function test_it_throws_authorization_error_when_displaying_the_subjects_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('subjects.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_subjects_list_page_while_user_still_logged_out()
    {
        $this->get(route('subjects.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_subjects()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('subjects.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_subjects()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('subjects.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_subjects_while_user_still_logged_out()
    {
        $this->get(route('subjects.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_subjects()
    {
        $user = factory(User::class)->create();
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->get(route('subjects.search'), ['search' => $subject->name])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_subjects()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->get(route('subjects.search'), ['search' => $subject->name])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_subjects_while_user_still_logged_out()
    {
        $subject = factory(Subject::class)->create();

        $this->get(route('subjects.search'), ['search' => $subject->name])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Subject Data
    public function subject()
    {
        return [
            'name' => $this->faker->unique()->name,
        ];
    }

    //store
    public function test_it_can_create_the_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('subjects.store'), $this->subject())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('subjects.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('subjects.store'), $this->subject())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_subject_while_user_still_logged_out()
    {
        $this->post(route('subjects.store'), $this->subject())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_subject()
    {
        $user = factory(User::class)->create();
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->get(route('subjects.show', ['subject' => $subject->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->get(route('subjects.show', ['subject' => $subject->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('subjects.show', ['subject' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_subject_while_user_still_logged_out()
    {
        $subject = factory(Subject::class)->create();

        $this->get(route('subjects.show', ['subject' => $subject->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_subject()
    {
        $user = factory(User::class)->create();
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->put(route('subjects.update', ['subject' => $subject->id]), $this->subject())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_subject()
    {
        $user = factory(User::class)->create();
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->put(route('subjects.update', ['subject' => $subject->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->put(route('subjects.update', ['subject' => $subject->id]), $this->subject())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('subjects.update', ['subject' => $this->faker->randomNumber(9)]), $this->subject())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_subject_while_user_still_logged_out()
    {
        $subject = factory(Subject::class)->create();

        $this->put(route('subjects.update', ['subject' => $subject->id]), $this->subject())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_subject()
    {
        $user = factory(User::class)->create();
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->delete(route('subjects.destroy', ['subject' => $subject->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $subject = factory(Subject::class)->create();

        $this->actingAs($user)
            ->delete(route('subjects.destroy', ['subject' => $subject->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('subjects.destroy', ['subject' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_subject_while_user_still_logged_out()
    {
        $subject = factory(Subject::class)->create();

        $this->delete(route('subjects.destroy', ['subject' => $subject->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
