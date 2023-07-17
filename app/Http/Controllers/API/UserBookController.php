<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Book;
use App\Models\Certificates;
use App\Models\GeneralInformations;
use App\Models\Question;
use App\Models\Thesis;
use App\Models\User;
use App\Models\UserBook;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


class UserBookController extends BaseController
{

    public function index()
    {

        $userbook = UserBook::all();
        return $this->sendResponse($userbook, "User Books");
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $count = UserBook::where(function ($query) {
            $query->where('status', '!=', 'finished')
                ->Where('status', '!=', 'rejected')
                ->WhereNull('status');
        })->where('user_id', Auth::id())->count();

        if ($count > 0) {
            return $this->sendError('You have an open book');
        }
        try {
            $userBook = UserBook::firstOrCreate([
                'book_id' => $request->book_id,
                'user_id' => Auth::id()
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            echo ($e);
            return $this->sendError('User or book does not exist');
        }
        return $this->sendResponse($userBook, "User book created");
    }


    public function getByBookID($bookId)
    {
        $userBook['userBook'] = UserBook::with('thesises', 'questions', 'generalInformation')->where('book_id', $bookId)->where('user_id', Auth::id())->first();
        $userBook['completionPercentage'] = 10;


        //50 \ 8 => 6.25 for each (50%)
        $theses = Thesis::where('user_book_id', $userBook['userBook']->id)->where(function ($query) {
            $query->where('status', '!=', 'retard')->where('status', '!=', 'rejected')->orWhereNull('status');
        })->count();
        if ($theses > 8) {
            $userBook['completionPercentage'] = $userBook['completionPercentage'] + (6.25 * 8);
        } else {
            $userBook['completionPercentage'] = $userBook['completionPercentage'] + (6.25 * $theses);
        }


        //25 \ 5 => 5 for each (25%)
        $questions = Question::where('user_book_id', $userBook['userBook']->id)->where(function ($query) {
            $query->where('status', '!=', 'retard')->where('status', '!=', 'rejected')->orWhereNull('status');
        })->count();
        if ($questions > 5) {
            $userBook['completionPercentage'] = $userBook['completionPercentage'] + (5 * 5);
        } else {
            $userBook['completionPercentage'] = $userBook['completionPercentage'] + (5 * $questions);
        }

        $generalInformations = GeneralInformations::where('user_book_id', $userBook['userBook']->id)->where(function ($query) {
            $query->where('status', '!=', 'retard')->where('status', '!=', 'rejected')->orWhereNull('status');
        })->count();
        $userBook['completionPercentage'] = $userBook['completionPercentage'] + (15 * $generalInformations); // only one  (15%)

        return $this->sendResponse($userBook, "User book created");
    }

    public function show($id)
    {
        $userBook = UserBook::find($id);
        if (is_null($userBook)) {
            return $this->sendError('UserBook does not exist');
        }
        return $userBook;
    }

    public function lastAchievement()
    {
        $userBook['last_achievement'] = UserBook::where('user_id', Auth::id())->latest()->first();
        $userBook['statistics'] = UserBook::join('general_informations', 'user_book.id', '=', 'general_informations.user_book_id')
            ->join('questions', 'user_book.id', '=', 'questions.user_book_id')
            ->join('thesis', 'user_book.id', '=', 'thesis.user_book_id')
            ->select(DB::raw('avg(general_informations.degree) as general_informations_degree,avg(questions.degree) as questions_degree,avg(thesis.degree) as thesises_degree'))
            ->where('user_id', Auth::id())
            ->orderBy('user_book.created_at', 'desc')->first();

        return $this->sendResponse($userBook, 'Last Achevment');
    }


    public function finishedAchievement()
    {
        $userBook = UserBook::where('user_id', Auth::id())->where('status', 'finished')->get();
        return $this->sendResponse($userBook, 'Last Achevment');
    }


    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }
        try {
            $userBook = UserBook::find($id);
            $userBook->status = $request->status;
            $userBook->save();
            $user = User::find($userBook->user_id);
            $theses = Thesis::where('user_book_id', $id)->where('status', 'ready')->update(['status' => $request->status]);
            $questions = Question::where('user_book_id', $id)->where('status', 'ready')->update(['status' => $request->status]);
            $generalInformations = GeneralInformations::where('user_book_id', $id)->where('status', 'ready')->update(['status' => $request->status]);
            if ($request->status == 'review') {
                $user->notify(
                    (new \App\Notifications\SetAchievement())->delay(now()->addMinutes(2))
                );
            } else {
                $user->notify(
                    (new \App\Notifications\RejectAchievement())->delay(now()->addMinutes(2))
                );
            }
        } catch (\Error $e) {
            return $this->sendError($e);
        }
        return $this->sendResponse($userBook, 'UserBook updated Successfully!');
    }

