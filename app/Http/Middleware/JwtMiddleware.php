<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = $request->header('Authorization');
        $token = explode(" ",$auth);
        if($auth == null) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        $token = $token[1];

        try {
            $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }
        $user = Users::find($credentials->uid);
        $request->auth = $user;
        return $next($request);
    }
}