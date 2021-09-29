<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AuthenticationController extends ApiController
{
    
    /**
     * This function is used to register a user to the system
     * @param Request $request
     * 
     * @return [type]
     */
    public function register(Request $request)
    {
        $attr = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|max:30'
        ]);

        $user = User::create([
            'first_name' => $attr['first_name'],
            'last_name' => $attr['last_name'],
            'full_name' => $attr['first_name'] . ' ' . $attr['last_name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        return $this->success([
            'token' => $user->createToken('tokens')->plainTextToken,
            'user' => $user,
        ]);
    }

    
    /**
     * This function is used to login an existing user
     * @param Request $request
     * 
     * @return [type]
     */
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user' => Auth::user(),
        ]);
    }

    /**
     * This function is used to logout a logged in user
     * @return [type]
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
