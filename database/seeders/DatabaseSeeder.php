<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nodes')->insert([
            ['name' => 'A'],
            ['name' => 'B'],
            ['name' => 'C'],
            ['name' => 'D'],
            ['name' => 'E'],
            ['name' => 'F'],
            ['name' => 'G'],
            ['name' => 'H']
        ]);
        DB::table('connections')->insert([
            ['name' => 'A-B','distance' => 10, 'speed'=> 10, 'origin'=> 1,'destination'=>2, 'unidirectional'=> 1],
            ['name' => 'E-D','distance' => 10, 'speed'=> 10, 'origin'=> 5,'destination'=>4, 'unidirectional'=> 1],
            ['name' => 'D-C','distance' => 10, 'speed'=> 10, 'origin'=> 4,'destination'=>3, 'unidirectional'=> 1],
            ['name' => 'A-D','distance' => 10, 'speed'=> 10, 'origin'=> 1,'destination'=>4, 'unidirectional'=> 1],
            ['name' => 'B-E','distance' => 10, 'speed'=> 10, 'origin'=> 2,'destination'=>5, 'unidirectional'=> 1],
            ['name' => 'F-C','distance' => 10, 'speed'=> 10, 'origin'=> 6,'destination'=>3, 'unidirectional'=> 1],
            ['name' => 'G-H','distance' => 10, 'speed'=> 10, 'origin'=> 7,'destination'=>8, 'unidirectional'=> 1],
        ]);
    }
}
