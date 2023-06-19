<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookLevel;
use App\Models\BookSection;
use App\Models\BookType;
use App\Models\UserBook;
use App\Models\Question;
use App\Models\GeneralInformations;
use App\Models\Thesis;
use App\Models\Quotation;
use App\Models\User;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookSection::create(['name' => 'book section']);
        BookLevel::create(['name' => 'book level']);
        Book::create([
            'name'=>"هذا كتاب جميل",
            'pages' => 122,
            'level_id' => 1,
            'section_id' => 1,


        ]);Book::create([
            'name'=>"هذا كتاب ",
            'pages' => 122,
            'level_id' => 1,
            'section_id' => 1,


        ]);Book::create([
            'name'=>"هذا  ",
            'pages' => 122,
            'level_id' => 1,
            'section_id' => 1,


        ]);
        UserBook::create([
            'book_id' => 1,
            "user_id" => 1
        ]);
    }
}
