<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\{Question, User};


class QuestionService extends AppService {

    public function __construct() {
        $this->sortableFields = ['updated_at', 'id', 'user_id', 'question', 'is_active', 'created_at', 'created_by'];
    }

    /**
     * Fetch paginated list
     * @param mixed $request
     * 
     * @return array An array of questions 
     */
    public function fetchQuestions($request, $userId=null) {
        $question       = new Question();
        $userTable      = (new User())->getTable();
        $questionTable  = $question->getTable();
        
        // Apply sorting
        $sortedFields = $this->sortByField($request);

        // Default conditions
        $conditions = [$questionTable.'.is_deleted' => 0];
        
        // Owner condition
        if($userId) {
            $conditions[$questionTable.'.user_id'] = $userId;
        }

        return $question
            ->select([
                $questionTable.'.*',  $userTable.'.full_name as created_by'
            ])
            ->join($userTable, $questionTable.'.user_id', '=', $userTable.'.id')
            ->where($conditions)
            ->orderBy($sortedFields['sort_by'], $sortedFields['sort_order'])
            ->paginate(config('constants.page_limit'));
    }

    /**
     * Fetch question details 
     * @param mixed $id
     * @param mixed $userId=null User ID of owner
     * 
     * @return array An array of question details
     */
    public function fetchDetails($id, $userId=null) {
        // Default conditions
        $conditions = ['is_deleted' => 0];
        
        // Owner condition
        if($userId) {
            $conditions['user_id'] = $userId;
        }

        return (new Question())
            ->where($conditions)
            ->with(['User' => function($query) {
                $query->select(['id', 'first_name', 'last_name', 'full_name']);
            }])
            ->find($id);
    }


    public function saveDetails($question=null, $requestFields=[], $userId=null) {
        if(empty($question)) {
            $question = new Question();
            $question->user_id = $userId;
        }

        foreach($requestFields as $key => $value) {
            $question->$key = $value;
        }
        $question->save();
        return $question;
    }
}