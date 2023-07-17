<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Book;
use App\Models\Thesis;




class ThesisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        $reviewers = User::whereHas('roles' , function($q){
            $q->where('name','reviewer');
        })->pluck('id')->toArray();
        $auditors = User::whereHas('roles' , function($q){
            $q->where('name','auditor');
        })->pluck('id')->toArray();

        ### Thesis for Finished and stage_two book ###
        $user_books = DB::table('user_book')->select('id','book_id')->where('status','!=','stage_one')->get();
        foreach ($user_books as $user_book) {
            $book_pages = Book::where('id',$user_book->book_id)->pluck('pages')->first();
            $nThesis = rand(8, 12);
            for ($j=0; $j < $nThesis ; $j++) { 
                Thesis::factory()->create([
                    'starting_page'	=> strval(rand(1, floor($book_pages/2))),
                    'ending_page'	=> rand( floor($book_pages/2)+1,  $book_pages),
                    'status'	=> 'audited',
                    'degree'	=> rand(70, 100),
                    'reviewer_id' => Arr::random($reviewers),
                    'auditor_id' => Arr::random($auditors),
                    'user_book_id'=>$user_book->id,
                ]);
            }
        }


        ### Thesis for stage_one book ###
        $stage_oneBooks = DB::table('user_book')->select('id','book_id')->where('status','stage_one')->get();

        foreach ($stage_oneBooks as $stage_oneBook) {
            $book_pages = Book::where('id',$stage_oneBook->book_id)->pluck('pages')->first();
            $status =['audit' , 'review' , 'rejected' ,'audited',null];
            $status_degree = [
                'rejected' => rand(0,69),
                'audited' => rand(70,100)  
            ];
            $nThesis = rand(8, 12);
            for ($j=0; $j < $nThesis ; $j++) { 
                Thesis::factory()->create([
                    'starting_page'	=> strval(rand(1, floor($book_pages/2))),
                    'ending_page'	=> rand( floor($book_pages/2)+1,  $book_pages),
                    'status'	=> $s = Arr::random($status),
                    'degree'	=> $status_degree[$s] ?? null,
                    'reviewer_id' => Arr::random($reviewers),
                    'auditor_id' => Arr::random($auditors),
                    'user_book_id'=> $stage_oneBook->id,
                ]);
            }    
        }
    }        

}

