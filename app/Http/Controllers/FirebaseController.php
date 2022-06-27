<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\FirebaseUser;
use Illuminate\Http\Request;
use Firebase\FirebaseLib;
use Illuminate\Support\Facades\Hash;

class FirebaseController extends BaseController
{
    //

    private function setConnection()
    {
        # code...
        $firebase = new FirebaseLib(env('FIREBASE_URL'), env('FIREBASE_TOKEN'));
        return $firebase;
    }

    public function read()
    {
        $con = $this->setConnection();
        if(!$con){
            return response()->json(["result" => "cannot connect to firebase"], 404);
        }
        try {
            $path = env('FIREBASE_PATH');
            $user = $con->get($path);
            return response()->json(["result" => json_decode($user)], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    public function create(Request $request)
    {
        $con = $this->setConnection();
        if(!$con){
            return response()->json(["result" => "cannot connect to firebase"], 404);
        }
        try {
            $id = uniqid();
            $user = new FirebaseUser;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $con->set(env('FIREBASE_PATH') . '/' .$id. '/', $user);
            return response()->json(["result" => "created"], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    public function delete($id){
        $con = $this->setConnection();
        if(!$con){
            return response()->json(["result" => "cannot connect to firebase"], 404);
        }
        try {
            $path = env('FIREBASE_PATH') . '/' . $id;
            $con->delete($path);
            return response()->json(["result" => "deleted"], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    public function update($id,Request $request){
        $con = $this->setConnection();
        if(!$con){
            return response()->json(["result" => "cannot connect to firebase"], 404);
        }
        try {
            $path = env('FIREBASE_PATH') . '/' . $id;
            $user = $con->get($path);
            if($user == "null"){
                return response()->json(["result" => "user not found"], 201);
            }
            $decode = json_decode($user);
            $user = new FirebaseUser;
            $user->name = $request->name != null ? $request->name : $decode->name;
            $user->email = $request->email != null ? $request->email : $decode->email;
            $user->password = $request->password != null ? Hash::make($request->password) : $decode->password;
            $con->update($path, $user);
            return response()->json(["result" => "updated"], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }
}
