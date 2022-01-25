<?php

namespace Tests\Feature;

use App\Models\GradeLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GradeLevelControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_grade_levels_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('grade-levels.index'))
            ->assertStatus(200)
            ->assertSee('Manage Grade Level');
    }

    public function test_it_throws_authorization_error_when_displaying_the_grade_levels_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('grade-levels.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_grade_levels_list_page_while_user_still_logged_out()
    {
        $this->get(route('grade-levels.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_grade_levels()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('grade-levels.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_grade_levels()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('grade-levels.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_grade_levels_while_user_still_logged_out()
    {
        $this->get(route('grade-levels.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_grade_levels()
    {
        $user = factory(User::class)->create();
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->get(route('grade-levels.search'), ['search' => $gradeLevel->grade_level])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_grade_levels()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->get(route('grade-levels.search'), ['search' => $gradeLevel->grade_level])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_grade_levels_while_user_still_logged_out()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->get(route('grade-levels.search'), ['search' => $gradeLevel->grade_level])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Grade Level Data
    public function gradeLevel()
    {
        return [
            'grade_level' => $this->faker->unique()->randomNumber(9),
        ];
    }

    //store
    public function test_it_can_create_the_grade_level()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('grade-levels.store'), $this->gradeLevel())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_grade_level()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('grade-levels.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_grade_level()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('grade-levels.store'), $this->gradeLevel())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_grade_level_while_user_still_logged_out()
    {
        $this->post(route('grade-levels.store'), $this->gradeLevel())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_grade_level()
    {
        $user = factory(User::class)->create();
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->get(route('grade-levels.show', ['grade_level' => $gradeLevel->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_grade_level()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->get(route('grade-levels.show', ['grade_level' => $gradeLevel->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_grade_level()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('grade-levels.show', ['grade_level' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_grade_level_while_user_still_logged_out()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->get(route('grade-levels.show', ['grade_level' => $gradeLevel->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_grade_level()
    {
        $user = factory(User::class)->create();
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->put(route('grade-levels.update', ['grade_level' => $gradeLevel->id]), $this->gradeLevel())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_grade_level()
    {
        $user = factory(User::class)->create();
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->put(route('grade-levels.update', ['grade_level' => $gradeLevel->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_grade_level()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->put(route('grade-levels.update', ['grade_level' => $gradeLevel->id]), $this->gradeLevel())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_grade_level()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('grade-levels.update', ['grade_level' => $this->faker->randomNumber(9)]), $this->gradeLevel())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_grade_level_while_user_still_logged_out()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->put(route('grade-levels.update', ['grade_level' => $gradeLevel->id]), $this->gradeLevel())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_grade_level()
    {
        $user = factory(User::class)->create();
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->delete(route('grade-levels.destroy', ['grade_level' => $gradeLevel->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_grade_level()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->actingAs($user)
            ->delete(route('grade-levels.destroy', ['grade_level' => $gradeLevel->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_grade_level()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('grade-levels.destroy', ['grade_level' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_grade_level_while_user_still_logged_out()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $this->delete(route('grade-levels.destroy', ['grade_level' => $gradeLevel->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
