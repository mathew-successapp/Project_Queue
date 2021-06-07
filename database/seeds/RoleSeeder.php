<?php

use Illuminate\Database\Seeder;
use App\Model\Roles;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roles::create([
            'name' => 'Sales Engineering Manager'
        ]);
        Roles::create([
            'name' => 'Sales Engineer'
        ]);
        Roles::create([
            'name' => 'Client'
        ]);
    }
}
