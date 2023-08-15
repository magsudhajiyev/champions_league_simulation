<?php

namespace Database\Seeders;

use App\Models\FootballTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FootbalteamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FootballTeam::insert([
            ['name' => 'Liverpool', 'strength_points' => 70 ],
            ['name' => 'Manchester City', 'strength_points' => 75 ],
            ['name' => 'Chelsea', 'strength_points' => 60 ],
            ['name' => 'Arsenal', 'strength_points' => 80 ]
        ]);
    }
}
