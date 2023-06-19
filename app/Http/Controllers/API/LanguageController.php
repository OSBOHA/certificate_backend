<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends BaseController
{
    public function index()
    { 

        $Language = Language::all();
        return $this->sendResponse($Language, "Language");
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
            $Language = Language::create($input);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendError('Language does not exist');
        }

        return $this->sendResponse($Language, "Language created");
    }


    public function show($id)
    {
        $Language = Language::find($id);

        if (is_null($Language)) {

            return $this->sendError('Language does not exist');
        }
        return $this->sendResponse($Language, "Language");
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


        $Language = Language::find($id);

        $updateParam = [
            "name" => $input['name'],

        ];

            $Language->update($updateParam);

        return $this->sendResponse($Language, 'Language updated Successfully!');
    }

    public function destroy($id)
    {

        $Language = Language::destroy($id);

        if ($Language == 0) {

            return $this->sendError('Language does not exist');
        }
        return $this->sendResponse($Language, 'Language deleted Successfully');
    }



}
