<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\{Lookup, User, UserOneTimePassword};


class AuthService extends AppService {

    /**
     * Send OTP on user email address
     * @param mixed $userId
     * 
     * @return void
     */
    public function sendOtp($user, $emailMetaData=[], $otpType='') {
        $userOneTimePassword = new \App\Models\UserOneTimePassword();
        $otpTypeId = Lookup::where(['slug' => $otpType])->value('id');
        $otp = $this->generateNumericOTP($userOneTimePassword, 'otp', config('constants.otp_length'));

        (new \App\Models\UserOneTimePassword())->updateOrCreate([
            'user_id' => $user->id,
            'type_id' => $otpTypeId
        ],['otp' => $otp]);
        $user->otp = $otp;
        $this->sendEmail($user, $emailMetaData);
    }

    /**
     * Validate user OTP
     * @param mixed $requestData=[]
     * @param mixed $otpExpiryTime=60 in minutes
     * @param mixed $otpType=''
     * 
     * @return array
     */
    public function isValidOTP($otp, $otpExpiryTime=60, $otpType='') {
        $otpObject      = new UserOneTimePassword();
        $userTable      = (new User())->getTable();
        $otpTable       = $otpObject->getTable();
        $lookupTable    = (new Lookup())->getTable();

        $row = $otpObject
            ->join($userTable, $otpTable.'.user_id', '=', $userTable.'.id')
            ->join($lookupTable, $otpTable.'.type_id', '=', $lookupTable.'.id')
            ->where($userTable.'.is_active', '=', config('constants.boolean_true'))
            ->where($userTable.'.is_deleted', '=', config('constants.boolean_false'))
            ->where($otpTable.'.otp', '=', $otp)
            ->where($lookupTable.'.slug', '=', $otpType)
            ->WhereRaw("date_add($otpTable.updated_at,INTERVAL $otpExpiryTime minute) > now()")
            ->first([$otpTable.'.*', DB::raw("date_add($otpTable.updated_at,INTERVAL 60 minute) as expiry_time")]);

        if(empty($row)) {
            throw ValidationException::withMessages([
                'otp' => __('validation.otp.invalid'),
            ]);
        }
        
        return $row;
    }

    /**
     * Delete otp after verification
     * @param mixed $id
     * 
     * @return void
     */
    public function deleteOTP($id) {
        UserOneTimePassword::destroy($id);
    }
}