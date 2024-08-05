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
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(StoreUserRequest $request)
    {

        $payload = $request->validated();
        $payload['password'] = Hash::make($payload['password']);
        $payload['password_confirmation'] = Hash::make($payload['password_confirmation']);
        $user = User::create($payload);
        $token = $user->createToken('authToken')->plainTextToken;
        $user = User::where('email', $request->email)->firstOrFail();
        return response()->json(['token' => $token, 'message' => 'User created!', 'data' => $user], 201);
    }


    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(LoginAuthenticationRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!Auth::attempt($credentials)) {
                return $this->errorResponse(Auth::attempt($credentials), 'Unatuthorized', ['password' => ['Invalid email or password please try again']]);
            }
            $user = User::where('email', $request->email)->firstOrFail();
            $request->user()->tokens()->delete();
            $token = $request->user()->createToken('authToken')->plainTextToken;
            return $this->successResponse(200, 'Login success!', ['token' => $token, 'user' => $user]);
        } catch (Exception $e) {
            return $this->errorResponse(500, 'Internal server error');
        }
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['code' => 200, 'message' => 'Successfull logout'], 200);
    }
}
