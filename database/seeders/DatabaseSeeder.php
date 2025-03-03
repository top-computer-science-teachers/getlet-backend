<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        User::factory()->create([
            'firstname' => 'Spartak',
            'lastname' => 'Vasilev',
            'phone' => '+79676243734',
            'city_id' => City::all()->random()->id,
        ]);
    }
}
