<?php

use Illuminate\Database\Seeder;
use App\Model\Permissions;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permissions::create([
            'name' => 'All',
            'user_id' => 1
        ]);
        Permissions::create([
            'name' => 'View',
            'user_id' => 2
        ]);
        Permissions::create([
            'name' => 'Update',
            'user_id' => 3
        ]);
    }
}
