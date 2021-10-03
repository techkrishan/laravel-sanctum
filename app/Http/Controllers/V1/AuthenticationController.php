<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
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
    public function register(UserRequest $request)
    {
        return DB::transaction(function() use ($request) {
            $userService = new UserService();
            $user = $userService->saveDetails(null, $request->validated(), null);
            $userService->sendEmail($user, config('email_constants.account_verification'));

            return $this->success($user, __('messages.user_register'));
        });
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
            return $this->error(__('messages.credentials_not_match'), 401);
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
            'message' => __('messages.user_logout')
        ];
    }
    
}
