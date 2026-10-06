<?php

namespace Database\Factories;

use App\Models\Computer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Computer>
 */
class ComputerFactory extends Factory
{
    protected $model = Computer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'device_uuid' => (string) Str::uuid(),
            'name' => fake()->domainWord() . '-PC',
            'description' => fake()->sentence(),
            'local_ip' => '192.168.1.' . fake()->numberBetween(10, 200),
            'local_port' => 5050,
            'status' => 'online',
            'last_seen_at' => now(),
            'capabilities' => [
                'camera_preview' => true,
                'camera_capture' => true,
                'sensors' => true,
                'file_transfer' => true,
            ],
        ];
    }
}
