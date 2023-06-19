<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Models\Type;
use Illuminate\Http\Request;

class BookTypeController extends BaseController
{
    public function index()
    { 

        $Type = Type::all();
        return $this->sendResponse($Type, "Type");
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
            $Type = Type::create($input);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendError('Type does not exist');
        }

        return $this->sendResponse($Type, "Type created");
    }


    public function show($id)
    {
        $Type = Type::find($id);

        if (is_null($Type)) {

            return $this->sendError('Type does not exist');
        }
        return $this->sendResponse($Type, "Type");
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


        $Type = Type::find($id);

        $updateParam = [
            "name" => $input['name'],

        ];

            $Type->update($updateParam);

        return $this->sendResponse($Type, 'Type updated Successfully!');
    }

    public function destroy($id)
    {

        $Type = Type::destroy($id);

        if ($Type == 0) {

            return $this->sendError('Type does not exist');
        }
        return $this->sendResponse($Type, 'Type deleted Successfully');
    }



}
