<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Users;
use Illuminate\Http\Request;


class UsersController extends BaseController
{
    //
    public function read()
    {
        try {
            $user = Users::orderBy("created_at","desc")->limit(100)->get();
            return response()->json(["result" => $user], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    public function create(Request $request)
    {
        try {
            $user = new Users;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            return response()->json(["result" => "created"], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    public function delete($id){
        try {
            $user = Users::find($id);
            if($user == null){
                return response()->json(["result" => "user not found"], 201);
            }
            $user->delete();
            return response()->json(["result" => "deleted"], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    public function update($id,Request $request){
        try {
            $user = Users::find($id);
            if($user == null){
                return response()->json(["result" => "user not found"], 201);
            }
            $user->name = $request->name != null ? $request->name : $user->name;
            $user->email = $request->email != null ? $request->email : $user->email;
            $user->save();
            return response()->json(["result" => "updated"], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }
}
