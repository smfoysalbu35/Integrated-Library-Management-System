<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class OpacControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_opac_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('opac.index'))
            ->assertStatus(200)
            ->assertSee('Online Public Access Catalog')
            ->assertSee('Accession List');
    }
}
