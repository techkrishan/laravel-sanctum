<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\{UserRequest, SendVerificationEmailRequest};
use App\Http\Requests\Auth\{VerifyEmailRequest, ResetPasswordOtpRequest, ResetPasswordRequest};
use App\Services\{UserService, AuthService};
use App\Models\User;
use Auth;

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
            $userService->sendOtp($user, config('email_constants.account_verification'), config('lookups.otp_type.email_verification.slug'));
            return $this->success($user, __('messages.user_register'));
        });
    }

    
    /**
     * This function is used to login an existing user
     * @param Request $request
     * 
     * @return json
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
            $authService->saveDetails($user, [
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
            $authService->saveDetails($user, [
                'password' => $requestData['password'],
            ]);
            $authService->deleteOTP($row->id);

            return $this->success([], __('messages.password_reset'));
        });
    }
}
