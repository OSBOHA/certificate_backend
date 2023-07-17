<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\GeneralInformations;
use App\Models\Question;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\UserBook;



class GeneralInformationsController extends BaseController
{
    public function index()
    {

        $general_informations = GeneralInformations::all();
        return $this->sendResponse($general_informations, "General Informations");
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'general_question' => 'required',
            'summary' => 'required',
            'user_book_id' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $input = $request->all();

        try {
            $general_informations = GeneralInformations::create($input);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendError('User Book does not exist');
        }

        return $this->sendResponse($general_informations, "General Informations created");
    }


    public function show($id)
    {
        $general_informations = GeneralInformations::where('id',$id)->with('user_book.book')->first();

        if (is_null($general_informations)) {

            return $this->sendError('General Informations does not exist');
        }
        return $this->sendResponse($general_informations, "General Informations");
    }


    public function update(Request $request,  $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'general_question' => 'required',
            'summary' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        try {
            $general_informations = GeneralInformations::find($id);
            if (Auth::id() == $general_informations->user_book->user_id) {

                $general_informations->update($request->all());
            }
            else{
                $general_informations->reviews = $request->reviews;
                $general_informations->degree=$request->degree;
                $general_informations->auditor_id = $request->auditor_id;
                $general_informations->status='audited';
                $general_informations->save();

            }

        } catch (\Error $e) {
            return $this->sendError('General Informations does not exist');
        }
        return $this->sendResponse($general_informations, 'General Informations updated Successfully!');
    }

    public function destroy($id)
    {

        $result = GeneralInformations::destroy($id);

        if ($result == 0) {

            return $this->sendError('General Informations does not exist');
        }
        return $this->sendResponse($result, 'General Informations deleted Successfully!');
    }

        //ready to review
        public function reviewGeneralInformations($id)
        {
            try {
                $generalInformations = GeneralInformations::where('user_book_id', $id)->update(['status' => 'ready']);
            } catch (\Error $e) {
                return $this->sendError('General Informations does not exist');
            }
        }


    public function review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
            'reviewer_id' => 'required',
            'reviews' => 'required_if:status,rejected'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        try {
            $info = GeneralInformations::find($request->id);
            $info->status = $request->status;
            $info->reviewer_id = $request->reviewer_id;
            if ($request->has('reviews')) {
                //REJECT OR RETARD
                $info->reviews = $request->reviews;
                $userBook=UserBook::find($info->user_book_id);
                $user=User::find($userBook->user_id);
$userBook->status=$request->status;
                $userBook->reviews=$request->reviews;
                $userBook->save();
                if( $request->status == 'rejected'){
                    $theses=Thesis::where('user_book_id',$info->user_book_id)->update(['status'=>$request->status ,'reviews'=>$request->reviews ]);
                    $questions=Question::where('user_book_id',$info->user_book_id)->update(['status'=>$request->status ,'reviews'=>$request->reviews ]);
        
                }
                $user->notify(
                    (new \App\Notifications\RejectAchievement())->delay(now()->addMinutes(2))
                );
            }

            $info->save();
        } catch (\Error $e) {
            return $this->sendError('Question does not exist');
        }
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


        $general_informations = GeneralInformations::find($id);
        $general_informations->reviews = $request->reviews;

