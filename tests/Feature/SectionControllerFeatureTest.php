<?php

namespace Tests\Feature;

use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SectionControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_sections_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('sections.index'))
            ->assertStatus(200)
            ->assertSee('Manage Section');
    }

    public function test_it_throws_authorization_error_when_displaying_the_sections_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('sections.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_sections_list_page_while_user_still_logged_out()
    {
        $this->get(route('sections.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_sections()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('sections.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_sections_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('sections.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_sections_while_user_still_logged_out()
    {
        $this->get(route('sections.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_sections()
    {
        $user = factory(User::class)->create();
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->get(route('sections.search'), ['search' => $section->name])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_sections_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->get(route('sections.search'), ['search' => $section->name])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_sections_while_user_still_logged_out()
    {
        $section = factory(Section::class)->create();

        $this->get(route('sections.search'), ['search' => $section->name])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Section Data
    public function section()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        return [
            'name' => $this->faker->unique()->name,
            'grade_level_id' => $gradeLevel->id,
        ];
    }

    //store
    public function test_it_can_create_the_section()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('sections.store'), $this->section())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_section()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('sections.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_section()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('sections.store'), $this->section())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_section_while_user_still_logged_out()
    {
        $this->post(route('sections.store'), $this->section())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_section()
    {
        $user = factory(User::class)->create();
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->get(route('sections.show', ['section' => $section->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_section()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->get(route('sections.show', ['section' => $section->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_section()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('sections.show', ['section' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_section_while_user_still_logged_out()
    {
        $section = factory(Section::class)->create();

        $this->get(route('sections.show', ['section' => $section->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_section()
    {
        $user = factory(User::class)->create();
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->put(route('sections.update', ['section' => $section->id]), $this->section())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_section()
    {
        $user = factory(User::class)->create();
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->put(route('sections.update', ['section' => $section->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_section()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->put(route('sections.update', ['section' => $section->id]), $this->section())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_section()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('sections.update', ['section' => $this->faker->randomNumber(9)]), $this->section())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_section_while_user_still_logged_out()
    {
        $section = factory(Section::class)->create();

        $this->put(route('sections.update', ['section' => $section->id]), $this->section())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_section()
    {
        $user = factory(User::class)->create();
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->delete(route('sections.destroy', ['section' => $section->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_section()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $section = factory(Section::class)->create();

        $this->actingAs($user)
            ->delete(route('sections.destroy', ['section' => $section->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_section()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('sections.destroy', ['section' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_section_while_user_still_logged_out()
    {
        $section = factory(Section::class)->create();

        $this->delete(route('sections.destroy', ['section' => $section->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
