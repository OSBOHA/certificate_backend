<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FqasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//question	answer
        $fqas = 1;
        while ($fqas <= 10) {
            DB::table('fqas')->insert([
                'question' => 'question' . $fqas,
                'answer' => 'answer' . $fqas,
            ]);
        $fqas++;    
        }
    }
}