        $general_informations->degree = $request->degree;
        $general_informations->auditor_id = $request->auditor_id;
        $general_informations->status = 'audited';
    try {
            $general_informations->save();

            // Stage Up
            $auditedTheses=Thesis::where('user_book_id', $general_informations->user_book_id)->where('status', 'audited')->count();
            $auditedQuestions=Question::where('user_book_id', $general_informations->user_book_id)->where('status', 'audited')->count();
            if($auditedTheses >= 8 && $auditedQuestions >= 5 ){
                $userBook=UserBook::where('id', $general_informations->user_book_id)->update(['status' => 'audited']);
            }


        } catch (\Error $e) {
            return $this->sendError('General Informations does not exist');
        }
        return $this->sendResponse($general_informations, 'Degree added Successfully!');
    }



    public function finalDegree($user_book_id){

        $degrees = GeneralInformations::where("user_book_id",$user_book_id)->avg('degree');
        return $this->sendResponse($degrees, 'Final Degree!');
    }

    // DUPLICATED

    public function getByUserBookId($user_book_id){
         $general_informations = GeneralInformations::where('user_book_id',$user_book_id)->first();
        return $this->sendResponse($general_informations, 'General Informations!');
    }
    public function getByStatus($status){
        $general_informations =  GeneralInformations::with("user_book.user")->with("user_book.book")->where('status',$status)->groupBy('user_book_id')->get();
        return $this->sendResponse($general_informations, 'Questions');

    }


    public function getByUserBook($user_book_id){
        $general_informations =  GeneralInformations::with("user_book.user")->with("user_book.book")->with('reviewer')->with('auditor')->where('user_book_id',$user_book_id)->first();
        return $this->sendResponse($general_informations, 'General Informations');
    }
    public function getByBook($book_id)
    {
        $general_informations['user_book']= UserBook::where('user_id', Auth::id())->where('book_id', $book_id)->first();
        $general_informations['general_informations'] =  GeneralInformations::with('reviewer')->with('auditor')->where('user_book_id', $general_informations['user_book']->id)->first();
        return $this->sendResponse($general_informations, 'general_informations');
    }



    public static function generalInformationsStatistics(){
        $generalInformationsCount = GeneralInformations::count();
        $very_excellent =  GeneralInformations::where('degree' ,'>=',95)->where('degree','<',100)->count();
        $excellent = GeneralInformations::where('degree' ,'>',94.9)->where('degree','<',95)->count();
        $veryGood =  GeneralInformations::where('degree' ,'>',89.9)->where('degree','<',85)->count();
        $good = GeneralInformations::where('degree' ,'>',84.9)->where('degree','<',80)->count();
        $accebtable = GeneralInformations::where('degree' ,'>',79.9)->where('degree','<',70)->count();
        $rejected = GeneralInformations::where('status','rejected')->count();
        return [
            "total" => $generalInformationsCount,
            "very_excellent" =>( $very_excellent / $generalInformationsCount) * 100,
            "excellent" =>( $excellent / $generalInformationsCount) * 100,
            "very_good" =>( $veryGood / $generalInformationsCount) * 100,
            "good" =>( $good / $generalInformationsCount) * 100,
            "accebtable" =>( $accebtable / $generalInformationsCount) * 100,
            "rejected" =>( $rejected / $generalInformationsCount) * 100,
        ];
    }

    public static function generalInformationsStatisticsForUser($id){
        $generalInformationsCount = UserBook::join('general_informations', 'user_book.id', '=', 'general_informations.user_book_id')->where('user_id',$id)->count();
        $very_excellent =  UserBook::join('general_informations', 'user_book.id', '=', 'general_informations.user_book_id')->where('degree' ,'>=',95)->where('degree','<=',100)->count();
        $excellent = UserBook::join('general_informations', 'user_book.id', '=', 'general_informations.user_book_id')->where('degree' ,'>',94.9)->where('degree','<',95)->count();
        $veryGood =  UserBook::join('general_informations', 'user_book.id', '=', 'general_informations.user_book_id')->where('degree' ,'>',89.9)->where('degree','<',85)->count();
        $good = UserBook::join('general_informations', 'user_book.id', '=', 'general_informations.user_book_id')->where('degree' ,'>',84.9)->where('degree','<',80)->count();
        $accebtable = UserBook::join('general_informations', 'user_book.id', '=', 'general_informations.user_book_id')->where('degree' ,'>',79.9)->where('degree','<',70)->count();
        return [
            "total" => $generalInformationsCount,
            "very_excellent" =>( $very_excellent / $generalInformationsCount) * 100,
            "excellent" =>( $excellent / $generalInformationsCount) * 100,
            "very_good" =>( $veryGood / $generalInformationsCount) * 100,
            "good" =>( $good / $generalInformationsCount) * 100,
            "accebtable" =>( $accebtable / $generalInformationsCount) * 100
        ];
    }
}
