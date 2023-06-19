<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\GeneralInformations;
use App\Models\Question;
use App\Models\Quotation;
use App\Models\Thesis;
use App\Models\User;
use App\Models\UserBook;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;



class QuestionController extends BaseController
{
    public function index()
    {

        $question = Question::all();
        return $this->sendResponse($question, "Questions");
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'quotes' => 'required',
            'quotes.*.text' => 'required',
            'user_book_id' => 'required',
            "starting_page" => 'required',
            "ending_page" => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $input = $request->all();
        $quotationInput = $input['quotes'];
        $qoutes = [];

        foreach ($quotationInput as $value) {

            $qoute = Quotation::create($value);
            array_push($qoutes, $qoute);
        }



        try {
            $newQuestion = Question::create($input);
            $newQuestion->quotation()->saveMany($qoutes);
        } catch (\Illuminate\Database\QueryException $e) {
            echo ($e);
            return $this->sendError('User Book does not exist.');
        }
        $question = Question::find($newQuestion->id);
        return $this->sendResponse($question, "Question created");
    }


    public function show($id)
    {
        $question = Question::where('id', $id)->with('user_book.book')->first();

        if (is_null($question)) {

            return $this->sendError('Question does not exist');
        }
        return $this->sendResponse($question, "Question");
    }


    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'quotes' => 'required',
            'quotes.*.text' => 'required',
            "starting_page" => 'required',
            "ending_page" => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $input = $request->all();
        $quotationInput = $input['quotes'];
        $qoutes = [];

