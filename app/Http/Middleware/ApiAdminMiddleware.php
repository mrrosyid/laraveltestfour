<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        $authenticate = true;

        if (!$token){
            $authenticate = false;
        }

        $token = str_replace('Bearer ', '', $token);

        $user = User::where('token', $token)->first();
        if(!$user){
            $authenticate = false;
        } else {
            Auth::login($user);
        }

        if($user->role != 'admin'){
            $authenticate = false;
        } 

        if($authenticate){
            // dd($token, $user);
            return $next($request);
        } else {
            // dd($token, $user);
            return response()->json([
                "errors" => [
                    "message" => [
                        "unauthorized admin"
                    ]
                ]
            ])->setStatusCode(401);
        }
    }
}
