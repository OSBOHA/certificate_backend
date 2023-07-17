<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Book;


class User_BookSeeder extends Seeder
{

    public function run()
    {
        $status =['review'];

        $users = User::all()->pluck('id')->toArray(); //all user id
        for ($i=0; $i < count($users) ; $i++) { //for each user
            $nBooks = rand(0,5);
            if ($nBooks > 0){
                $books = Book::inRandomOrder()->limit($nBooks)->pluck('id')->toArray();
                DB::table('user_book')->insert([ //last book status
                    'status' => Arr::random($status),
                    'user_id' => $users[$i],
                    'book_id' => $books[0]
                ]);


                $j = count($books)-1 ;
                while ($j > 0) { ////previous books status = finished
                    DB::table('user_book')->insert([
                        'status' => 'finished',
                        'user_id' => $users[$i],
                        'book_id' => $books[$j]
                    ]);
                    $j--;
                }
            }
        }




    }
}
