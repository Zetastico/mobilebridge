<?php

namespace Database\Factories;

use App\Models\Computer;
use App\Models\Connection;
use App\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Connection>
 */
class ConnectionFactory extends Factory
{
    protected $model = Connection::class;

    public function definition(): array
    {
        return [
            'computer_id' => Computer::factory(),
            'phone_id' => Phone::factory(),
            'status' => 'active',
            'started_at' => now(),
            'ended_at' => null,
        ];
    }
}
