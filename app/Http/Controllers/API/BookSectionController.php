<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Models\BookSection;
use Illuminate\Http\Request;

class BookSectionController extends BaseController
{
    public function index()
    {

        $Section = BookSection::all();
        return $this->sendResponse($Section, "Section");
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $input = $request->all();

        try {
            $Section = BookSection::create($input);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendError('Section does not exist');
        }

        return $this->sendResponse($Section, "Section created");
    }


    public function show($id)
    {
        $Section = BookSection::find($id);

        if (is_null($Section)) {

            return $this->sendError('Section does not exist');
        }
        return $this->sendResponse($Section, "Section");
    }


    public function update(Request $request,  $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }


        $Section = BookSection::find($id);

        $updateParam = [
            "name" => $input['name'],

        ];

            $Section->update($updateParam);

        return $this->sendResponse($Section, 'Section updated Successfully!');
    }

    public function destroy($id)
    {

        $Section = BookSection::destroy($id);

        if ($Section == 0) {

            return $this->sendError('Section does not exist');
        }
        return $this->sendResponse($Section, 'Section deleted Successfully');
    }



}
