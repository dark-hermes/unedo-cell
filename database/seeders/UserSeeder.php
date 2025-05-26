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
        // Cities in North Sumatra
        $northSumatraCities = [
            'Medan', 'Binjai', 'Pematang Siantar', 'Tebing Tinggi', 'Tanjung Balai',
            'Sibolga', 'Padang Sidempuan', 'Gunungsitoli', 'Balige', 'Tarutung',
            'Sidikalang', 'Kabanjahe', 'Rantau Prapat', 'Pangkalan Brandan', 'Aek Kanopan'
        ];

        // Common street names in North Sumatra
        $streetNames = [
            'Sisingamangaraja', 'Diponegoro', 'Gatot Subroto', 'Sudirman', 'Ahmad Yani',
            'Pahlawan', 'Merdeka', 'Imam Bonjol', 'Teuku Umar', 'Cut Nyak Dien',
            'Kartini', 'Maju', 'Bhayangkara', 'Flamboyan', 'Anggrek'
        ];

        // Admin user
        User::create([
            'name' => 'Unedo Cell Admin',
            'email' => env('MAIL_FROM_ADDRESS'),
            'password' => bcrypt('password'),
            'is_active' => true,
            'phone' => preg_replace('/[^\d]/', '', fake()->phoneNumber()),
        ])->assignRole('admin');

        // Test user
        $user = User::create([
            'name' => 'Test User',
            'email' => env('USERTEST_MAIL'),
            'password' => bcrypt('password'),
            'is_active' => true,
            'phone' => preg_replace('/[^\d]/', '', fake()->phoneNumber()),
        ])->assignRole('user');

        // Add addresses for test user
        $user->addresses()->create([
            'name' => 'Rumah',
            'address' => 'Jl. '.$streetNames[array_rand($streetNames)].' No. '.rand(1, 100).', Medan, Sumatera Utara 20111',
            'is_default' => false,
            'phone' => $user->phone,
            'latitude' => '3.589665', // Medan coordinates
            'longitude' => '98.673826',
            'note' => 'Taruh di depan rumah',
            'recipient_name' => fake()->name(),
        ]);

        $user->addresses()->create([
            'name' => 'Kantor',
            'address' => 'Jl. '.$streetNames[array_rand($streetNames)].' No. '.rand(1, 100).', Medan, Sumatera Utara 20222',
            'is_default' => true,
            'phone' => $user->phone,
            'latitude' => '3.595196', // Medan coordinates (slightly different)
            'longitude' => '98.672222',
            'note' => 'Taruh di depan kantor',
            'recipient_name' => fake()->name(),
        ]);

        // Active users with addresses in various North Sumatra cities
        User::factory(10)->create([
            'is_active' => true,
        ])->each(function ($user) use ($northSumatraCities, $streetNames) {
            $user->assignRole('user');
            $city = $northSumatraCities[array_rand($northSumatraCities)];
            $postalCode = rand(20000, 23000);
            
            $user->addresses()->create([
                'name' => 'Rumah',
                'address' => 'Jl. '.$streetNames[array_rand($streetNames)].' No. '.rand(1, 100).', '.$city.', Sumatera Utara '.$postalCode,
                'is_default' => true,
                'phone' => $user->phone,
                'latitude' => $this->generateNorthSumatraLatitude($city),
                'longitude' => $this->generateNorthSumatraLongitude($city),
                'note' => fake()->sentence(),
                'recipient_name' => $user->name,
            ]);
        });

        // Inactive users
        User::factory(5)->create([
            'is_active' => false,
        ])->each(function ($user) {
            $user->assignRole('user');
        });

        // Add random addresses to active users within North Sumatra
        User::where('is_active', true)->get()->each(function ($user) use ($northSumatraCities, $streetNames) {
            $city = $northSumatraCities[array_rand($northSumatraCities)];
            $postalCode = rand(20000, 23000);
            
            $user->addresses()->create([
                'name' => 'Alamat Tambahan',
                'address' => 'Jl. '.$streetNames[array_rand($streetNames)].' No. '.rand(1, 100).', '.$city.', Sumatera Utara '.$postalCode,
                'is_default' => false,
                'phone' => $user->phone,
                'latitude' => $this->generateNorthSumatraLatitude($city),
                'longitude' => $this->generateNorthSumatraLongitude($city),
                'note' => 'Alamat tambahan di '.$city,
                'recipient_name' => fake()->name(),
            ]);
        });
    }

    /**
     * Generate latitude based on city in North Sumatra
     */
    private function generateNorthSumatraLatitude(string $city): float
    {
        // Approximate latitude ranges for cities in North Sumatra
        $cityLatitudes = [
            'Medan' => ['min' => 3.50, 'max' => 3.70],
            'Binjai' => ['min' => 3.55, 'max' => 3.65],
            'Pematang Siantar' => ['min' => 2.90, 'max' => 3.00],
            'Tebing Tinggi' => ['min' => 3.30, 'max' => 3.40],
            'Tanjung Balai' => ['min' => 2.95, 'max' => 3.05],
            'Sibolga' => ['min' => 1.70, 'max' => 1.80],
            'Padang Sidempuan' => ['min' => 1.35, 'max' => 1.45],
            'Gunungsitoli' => ['min' => 1.25, 'max' => 1.35],
            'Balige' => ['min' => 2.30, 'max' => 2.40],
            'Tarutung' => ['min' => 2.00, 'max' => 2.10],
            'Sidikalang' => ['min' => 2.70, 'max' => 2.80],
            'Kabanjahe' => ['min' => 3.00, 'max' => 3.10],
            'Rantau Prapat' => ['min' => 2.10, 'max' => 2.20],
            'Pangkalan Brandan' => ['min' => 4.00, 'max' => 4.10],
            'Aek Kanopan' => ['min' => 4.20, 'max' => 4.30]
        ];

        $range = $cityLatitudes[$city] ?? $cityLatitudes['Medan'];
        return fake()->randomFloat(6, $range['min'], $range['max']);
    }

    /**
     * Generate longitude based on city in North Sumatra
     */
    private function generateNorthSumatraLongitude(string $city): float
    {
        // Approximate longitude ranges for cities in North Sumatra
        $cityLongitudes = [
            'Medan' => ['min' => 98.60, 'max' => 98.75],
            'Binjai' => ['min' => 98.40, 'max' => 98.50],
            'Pematang Siantar' => ['min' => 99.00, 'max' => 99.10],
            'Tebing Tinggi' => ['min' => 99.10, 'max' => 99.20],
            'Tanjung Balai' => ['min' => 99.80, 'max' => 99.90],
            'Sibolga' => ['min' => 98.70, 'max' => 98.80],
            'Padang Sidempuan' => ['min' => 99.20, 'max' => 99.30],
            'Gunungsitoli' => ['min' => 97.50, 'max' => 97.60],
            'Balige' => ['min' => 99.05, 'max' => 99.15],
            'Tarutung' => ['min' => 98.95, 'max' => 99.05],
            'Sidikalang' => ['min' => 98.30, 'max' => 98.40],
            'Kabanjahe' => ['min' => 98.50, 'max' => 98.60],
            'Rantau Prapat' => ['min' => 99.80, 'max' => 99.90],
            'Pangkalan Brandan' => ['min' => 98.20, 'max' => 98.30],
            'Aek Kanopan' => ['min' => 99.60, 'max' => 99.70]
        ];

        $range = $cityLongitudes[$city] ?? $cityLongitudes['Medan'];
        return fake()->randomFloat(6, $range['min'], $range['max']);
    }
}