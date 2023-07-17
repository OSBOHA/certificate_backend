<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        DB::table('section')->insert([
            'name' => 'تنمية'
        ]);
        //2
        DB::table('section')->insert([
            'name' => 'فكري'
        ]);
        //3
        DB::table('section')->insert([
            'name' => 'تربية'
        ]);
        //4
        DB::table('section')->insert([
            'name' => 'اجتماعي'
        ]);
        //5
        DB::table('section')->insert([
            'name' => 'تاريخي'
        ]);
        //6
        DB::table('section')->insert([
            'name' => 'أدبي'
        ]);

        //7
        DB::table('section')->insert([
            'name' => 'سياسي'
        ]);
        //8
        DB::table('section')->insert([
            'name' => 'علمي'
        ]);
        //9
        DB::table('section')->insert([
            'name' => 'ديني'
        ]);
        //10
        DB::table('section')->insert([
            'name' => 'إقتصادي'
        ]);
        //11
        DB::table('section')->insert([
            'name' => 'عسكري'
        ]);
        //12
        DB::table('section')->insert([
            'name' => 'سير الصحابة'
        ]);
        
        //13
        DB::table('section')->insert([
            'name' => 'انجليزي'
        ]);
        
        //14
        DB::table('section')->insert([
            'name' => 'خيال علمي / أطفال'
        ]);
        
        //15
        DB::table('section')->insert([
            'name' => 'انجليزي'
        ]);

    }
}
