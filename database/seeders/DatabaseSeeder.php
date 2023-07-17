<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            // RolesSeeder::class,
            // LevelSeeder::class,
            // SectionSeeder::class,
            // BookSeeder::class,
            // OneUserSeeder::class,
            // FqasSeeder::class,
            // UsersTableSeeder::class,
            // User_BookSeeder::class,
            // ThesisSeeder::class
        ]);
    }
}
