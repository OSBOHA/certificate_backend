<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Support\Facades\Auth;


class BooksController extends BaseController
{

    public function index()
    {
        $books['books'] =Book::with('section', 'level')->paginate(20);

        // SELECT * FROM `user_book` WHERE user_id =1 and (status != 'finished' || status is null )

        $books['open_book']= Book::with('section', 'level')->whereHas('userBook', function ($q) {
            $q->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('status', '!=', 'finished')
                ->where('status', '!=', 'rejected')
                ->orWhereNull('status');
            });

        })->get();

        return $this->sendResponse($books, "Books");
    }

    public function checkAchievement($id)
    {

        $already_have_one = UserBook::where('user_id', Auth::id())->where(function ($query) {
            $query->where('status', '!=', 'finished')
            ->where('status', '!=', 'rejected')
            ->orWhereNull('status');
        })->first();
        return $this->sendResponse($already_have_one, "Already Have One");
    }
    public function bookByName($name)
    {
        $books = Book::with('section', 'level')->where('name','LIKE','%'.$name.'%')->paginate(9);
        if($books->isNotEmpty()){
            return $this->sendResponse($books, "Already Have One");
        }
        else{
            return $this->sendResponse('empty','No Books Found');
        }        
    }


    public function store(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'start_page' => 'required',
            'name' => 'required',
            'section_id' => 'required',
            "level_id" => 'required',
            "end_page" => 'required',
            "writer" => 'required',
            'publisher' => 'required',
            'link' => 'required',
            'brief' => 'required', 
            'language_id' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validate Error', $validator->errors());
        }

        try {
            $book = Book::create($input);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return $this->sendError('Book already exist');
            } else {
                return $this->sendError('Type or Category does not exist');
            }
        }

        return $this->sendResponse($book, 'Book added Successfully!');
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (is_null($book)) {
            return $this->sendError('Book does not exist');
        }
        return $this->sendResponse($book, "Book");
    }


    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'start_page' => 'required',
            'name' => 'required',
            'section_id' => 'required',
            "level_id" => 'required',
            "type_id" => 'required',
            "end_page" => 'required',
            "writer" => 'required',
            'publisher' => 'required',
            'link' => 'required',
            'brief' => 'required', 
            'language_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $book = Book::find($id);

        $updateParam = [
            'start_page' => $input['start_page'],
            'name' => $input['name'],
            'section_id' =>  $input['section_id'],
            "level_id" =>  $input['level_id'],
            "end_page" =>  $input['end_page'],
            "writer" =>  $input['writer'],
            'publisher' =>  $input['publisher'],
            'link' =>  $input['link'],
            'brief' =>  $input['brief'], 
		'type_id' => $input['type_id'],
            'language_id' => $input['language_id']
        ];
        try {
            $book->update($updateParam);
        } catch (\Error $e) {
            return $this->sendError('Book does not exist');
        }
        return $this->sendResponse($book, 'Book updated Successfully!');
    }

    public function destroy($id)
    {
        $result = Book::destroy($id);

        if ($result == 0) {

            return $this->sendError('Book does not exist');
        }
        return $this->sendResponse($result, 'Book deleted Successfully!');
    }

    public function getBooksForUser()
    {
        $id = Auth::id();
        $current_book = Book::select('books.*', 'user_book.status')->join('user_book', 'books.id', '=', 'user_book.book_id')->where('user_id', $id)->where('status', "!=",'finished')->get();

        $books = Book::select([
            'books.*',
            'has_certificate' => UserBook::join('users', 'user_book.user_id', '=', 'users.id')
                ->selectRaw('count(status)')
                ->whereColumn('books.id', 'user_book.book_id')
                ->where('user_book.user_id', $id)
                ->where('user_book.status', "finished"),
            'certificates_count' => UserBook::selectRaw('count(status)')
                ->whereColumn('books.id', 'user_book.book_id')
                ->where('user_book.status', "finished"),
        ])->get();
        $res = ['open_book' => $current_book, 'books' => $books];
        return $this->sendResponse($res, 'Books');
    }


    public function getOpenBook($id)
    {

        $userId = Auth::id();

        try {
            $book = Book::find($id)->load(['userBook' => function ($query)  use ($userId) {
                $query->where('user_id', $userId);
            }]);
        } catch (\Error $e) {
            return $this->sendError('Book does not exist');
        }
        return $this->sendResponse($book, 'Books');
    }

        public function getUserBook($id)

    {
        try {

            $userBook['book'] =Book::with('type', 'category')->where('id', $id)->first();
            $userBook['user_book'] = UserBook::where('user_id', Auth::id())->where('book_id', $id)->first();
            $userBook['already_have_one'] = UserBook::where('status', "!=",'finished')->where('user_id', Auth::id())->count();

            return $this->sendResponse($userBook, 'userBook');
        } catch (\Error $e) {
            return $this->sendError('Book does not exist');
        }
    }

    
}
