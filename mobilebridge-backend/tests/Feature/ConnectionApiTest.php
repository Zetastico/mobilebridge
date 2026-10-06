<?php

namespace Tests\Feature;

use App\Models\Computer;
use App\Models\Connection;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConnectionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_connection_between_own_devices(): void
    {
        $user = User::factory()->create();
        $computer = Computer::factory()->create(['user_id' => $user->id]);
        $phone = Phone::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/connections', [
            'computer_id' => $computer->id,
            'phone_id' => $phone->id,
            'status' => 'active',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Conexión registrada exitosamente',
                'connection' => [
                    'computer_id' => $computer->id,
                    'phone_id' => $phone->id,
                    'status' => 'active',
                ],
            ]);

        $this->assertDatabaseHas('connections', [
            'computer_id' => $computer->id,
            'phone_id' => $phone->id,
            'status' => 'active',
        ]);
    }

    public function test_user_cannot_create_connection_with_devices_they_do_not_own(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $computer = Computer::factory()->create(['user_id' => $otherUser->id]);
        $phone = Phone::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/connections', [
            'computer_id' => $computer->id,
            'phone_id' => $phone->id,
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Dispositivo no autorizado para este usuario',
            ]);
    }

    public function test_duplicate_active_connection_is_closed_automatically(): void
    {
        $user = User::factory()->create();
        $computer = Computer::factory()->create(['user_id' => $user->id]);
        $phone = Phone::factory()->create(['user_id' => $user->id]);

        $firstConn = Connection::factory()->create([
            'computer_id' => $computer->id,
            'phone_id' => $phone->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/connections', [
            'computer_id' => $computer->id,
            'phone_id' => $phone->id,
            'status' => 'active',
        ]);

        $response->assertStatus(201);

        // Previous connection must be closed
        $this->assertDatabaseHas('connections', [
            'id' => $firstConn->id,
            'status' => 'closed',
        ]);
    }
}
