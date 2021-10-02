<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Auth;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->success((new UserService())->fetch($request, Auth::user()->id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $question = (new UserService())->saveDetails(null, $request->validated(), Auth::user()->id);
        return $this->success($question, __('messages.user_saved'), config('constants.status_code.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch user details
        $user = (new UserService())->fetch(null, Auth::user()->id, $id);
        if(empty($user)) {
            return $this->error(__('messages.user_not_found'), config('constants.status_code.not_found'));
        }
        return $this->success($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        // Fetch user details
        $user = (new UserService())->fetch(null, Auth::user()->id, $id);
        if(empty($user)) {
            return $this->error(__('messages.user_not_found'), config('constants.status_code.not_found'));
        }

        $user = (new UserService())->saveDetails($user, $request->validated());
        return $this->success($user, __('messages.user_saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Fetch user details
        $user = (new UserService())->fetch(null, Auth::user()->id, $id);
        if(empty($user)) {
            return $this->error(__('messages.user_not_found'), config('constants.status_code.not_found'));
        }

        $user = (new UserService())->saveDetails($user, ['is_deleted' => config('constants.boolean_true')]);
        return $this->success([], __('messages.user_deleted'));
    }
}
