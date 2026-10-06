<?php

namespace Database\Factories;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Phone>
 */
class PhoneFactory extends Factory
{
    protected $model = Phone::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'device_uuid' => (string) Str::uuid(),
            'name' => fake()->domainWord() . '-Phone',
            'model' => 'Pixel 7',
            'android_version' => '14',
            'status' => 'online',
            'last_seen_at' => now(),
        ];
    }
}
