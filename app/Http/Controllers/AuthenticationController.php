<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\NotificationToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Authentication\StoreUserRequest;
use App\Http\Requests\Authentication\LoginAuthenticationRequest;

class AuthenticationController extends Controller
{


    public function register(StoreUserRequest $request)
    {
        try {
            $payload = $request->validated();
            $payload['password'] = Hash::make($payload['password']);
            $payload['password_confirmation'] = Hash::make($payload['password_confirmation']);
            $user = User::create($payload);
            $token = $user->createToken('authToken')->plainTextToken;
            $user = User::where('email', $request->email)->firstOrFail();

            return $this->successResponse(['token' => $token, 'message' => 'User created!', 'data' => $user], 'User succesfully created', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Internal server error', 500);
        }
    }




    public function login(LoginAuthenticationRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!Auth::attempt($credentials)) {
                return $this->errorResponse(new Exception('Invalid credentials'), 'Unatuthorized', 401);
            }
            $user = User::where('email', $request->email)->firstOrFail();
            $request->user()->tokens()->delete();
            $token = $request->user()->createToken('authToken')->plainTextToken;
            return $this->successResponse(['token' => $token, 'user' => $user], 'Login success!', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Internal server error', 500);
        }
    }



    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['code' => 200, 'message' => 'Successfull logout'], 200);
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Internal server error', 500);
        }
    }
}
