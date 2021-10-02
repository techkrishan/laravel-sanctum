<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\{Question, User, Lookup};


class QuestionService extends AppService {

    public function __construct() {
        $this->sortableFields = ['updated_at', 'id', 'user_id', 'question', 'is_active', 'created_at', 'created_by', 'category'];
    }

    /**
     * Fetch paginated question list or question details
     * @param mixed $request=null
     * @param mixed $userId=null
     * @param mixed $id=null
     * 
     * @return array An array of questions or questions details
     */
    public function fetch($request=null, $userId=null, $id=null) {
        $question       = new Question();
        $questionTable  = $question->getTable();
        $userTable      = (new User())->getTable();
        $lookupTable    = (new Lookup())->getTable();
        
        // Default conditions
        $conditions = [$questionTable.'.is_deleted' => config('constants.boolean_false')];
        
        // Owner condition
        if($userId) {
            $conditions[$questionTable.'.user_id'] = $userId;
        }

        $sqlObject = $question->select([
                $questionTable.'.*',  $userTable.'.full_name as created_by', $lookupTable.'.label as category',
            ])
            ->join($lookupTable, $questionTable.'.category_id', '=', $lookupTable.'.id')
            ->join($userTable, $questionTable.'.user_id', '=', $userTable.'.id')
            ->where($conditions); 

        if(!empty($id)) {
            return $sqlObject->find($id);
        } else {
            // Apply sorting
            $sortedFields = $this->sortByField($request);
            return $sqlObject->orderBy($sortedFields['sort_by'], $sortedFields['sort_order'])->paginate(config('constants.page_limit'));
        }
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