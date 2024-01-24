<?php

namespace App\Http\Controllers;

use App\Http\Requests\EarlyCreateRequest;
use App\Http\Requests\EarlyUpdateRequest;
use App\Http\Resources\EarlyResource;
use App\Models\Early;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class EarlyController extends Controller
{
    public function register(EarlyCreateRequest $request){
        $data = $request->validated();

        if (Early::where('email', $data['email'])->count() == 1){
            throw new HttpResponseException(response([
                "errors" => [
                    "email" => [
                        "email sudah terdaftar"
                    ]
                ]
            ], 400));
        }

        $user = new Early($data);
        $user->save();

        return (new EarlyResource($user))->response()->setStatusCode(201);
    }

    public function get(){
        $early = Early::all();
        return EarlyResource::collection($early)->collection;
    }

    public function update(int $id, EarlyUpdateRequest $request){
        
        $data = $request->validated();
        $early = Early::where('id', $id)->first();

        if (isset($data['status'])){
            $early->status = $data['status'];
        }

        $early->save();
        return new EarlyResource($early);
    }
    
    public function delete(int $id){

        $earlyDelete = Early::where('id', $id)->first();
        if(!$earlyDelete){
            throw new HttpResponseException(response([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])-> setStatusCode(404));

        }
        $earlyDelete->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
