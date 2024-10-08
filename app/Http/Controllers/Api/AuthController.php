<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request) {
        
        // 1. validate the request
        $request->validated($request->all());

        // 2. check if the user exists
        if(!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        // 3. if valid create user from data.
        $user = User::firstWhere('email', $request->email);

        // 4. return a token for the user
        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'auth_token for ' . $user->email,
                    Abilities::getAbillities($user),
                    now()->addMonth()
                )->plainTextToken
            ]
        );
    }

    public function register() {
        return $this->ok('Register successful');
    }

    public function logout(Request $request) {
        // removes the current users session token
        $request->user()->currentAccessToken()->delete();
        return $this->ok('Logged out successfully');
    }
}
