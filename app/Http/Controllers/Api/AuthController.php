<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Users\UserPublicResource;

class AuthController extends Controller
{
    use ApiResponder;
    //
    public function register(RegisterRequest $request){
        try{
            $data = $request->validated();
            $new_user = User::create($data);
            return $this->respondWithSuccess('created User Success');
        }catch(Exception $e){
            return $this->setStatusCode(422)->respondWithError($e->getMessage());


        }

    }


    public function login(LoginRequest $request){
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('post')->plainTextToken;

            return $this->respondResource(new UserPublicResource($user),[
                'token'=>$token,
                'message'=>'Login Success !'
            ]);
        } else {
            return $this->setStatusCode(422)->respondWithError('Invalid credentials');
        }

    }
}
