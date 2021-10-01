<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\{User, Lookup};


class UserService extends AppService {

    public function __construct() {
        $this->sortableFields = [
            'updated_at', 
            'id', 
            'first_name', 
            'last_name', 
            'email', 
            'full_name', 
            'is_active', 
            'created_at', 
            'created_by',
            'user_type',
        ];
    }

    /**
     * Fetch paginated list
     * @param mixed $request
     * 
     * @return array An array of questions 
     */
    public function fetchList($request, $userId=null) {
        $user           = new user();
        $userTable      = $user->getTable();
        $lookupTable    = (new Lookup())->getTable();
        
        // Apply sorting
        $sortedFields = $this->sortByField($request);

        // Default conditions
        $conditions = [
            $lookupTable.'.is_deleted' => 0,
            $lookupTable.'.is_active' => 1,
            $userTable.'.is_deleted' => 0,
        ];
        
        // Owner condition
        if($userId) {
            $conditions[$userTable.'.user_id'] = $userId;
        }

        return $user
            ->select([
                $userTable.'.*',  $lookupTable.'.label as user_type', 'owner_users.full_name as created_by'
            ])
            ->leftJoin($userTable.' as owner_users', $userTable.'.user_id', '=', 'owner_users.id')
            ->join($lookupTable, $userTable.'.user_type_id', '=', $lookupTable.'.id')
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


    /**
     * Save the user details
     * @param mixed $user=null
     * @param mixed $requestFields=[]
     * @param mixed $userId=null
     * 
     * @return array An array of user details 
     */
    public function saveDetails($user=null, $requestFields=[], $userId=null) {
        
        // Set default user type
        if(empty($requestFields['user_type_id'])) {
            $requestFields['user_type_id'] = Lookup::where([
                'slug' => config('lookups.user_type.interviewee.slug')
            ])->first()->id;
        }

        if(empty($user)) {
            $user = new User();
            $user->user_id = $userId;
        }

        foreach($requestFields as $key => $value) {
            $user->$key = $value;
        }
        $user->save();
        return $user;
    }
}