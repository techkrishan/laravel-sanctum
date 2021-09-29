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
        return $this->success((new QuestionService())->fetchQuestions($request, Auth::user()->id), "", 200);
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
        return $this->success($question, __('messages.question_saved'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = (new QuestionService())->fetchDetails($id, Auth::user()->id);

        if(empty($question)) {
            return $this->error(__('messages.question_not_found'), 404);
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
        $question = (new QuestionService())->fetchDetails($id, Auth::user()->id);
        if(empty($question)) {
            return $this->error(__('messages.question_not_found'), 404);
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
        $question = (new QuestionService())->fetchDetails($id, Auth::user()->id);
        if(empty($question)) {
            return $this->error(__('messages.question_not_found'), 404);
        }
        
        $question = (new QuestionService())->saveDetails($question, ['is_deleted'=>1]);
        return $this->success(['id'=>$question->id], __('messages.question_saved'));
    }
}
