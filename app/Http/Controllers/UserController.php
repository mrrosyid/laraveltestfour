<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Early;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function register(UserRegisterRequest $request){
        $data = $request->validated();

        if (Early::where('email', $data['email'])->count() == 0){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ], 401));
        }

        if (User::where('email', $data['email'])->count() == 1){
            throw new HttpResponseException(response([
                "errors" => [
                    "email" => [
                        "email sudah terdaftar"
                    ]
                ]
                    ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource{
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
                    ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();
        return new UserResource($user);
    }

    public function get()
    {
        $user = User::all();
        // return new UserResource($user);
        // return response()->json($user);
        
        return UserResource::collection($user)->collection;

    }

    public function update(int $id, UserUpdateRequest $request){
        
        $data = $request->validated();
        $user = User::where('id', $id)->first();

        if (isset($data['role'])){
            $user->role = $data['role'];
        }

        if (isset($data['password'])){
            $user->passwoord = Hash::make($data['password']);
        }
         /** @var \App\Models\User $user **/
        $user->save();
        return new UserResource($user);
    }

    public function logout(Request $request): JsonResponse{
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }

    public function delete(int $id): JsonResponse{

        $userDelete = User::where('id', $id)->first();
        if(!$userDelete){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])-> setStatusCode(404));

        }
        $userDelete->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
