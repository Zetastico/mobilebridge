<?php

namespace Tests\Feature;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_phone(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/phones/register', [
            'device_uuid' => 'phone-uuid-777',
            'name' => 'Mi Samsung S23',
            'model' => 'SM-S911B',
            'android_version' => '14',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Teléfono registrado y activo',
                'phone' => [
                    'device_uuid' => 'phone-uuid-777',
                    'name' => 'Mi Samsung S23',
                    'model' => 'SM-S911B',
                    'android_version' => '14',
                    'status' => 'online',
                ],
            ]);

        $this->assertDatabaseHas('phones', [
            'user_id' => $user->id,
            'device_uuid' => 'phone-uuid-777',
        ]);
    }

    public function test_user_can_send_phone_heartbeat(): void
    {
        $user = User::factory()->create();
        $phone = Phone::factory()->create([
            'user_id' => $user->id,
            'device_uuid' => 'phone-heartbeat-uuid',
            'status' => 'online',
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/phones/heartbeat', [
            'device_uuid' => 'phone-heartbeat-uuid',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Heartbeat recibido',
            ]);
    }

    public function test_user_can_mark_phone_offline(): void
    {
        $user = User::factory()->create();
        $phone = Phone::factory()->create([
            'user_id' => $user->id,
            'device_uuid' => 'phone-offline-uuid',
            'status' => 'online',
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/phones/offline', [
            'device_uuid' => 'phone-offline-uuid',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Teléfono marcado como offline',
            ]);

        $this->assertDatabaseHas('phones', [
            'id' => $phone->id,
            'status' => 'offline',
        ]);
    }

    public function test_user_can_list_own_phones(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Phone::factory()->count(2)->create(['user_id' => $user->id]);
        Phone::factory()->count(3)->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/phones');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'count' => 2,
            ]);
    }

    public function test_user_cannot_access_other_user_phone(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherPhone = Phone::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/phones/{$otherPhone->id}");
        $response->assertStatus(404);
    }
}
