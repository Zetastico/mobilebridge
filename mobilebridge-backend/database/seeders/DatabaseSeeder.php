<?php

namespace Database\Seeders;

use App\Models\Computer;
use App\Models\Connection;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Demo User
        $user = User::updateOrCreate(
            ['email' => 'admin@mobilebridge.com'],
            [
                'name' => 'MobileBridge Admin',
                'password' => Hash::make('password123'),
            ]
        );

        $demoUser = User::updateOrCreate(
            ['email' => 'user@mobilebridge.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password123'),
            ]
        );

        // Computers for Admin
        $pc1 = Computer::updateOrCreate(
            ['device_uuid' => 'pc-laptop-dell-uuid-001'],
            [
                'user_id' => $user->id,
                'name' => 'PC Laboratorio Principal',
                'description' => 'Estación de trabajo Linux/Windows con soporte de streaming',
                'local_ip' => '192.168.1.50',
                'local_port' => 5050,
                'status' => 'online',
                'last_seen_at' => now(),
                'capabilities' => [
                    'camera_preview' => true,
                    'camera_capture' => true,
                    'sensors' => true,
                    'file_transfer' => true,
                ],
            ]
        );

        $pc2 = Computer::updateOrCreate(
            ['device_uuid' => 'pc-desktop-oficina-002'],
            [
                'user_id' => $user->id,
                'name' => 'PC Oficina',
                'description' => 'Servidor de oficina local',
                'local_ip' => '192.168.1.55',
                'local_port' => 5050,
                'status' => 'offline',
                'last_seen_at' => now()->subHours(2),
                'capabilities' => [
                    'camera_preview' => true,
                    'camera_capture' => true,
                    'sensors' => false,
                    'file_transfer' => true,
                ],
            ]
        );

        // Phones for Admin
        $phone1 = Phone::updateOrCreate(
            ['device_uuid' => 'phone-pixel7-uuid-001'],
            [
                'user_id' => $user->id,
                'name' => 'Google Pixel 7',
                'model' => 'Pixel 7 (Pro)',
                'android_version' => 'Android 14 (API 34)',
                'status' => 'online',
                'last_seen_at' => now(),
            ]
        );

        $phone2 = Phone::updateOrCreate(
            ['device_uuid' => 'phone-samsung-s23-002'],
            [
                'user_id' => $user->id,
                'name' => 'Samsung Galaxy S23',
                'model' => 'SM-S911B',
                'android_version' => 'Android 14',
                'status' => 'offline',
                'last_seen_at' => now()->subDays(1),
            ]
        );

        // Connections
        Connection::create([
            'phone_id' => $phone1->id,
            'computer_id' => $pc1->id,
            'status' => 'active',
            'started_at' => now()->subMinutes(15),
            'ended_at' => null,
        ]);

        Connection::create([
            'phone_id' => $phone2->id,
            'computer_id' => $pc2->id,
            'status' => 'closed',
            'started_at' => now()->subHours(3),
            'ended_at' => now()->subHours(2),
        ]);
    }
}
