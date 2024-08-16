<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request) {
        $request->validated($request->all());

        if(!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = User::firstWhere('email', $request->email);

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken('auth_token')->plainTextToken
            ]
        );
        // return $this->ok($request->get('email'));
        // return $this->ok('Login successful');
        // return response()->json(['message' => 'Hello API!', 'status' => 200], 200);
    }
    public function register() {
        return $this->ok('Register successful');
    }
}
