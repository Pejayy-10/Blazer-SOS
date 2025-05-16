<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Philippines as the main country
        $philippines = Country::create([
            'name' => 'Philippines',
            'code' => 'PHL',
            'phone_code' => '+63'
        ]);
        
        // Add some major cities in the Philippines
        $philippineCities = [
            ['name' => 'Manila', 'state_province' => 'Metro Manila', 'zip_code' => '1000'],
            ['name' => 'Quezon City', 'state_province' => 'Metro Manila', 'zip_code' => '1100'],
            ['name' => 'Cebu City', 'state_province' => 'Cebu', 'zip_code' => '6000'],
            ['name' => 'Davao City', 'state_province' => 'Davao del Sur', 'zip_code' => '8000'],
            ['name' => 'Makati', 'state_province' => 'Metro Manila', 'zip_code' => '1200'],
            ['name' => 'Baguio', 'state_province' => 'Benguet', 'zip_code' => '2600'],
            ['name' => 'Iloilo City', 'state_province' => 'Iloilo', 'zip_code' => '5000'],
            ['name' => 'Cagayan de Oro', 'state_province' => 'Misamis Oriental', 'zip_code' => '9000'],
            ['name' => 'Zamboanga City', 'state_province' => 'Zamboanga del Sur', 'zip_code' => '7000'],
            ['name' => 'Taguig', 'state_province' => 'Metro Manila', 'zip_code' => '1630'],
        ];
        
        foreach ($philippineCities as $city) {
            City::create([
                'name' => $city['name'],
                'country_id' => $philippines->id,
                'state_province' => $city['state_province'],
                'zip_code' => $city['zip_code']
            ]);
        }
        
        // Add a few more countries for international students
        $countries = [
            ['name' => 'United States', 'code' => 'USA', 'phone_code' => '+1'],
            ['name' => 'United Kingdom', 'code' => 'GBR', 'phone_code' => '+44'],
            ['name' => 'Australia', 'code' => 'AUS', 'phone_code' => '+61'],
            ['name' => 'Canada', 'code' => 'CAN', 'phone_code' => '+1'],
            ['name' => 'Japan', 'code' => 'JPN', 'phone_code' => '+81'],
        ];
        
        foreach ($countries as $countryData) {
            Country::create($countryData);
        }
    }
}
