<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if(User::where('username', $data['username'])->count() == 1)
        {
            throw new HttpResponseException(response([
                'errors' => [
                    "username" => "username has already been taken"
                ]
            ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);    
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated();

        $user = User::where('username', $data['username'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            throw new HttpResponseException(response([
                'errors' => [
                    "message" => "username or password is wrong"
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function get(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);

    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = Auth::user();

        if(isset($data['username'])){
            $isExist = User::where('username', $data['username'])
                    ->where('id', '!=', $user->id)->exists();

            if($isExist){
                throw new HttpResponseException(response([
                    "errors" => [
                        "message" => "username has been taken already"
                    ]
                ], 422));
            }

            $user->username = $data['username'];

        }

        if(isset($data['name'])) $user->name = $data['name'];
        if(isset($data['password'])) $user->password = Hash::make($data['password']);

        $user->save();

        return new UserResource($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->token = null;

        $user->save();
        return response()->json([
            "data" => true
        ], 200);
    }
}