        try {


            $question = Question::find($id);
            if (Auth::id() == $question->user_book->user_id) {
                Quotation::where('question_id', $question->id)->delete();
                foreach ($quotationInput as $value) {
                    $qoute = Quotation::create($value);
                    array_push($qoutes, $qoute);
                }
                $question->question = $request->question;
                $question->starting_page = $request->starting_page;
                $question->ending_page = $request->ending_page;
                $question->quotation()->saveMany($qoutes);
                $question->save();
            }
        } catch (\Error $e) {

            return $this->sendError($e);
        }
        return $this->sendResponse($question, 'Question updated Successfully!');
    }

    public function destroy($id)
    {

        Quotation::where('question_id', $id)->delete();
        $result = Question::destroy($id);


        if ($result == 0) {

            return $this->sendError('Question does not exist');
        }
        return $this->sendResponse($result, 'Question deleted Successfully');
    }

    public function addDegree(Request $request,  $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'reviews' => 'required',
            'degree' => 'required',
            'auditor_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }


        $question = Question::find($id);

        $question->reviews = $request->reviews;
        $question->degree = $request->degree;
        $question->auditor_id = $request->auditor_id;
        $question->status = 'audited';

        try {
            $question->save();
            // Stage Up
            $auditedTheses = Thesis::where('user_book_id', $question->user_book_id)->where('status', 'audited')->count();
            $auditedGeneralInfo = GeneralInformations::where('user_book_id', $question->user_book_id)->where('status', 'audited')->count();
            $auditedQuestions = Question::where('user_book_id', $question->user_book_id)->where('status', 'audited')->count();
            if ($auditedTheses >= 8 && $auditedQuestions >= 5 && $auditedGeneralInfo) {
                $userBook = UserBook::where('id', $question->user_book_id)->update(['status' => 'audited']);
            }
        } catch (\Error $e) {
            return $this->sendError('Questions does not exist');
        }
        return $this->sendResponse($question, 'Degree added Successfully!');
    }
    //ready to review
    public function reviewQuestion($id)
    {
        try {
            $question = Question::where('user_book_id', $id)->where(function ($query) {
                $query->where('status', 'retard')
                    ->orWhereNull('status');
            })->update(['status' => 'ready']);
        } catch (\Error $e) {
            return $this->sendError('Question does not exist');
        }
    }

    public function review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required_without:user_book_id',
            'user_book_id' => 'required_without:id',
            'status' => 'required',
            'reviewer_id' => 'required',
            'reviews' => 'required_if:status,rejected'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        try {
            if ($request->has('id')) {

                $question = Question::find($request->id);
                $question->status = $request->status;
                $question->reviewer_id = $request->reviewer_id;
                if ($request->has('reviews')) {
                    $question->reviews = $request->reviews;
                    $userBook = UserBook::find($question->user_book_id);
                    $user = User::find($userBook->user_id);
                    $userBook->status=$request->status;
                    $userBook->reviews=$request->reviews;
                    $userBook->save();
                    $user->notify(
                        (new \App\Notifications\RejectAchievement())->delay(now()->addMinutes(2))
                    );
                }

                $question->save();
            } else if ($request->has('user_book_id')) {
                $questions = Question::where('user_book_id', $request->user_book_id)->update(['status' => $request->status]);
            }
        } catch (\Error $e) {
            return $this->sendError('Question does not exist');
        }
    }



    public function finalDegree($user_book_id)
    {
        $degrees = Question::where("user_book_id", $user_book_id)->avg('degree');
        return $this->sendResponse($degrees, 'Final Degree!');
    }

    public function getUserBookQuestions($id)
    {
        $questions = Question::where('user_book_id', $id)->get();
        return $this->sendResponse($questions, 'Questions');
    }
    public function getByStatus($status)
    {
        $questions =  Question::with("user_book.user")->with("user_book.book")->with("user_book.questions")->where('status', $status)->groupBy('user_book_id')->get();
        return $this->sendResponse($questions, 'Questions');
    }

    public function getByUserBook($user_book_id)
    {
        $response['questions'] =  Question::with("user_book.user")->with("user_book.book")->with('reviewer')->with('auditor')->where('user_book_id', $user_book_id)->get();
        $response['acceptedQuestions'] =  Question::where('user_book_id', $user_book_id)->where('status', 'accept')->count();
        $response['userBook'] =  UserBook::find($user_book_id);
        return $this->sendResponse($response, 'Questions');
    }
    public function getByBook($book_id)
    {
        $questions['user_book'] = UserBook::where('user_id', Auth::id())->where('book_id', $book_id)->first();
        $questions['questions'] =  Question::with('reviewer')->with('auditor')->where('user_book_id', $questions['user_book']->id)->get();
        return $this->sendResponse($questions, 'Questions');
    }

    public static function questionsStatistics()
    {
        $thesisCount = Question::count();
        $very_excellent =  Question::where('degree', '>=', 95)->where('degree', '<', 100)->count();
        $excellent = Question::where('degree', '>', 94.9)->where('degree', '<', 95)->count();
        $veryGood =  Question::where('degree', '>', 89.9)->where('degree', '<', 85)->count();
        $good = Question::where('degree', '>', 84.9)->where('degree', '<', 80)->count();
        $accebtable = Question::where('degree', '>', 79.9)->where('degree', '<', 70)->count();
        $rejected = Question::where('status', 'rejected')->count();
        return [
            "total" => $thesisCount,
            "very_excellent" => ($very_excellent / $thesisCount) * 100,
            "excellent" => ($excellent / $thesisCount) * 100,
            "veryGood" => ($veryGood / $thesisCount) * 100,
            "good" => ($good / $thesisCount) * 100,
            "accebtable" => ($accebtable / $thesisCount) * 100,
            "rejected" => ($rejected / $thesisCount) * 100,
        ];
    }

    public static function questionsStatisticsForUser($id)
    {
        $questionsCount = UserBook::join('questions', 'user_book.id', '=', 'questions.user_book_id')->where('user_id', $id)->count();
        $very_excellent =  UserBook::join('questions', 'user_book.id', '=', 'questions.user_book_id')->where('degree', '>=', 95)->where('degree', '<=', 100)->count();
        $excellent = UserBook::join('questions', 'user_book.id', '=', 'questions.user_book_id')->where('degree', '>', 94.9)->where('degree', '<', 95)->count();
        $veryGood = UserBook::join('questions', 'user_book.id', '=', 'questions.user_book_id')->where('degree', '>', 89.9)->where('degree', '<', 85)->count();
        $good = UserBook::join('questions', 'user_book.id', '=', 'questions.user_book_id')->where('degree', '>', 84.9)->where('degree', '<', 80)->count();
        $accebtable = UserBook::join('questions', 'user_book.id', '=', 'questions.user_book_id')->where('degree', '>', 79.9)->where('degree', '<', 70)->count();
        return [
            "total" => $questionsCount,
            "very_excellent" => ($very_excellent / $questionsCount) * 100,
            "excellent" => ($excellent / $questionsCount) * 100,
            "veryGood" => ($veryGood / $questionsCount) * 100,
            "good" => ($good / $questionsCount) * 100,
            "accebtable" => ($accebtable / $questionsCount) * 100,
        ];
    }
}
