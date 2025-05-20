<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Unedo Cell Admin',
            'email' => env('MAIL_FROM_ADDRESS'),
            'password' => bcrypt('password'),
            'is_active' => true,
            'phone' => preg_replace('/[^\d]/', '', fake()->phoneNumber()),
        ])->assignRole('admin');

        $user = User::create([
            'name' => 'Test User',
            'email' => env('USERTEST_MAIL'),
            'password' => bcrypt('password'),
            'is_active' => true,
            
            'phone' => preg_replace('/[^\d]/', '', fake()->phoneNumber()),
        ])->assignRole('user');
        
        $user->addresses()->create([
            'name' => 'Rumah',
            'address' => 'Jl. Sisingamangaraja, Aruan, Kec. Laguboti, Toba, Sumatera Utara 22381',
            'is_default' => false,
            'phone' => $user->phone,
            'latitude' => '-2.3521296275536945',
            'longitude' => '99.11777142261798',
            'note' => 'Taruh di depan rumah',
            'recipient_name' => fake()->name(),
        ]);

        $user->addresses()->create([
            'name' => 'Kantor',
            'address' => 'Jl. Diponegoro, Sitangkola, Kec. Laguboti, Toba, Sumatera Utara 22381',
            'is_default' => true,
            'phone' => $user->phone,
            'latitude' => '-2.3521296275536945',
            'longitude' => '99.11777142261798',
            'note' => 'Taruh di depan kantor',
            'recipient_name' => fake()->name(),
        ]);

        User::factory(10)->create([
            'is_active' => true,
        ])->each(function ($user) {
            $user->assignRole('user');
        });

        User::factory(5)->create([
            'is_active' => false,
        ])->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
