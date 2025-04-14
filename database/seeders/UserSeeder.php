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
            'name' => 'Unedo Cell',
            'email' => 'unedo@mail.test',
            'password' => bcrypt('password'),
            'is_active' => true,
            'phone' => preg_replace('/[^\d]/', '', fake()->phoneNumber()),
        ])->assignRole('admin');

        User::create([
            'name' => 'Test User',
            'email' => 'user@mail.test',
            'password' => bcrypt('password'),
            'is_active' => true,
            'phone' => preg_replace('/[^\d]/', '', fake()->phoneNumber()),
        ])->assignRole('user');

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
