<?php

namespace App\Http\Controllers\V1;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\QuestionRequest;
use Auth;
use App\Services\QuestionService;


class QuestionController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->success((new QuestionService())->fetch($request, Auth::user()->id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $question = (new QuestionService())->saveDetails(null, $request->validated(), Auth::user()->id);
        return $this->success($question, __('messages.question_saved'), config('constants.status_code.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // fetch question details
        $question = (new QuestionService())->fetch(null, Auth::user()->id, $id);
        if(empty($question)) {
            return $this->error(__('messages.question_not_found'), config('constants.status_code.not_found'));
        }
        return $this->success($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, $id)
    {   
        // fetch question details
        $question = (new QuestionService())->fetch(null, Auth::user()->id, $id);
        if(empty($question)) {
            return $this->error(__('messages.question_not_found'), config('constants.status_code.not_found'));
        }

        $question = (new QuestionService())->saveDetails($question, $request->validated());
        return $this->success($question, __('messages.question_saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // fetch question details
        $question = (new QuestionService())->fetch(null, Auth::user()->id, $id);
        if(empty($question)) {
            return $this->error(__('messages.question_not_found'), config('constants.status_code.not_found'));
        }
        
        $question = (new QuestionService())->saveDetails($question, ['is_deleted' => config('constants.boolean_true')]);
        return $this->success([], __('messages.question_deleted'));
    }
}
