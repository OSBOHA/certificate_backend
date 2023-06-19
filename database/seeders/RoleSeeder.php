<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin' , 'guard_name' => 'api']);
        $user = Role::create(['name' => 'user','guard_name' => 'api']);
        $reviewer = Role::create(['name' => 'reviewer','guard_name' => 'api']);
        $auditer = Role::create(['name' => 'auditer','guard_name'=>'api']);



    }
}