    public function destroy($id)
    {
        $userBook = UserBook::find($id);
        if (!$userBook) {
            return $this->sendError('UserBook does not exist');
        }
        
        if (is_null($userBook->status)) {
            $userBook->delete();
            return $this->sendResponse(null, 'UserBook deleted successfully!');
        }
        else{
            return $this->sendError('UserBook status is not null');
        }
    }


    public function changeStatus(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }


        $userBook = UserBook::find($id);

        $userBook->status = $input['status'];
        try {
            $user = User::find($userBook->user_id);
            $userBook->save();
            if ($userBook->status == 'rejected' || $userBook->status == 'retard')
                $user->notify(
                    (new \App\Notifications\RejectAchievement())->delay(now()->addMinutes(2))
                );
        } catch (\Error $e) {
            return $this->sendError('UserBook does not exist');
        }
        return $this->sendResponse($userBook, 'UserBook updated Successfully!');
    }
    public function review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        try {
            //REJECT OR RETARD ENTIER USER BOOK
            $userBook = UserBook::find($request->id);
            $user = User::find($userBook->user_id);
            $userBook->status = $request->status;
            $userBook->reviews = $request->reviews;
            $userBook->save();
            $theses = Thesis::where('user_book_id', $request->id)->update(['status' => $request->status, 'reviews' => $request->reviews]);
            $questions = Question::where('user_book_id', $request->id)->update(['status' => $request->status, 'reviews' => $request->reviews]);
            $generalInformations = GeneralInformations::where('user_book_id', $request->id)->update(['status' => $request->status, 'reviews' => $request->reviews]);
            $user->notify(
                (new \App\Notifications\RejectAchievement())->delay(now()->addMinutes(2))
            );
        } catch (\Error $e) {
            return $this->sendError('User Book does not exist');
        }
    }


    public function checkOpenBook()
    {
        $id = Auth::id();

        $open_book = UserBook::where('user_id', $id)->where('status', '!=', 'finished')->count();


        return $this->sendResponse($open_book, 'Open Book');
    }


    public function getStageStatus($id)
    {

        $thesis = Thesis::where('user_book_id', $id)->where('status', 'audit')->exists();
        $question = Question::where('user_book_id', $id)->where('status', 'audit')->exists();
        $status = $thesis + $question;


        return $this->sendResponse($status, 'Status');
    }

    public function readyToAudit()
    {

        $readyToAudit['theses'] = DB::select("SELECT user_book_id FROM(SELECT COUNT(id) AS totalThesis, user_book_id FROM thesis WHERE STATUS = 'accept' GROUP BY user_book_id) AS b WHERE b.totalThesis>=8");
        $readyToAudit['questions'] = DB::select("SELECT user_book_id FROM(SELECT COUNT(id) AS totalQuestions, user_book_id FROM questions WHERE STATUS = 'accept' GROUP BY user_book_id) AS b WHERE b.totalQuestions >=5");
        return $this->sendResponse($readyToAudit, 'readyToAudit');
    }

    public function checkCertificate($id)
    {
        $status = Certificates::where('user_book_id', $id)->exists();
        return $this->sendResponse($status, 'Status');
    }

    


    public function getStatistics($id)
    {
        $thesisFinalDegree = Thesis::where("user_book_id", $id)->avg('degree');
        $questionFinalDegree = Question::where("user_book_id", $id)->avg('degree');
        $generalInformationsFinalDegree = GeneralInformations::where("user_book_id", $id)->avg('degree');
        $finalDegree = ($thesisFinalDegree + $questionFinalDegree + $generalInformationsFinalDegree) / 3;
        $response = [
            "thesises" => intval($thesisFinalDegree),
            "questions" => intval($questionFinalDegree),
            "general_informations" => intval($generalInformationsFinalDegree),
            "final" => intval($finalDegree),
        ];
        return $this->sendResponse($response, 'Statistics');
    }

    public function getGeneralstatistics()
    {
        $thesis = ThesisController::thesisStatistics();
        $questions = QuestionController::questionsStatistics();
        $generalInformations = GeneralInformationsController::generalInformationsStatistics();
        $certificates = Certificates::count();
        $users = User::count();
        $books = Book::count();
        $auditer = Role::where('name', 'auditer')->count();
        $reviewer = Role::where('name', 'reviewer')->count();



        $response = [
            "thesises" => $thesis,
            "questions" => $questions,
            "general_informations" => $generalInformations,
            "certificates" => $certificates,
            'users' => $users,
            "books" => $books,
            "auditers" => $auditer,
            "reviewer" => $reviewer,
        ];
        return $this->sendResponse($response, 'Statistics');
    }


    public function getUserBookByStatus($user_book_status)
    {
        $user_books = UserBook::where('status', $user_book_status)->with('user')->with('book')->get();
        return $this->sendResponse($user_books, 'UserBooks');
    }
}
