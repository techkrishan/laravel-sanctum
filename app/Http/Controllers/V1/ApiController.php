<?php

namespace App\Http\Controllers\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * This function is used to return json response on success
     * @param mixed $data=[]
     * @param mixed $message=null
     * @param mixed $statusCode=200
     * 
     * @return [type]
     */
    public function success($data=[], $message=null, $statusCode=200) {
        return response([
            'success'   => true,
            'message'   => $message,
            'data'      => $data,
        ], $statusCode); 
    }

    /**
     * This function is used to return json response on error
     * @param mixed $message=null
     * @param mixed $statusCode=401
     * @param mixed $errors=[]
     * 
     * @return [type]
     */
    public function error($message=null, $statusCode=401, $errors=[]) {
        return response([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $statusCode); 
    }
}
