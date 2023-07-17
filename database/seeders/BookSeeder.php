<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $csvFile = fopen(base_path("database/data/books.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Book::create([
                    'name' => $data['0'],
                    'level_id' => $data['1'],
                    'section_id' => $data['2'],
                    'pages' => $data['3'],
                    'start_page'=>1,
                    "end_page"=>300,            
                    'language_id' => 1,
                    "section_id" => 1,

                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
