<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Book;

use Spatie\Permission\Models\Role;
//use Illuminate\Database\Eloquent\Builder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {

        ####### Seed Admin #######
        $user = User::factory()->create();
        $user->assignRole('admin');   

        ####### Seed reviewer #######
        $reviewer = 1;
        while ($reviewer <= 5) {
            $user = User::factory()->create();
            $user->assignRole('reviewer');
            $reviewer++;
        }

        ####### Seed auditor #######
        $auditor = 1;
        while ($auditor <=5) {
            $user = User::factory()->create();
            $user->assignRole('auditor');
            $auditor++;
        }

        ####### Seed ambassadors #######
        $user = User::factory()->count(20)->create();
    }   

}
