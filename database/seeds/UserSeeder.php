<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	"name"=>"admin",
        	"email"=>"admin@gmail.com",
        	"password"=>bcrypt("password")
        ]);
        User::create([
        	"name"=>"staff",
        	"email"=>"staff@gmail.com",
        	"password"=>bcrypt("password")
        ]);
    }
}
