<?php

namespace Tests\Feature;

use App\Models\Computer;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee($user->name);
    }

    public function test_authenticated_user_can_view_computers_page(): void
    {
        $user = User::factory()->create();
        $computer = Computer::factory()->create([
            'user_id' => $user->id,
            'name' => 'PC Laboratorio 101',
        ]);

        $response = $this->actingAs($user)->get('/computers');

        $response->assertStatus(200);
        $response->assertSee('PC Laboratorio 101');
    }

    public function test_authenticated_user_can_view_phones_page(): void
    {
        $user = User::factory()->create();
        $phone = Phone::factory()->create([
            'user_id' => $user->id,
            'name' => 'Pixel de Prueba',
        ]);

        $response = $this->actingAs($user)->get('/phones');

        $response->assertStatus(200);
        $response->assertSee('Pixel de Prueba');
    }
}
