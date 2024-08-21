<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    // creates an instance of LoginUserRequest and validates the request
    public function login(LoginUserRequest $request) {
        
        // this checks to make sure the incoming request adheres to the defined validation rules
        $request->validated($request->all());

        // If the email and password are invalid, send an error message
        if(!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        // if valid create user from data.
        $user = User::firstWhere('email', $request->email);

        // return a token for the user
        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'auth_token for ' . $user->email,
                    ['*'],
                    now()->addMonth()
                )->plainTextToken
            ]
        );
        // return $this->ok($request->get('email'));
        // return $this->ok('Login successful');
        // return response()->json(['message' => 'Hello API!', 'status' => 200], 200);
    }

    public function register() {
        return $this->ok('Register successful');
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('Logged out successfully');
    }
}
