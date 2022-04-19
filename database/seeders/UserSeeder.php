<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for($i=1;$i<=10;$i++)
        {
        	DB::table('users')->insert([
        		"name"=>$faker->name(),
        		"email"=>$faker->email(),
        		"password"=>Hash::make('12345')
        	]);
        }
    }
}
