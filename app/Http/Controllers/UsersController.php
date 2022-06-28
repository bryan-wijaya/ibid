<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ixudra\Curl\Facades\Curl;

class UsersController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"users"},
     *     summary="get all user from mongo db",
     *     operationId="test api",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function read()
    {
        try {
            $user = Users::orderBy("created_at","desc")->limit(100)->get();
            return response()->json(["result" => $user], 201);
        } catch (\Throwable $th) {
            return response()->json(["result" => "error"], 404);
        }
    }

    /**
     * @OA\POST(
     *     path="/users/create",
     *     tags={"users"},
     *     summary="add new user to mongo db",
     *     operationId="test api",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     @OA\Parameter(
     *          parameter="create",
     *          in="header",
     *          name="name",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          parameter="create",
     *          in="header",
     *          name="email",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          parameter="create",
     *          in="header",
     *          name="password",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      )
     * )
     */
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

     /**
     * @OA\DELETE(
     *     path="/users/delete/{id}",
     *     tags={"users"},
     *     summary="delete user from mongo db",
     *     operationId="test api",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     @OA\Parameter(
     *          parameter="delete",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      )
     * )
     */
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

    /**
     * @OA\PUT(
     *     path="/users/update/{id}",
     *     tags={"users"},
     *     summary="update existing user from mongo db",
     *     operationId="test api",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     @OA\Parameter(
     *          parameter="create",
     *          in="header",
     *          name="name",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          parameter="create",
     *          in="header",
     *          name="email",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          parameter="create",
     *          in="header",
     *          name="password",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          parameter="delete",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/documentation",
     *     tags={"test"},
     *     summary="test api",
     *     operationId="test api",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function documentation(){
        return response()->json(["result" => "success" ], 201);
    }

    public function filter(){
        $response = Curl::to('https://gist.githubusercontent.com/Loetfi/fe38a350deeebeb6a92526f6762bd719/raw/9899cf13cc58adac0a65de91642f87c63979960d/filter-data.json')
        ->get();
        $decode = json_decode($response);
        if($decode == null){
            return response()->json(["result" => "failed"], 404);
        }
        $bills = $decode->data->response->billdetails;
        $ret = [];
        foreach($bills as $bill){
            $denom = $bill->body[0];
            $exp = explode(":",$denom);
            $intDenom = intval($exp[1]);
            if($intDenom >= 100000){
                array_push($ret,$intDenom);
            }
        }
        return $ret;
    }
}
