<?php

namespace App\Services;


class AppService {

    protected $sortableFields = [];

    /**
     * This function is responsible to manipulate sorting of the list APIs
     * @param mixed $request
     * 
     * @return [type]
     */
    public function sortByField($request) {
        $sortBy             = trim($request->get('sort_by'));
        $sortOrder          = trim($request->get('sort_order'));
        return [
            'sort_by'       => (!empty($sortBy) && in_array($sortBy, $this->sortableFields)) ? $sortBy : $this->sortableFields[0],
            'sort_order'    => ($sortOrder == 'desc') ? 'desc' : 'asc',
        ];
    }

    /**
	 * Generate unique numeric OTP
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @param mixed $field=''
	 * @param mixed $length=6
	 * 
	 * @return integer return Unique numeric OPT
	 */
	public function generateNumericOTP(\Illuminate\Database\Eloquent\Model $model, $field, $length=6) { 
		$otp = substr(number_format(time() * rand(),0,'',''),0,$length);
		if ($model->where([$field => $otp])->count()) {
			return $this->generateNumericOTP($model, $field, $length);
		}
		return $otp; 
	}

    /**
     * Send email notification for user related events
     * @param mixed $user
     * @param mixed $emailMetaData=[]
     * 
     * @return void
     */
    public function sendEmail($user, $emailMetaData=[]) {
        if(config('constants.enable_mail')) {
            Mail::queue(new SendEmail([
                'to_email'      => $user->email,
                'meta_data'     => $emailMetaData,
                'data'          => $user->toArray(),
            ]));
        }
    }
}