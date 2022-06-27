<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;


class JwtController extends BaseController
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
            $user->password = Hash::make($request->password);
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
            $user->password = $request->password != null ? Hash::make($request->password) : $user->password;
            $user->save();
            return response()->json(["result" => "updated"], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    public function login(Request $request){
        $validated = $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = Users::where('email',$validated['email'])->first();
        if (!Hash::check($validated['password'], $user->password)){
            return response()->json(["result" => "email or password invalid"], 404);
        }

        $payload = [
            'iat' => intval(microtime(true)),
            'exp' => intval(microtime(true)) + (3600 * 1000),
            'uid' => $user->id
        ];                                                                                  
        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        return response()->json(["result" => $token], 201);
    }
}
