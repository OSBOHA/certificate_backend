<?php

namespace App\Http\Controllers\API;

use App\Models\BookLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookLevelController extends BaseController
{
    public function index()
    {

        $level = BookLevel::all();
        return $this->sendResponse($level, "level");
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
            $level = BookLevel::create($input);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendError('level does not exist');
        }

        return $this->sendResponse($level, "level created");
    }


    public function show($id)
    {
        $level = BookLevel::find($id);

        if (is_null($level)) {

            return $this->sendError('level does not exist');
        }
        return $this->sendResponse($level, "level");
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



        $level = BookLevel::find($id);

        $updateParam = [
            "name" => $input['name'],

        ];

            $level->update($updateParam);

        return $this->sendResponse($level, 'level updated Successfully!');
    }

    public function destroy($id)
    {

        $level = BookLevel::destroy($id);

        if ($level == 0) {

            return $this->sendError('level does not exist');
        }
        return $this->sendResponse($level, 'level deleted Successfully');
    }

}
