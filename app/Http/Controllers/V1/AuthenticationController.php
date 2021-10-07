<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\{UserRequest, SendVerificationEmailRequest};
use App\Http\Requests\Auth\{VerifyEmailRequest, ResetPasswordOtpRequest, ResetPasswordRequest, LoginRequest};
use App\Services\{UserService, AuthService};
use App\Models\User;

class AuthenticationController extends ApiController
{
    
    /**
     * This function is used to register a user to the system
     * @param Request $request
     * 
     * @return json
     */
    public function register(UserRequest $request)
    {
        return DB::transaction(function() use ($request) {
            $userService = new UserService();
            $user = $userService->saveDetails(null, $request->validated(), null);
            (new AuthService())->sendOtp($user, config('email_constants.account_verification'), config('lookups.otp_type.email_verification.slug'));
            return $this->success($user, __('messages.user_register'));
        });
    }

    
    /**
     * This function is used to login an existing user
     * @param Request $request
     * 
     * @return json
     */
    public function login(LoginRequest $request)
    {
        // Authenticate user credentials
        $request->authenticate();
        
        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user' => Auth::user(),
        ]);
    }

    /**
     * This function is used to logout a logged in user
     * @return json
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => __('messages.user_logout')
        ];
    }
    

    /**
     * Resend email for verification OTP
     * @param SendVerificationEmailRequest $request
     * 
     * @return json
     */
    public function sendVerificationEmail(SendVerificationEmailRequest $request) {
        return DB::transaction(function() use ($request) {
            $requestData = $request->validated();
            $user = (new User())->where(["email" => $requestData['email']])->first();
            (new AuthService())->sendOtp($user, config('email_constants.account_verification'), config('lookups.otp_type.email_verification.slug'));
            return $this->success($requestData, __('messages.verification_email'));
        });
    }

    /**
     * Verify user email by OTP
     * @param VerifyEmailRequest $request
     * 
     * @return json
     */
    public function verifyEmail(VerifyEmailRequest $request) {
        return DB::transaction(function() use ($request) {
            $requestData = $request->validated();
            $authService = new AuthService();
            $row = $authService->isValidOTP($requestData, config('constants.verification_otp_expiry'), config('lookups.otp_type.email_verification.slug'));
            $user  = User::find($row->user_id);
            (new UserService())->saveDetails($user, [
                'email_verified_at' => time(),
            ]);
            $authService->deleteOTP($row->id);

            return $this->success([], __('messages.email_verified'));
        });
    }

    public function resetPasswordOtp(ResetPasswordOtpRequest $request) {
        return DB::transaction(function() use ($request) {
            $user = (new User())->where(["email" => $request->get('email')])->first();
            (new AuthService())->sendOtp($user, config('email_constants.reset_password_otp'), config('lookups.otp_type.reset_password.slug'));
            return $this->success([], __('messages.password_reset_otp'));
        });
    }

    public function resetPassword(ResetPasswordRequest $request) {
        return DB::transaction(function() use ($request) {
            $requestData = $request->validated();
            $authService = new AuthService();
            $row = $authService->isValidOTP($requestData, config('constants.password_otp_expiry'), config('lookups.otp_type.reset_password.slug'));
            $user  = User::find($row->user_id);
            (new UserService())->saveDetails($user, [
                'password' => $requestData['password'],
            ]);
            $authService->deleteOTP($row->id);

            return $this->success([], __('messages.password_reset'));
        });
    }
}
