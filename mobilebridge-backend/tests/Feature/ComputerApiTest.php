<?php

namespace Tests\Feature;

use App\Models\Computer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComputerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_computer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/computers/register', [
            'device_uuid' => 'pc-uuid-1234',
            'name' => 'PC Laboratorio',
            'description' => 'Estación principal',
            'local_ip' => '192.168.1.50',
            'local_port' => 5050,
            'capabilities' => ['camera' => true, 'sensors' => true],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'PC Agent registrado y activo',
                'computer' => [
                    'device_uuid' => 'pc-uuid-1234',
                    'name' => 'PC Laboratorio',
                    'local_ip' => '192.168.1.50',
                    'local_port' => 5050,
                    'status' => 'online',
                ],
            ]);

        $this->assertDatabaseHas('computers', [
            'user_id' => $user->id,
            'device_uuid' => 'pc-uuid-1234',
        ]);
    }

    public function test_user_can_send_heartbeat(): void
    {
        $user = User::factory()->create();
        $computer = Computer::factory()->create([
            'user_id' => $user->id,
            'device_uuid' => 'pc-heartbeat-uuid',
            'status' => 'online',
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/computers/heartbeat', [
            'device_uuid' => 'pc-heartbeat-uuid',
            'local_ip' => '192.168.1.60',
            'local_port' => 5050,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Heartbeat recibido',
            ]);
    }

    public function test_user_can_mark_computer_offline(): void
    {
        $user = User::factory()->create();
        $computer = Computer::factory()->create([
            'user_id' => $user->id,
            'device_uuid' => 'pc-offline-uuid',
            'status' => 'online',
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/computers/offline', [
            'device_uuid' => 'pc-offline-uuid',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Dispositivo marcado como offline',
            ]);

        $this->assertDatabaseHas('computers', [
            'id' => $computer->id,
            'status' => 'offline',
        ]);
    }

    public function test_user_can_list_own_computers(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Computer::factory()->count(2)->create(['user_id' => $user->id]);
        Computer::factory()->count(3)->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/computers');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'count' => 2,
            ]);
    }

    public function test_user_cannot_access_or_modify_other_user_computer(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherComputer = Computer::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/computers/{$otherComputer->id}");
        $response->assertStatus(404);

        $responseUpdate = $this->actingAs($user, 'sanctum')->putJson("/api/computers/{$otherComputer->id}", [
            'name' => 'Hacked Name',
        ]);
        $responseUpdate->assertStatus(404);

        $responseDelete = $this->actingAs($user, 'sanctum')->deleteJson("/api/computers/{$otherComputer->id}");
        $responseDelete->assertStatus(404);
    }
}
