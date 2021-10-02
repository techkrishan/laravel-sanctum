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
     * Fetch user details or paginated user list
     * @param mixed $request=null
     * @param mixed $userId=null Owner user ID
     * @param mixed $id=null User ID 
     * 
     * @return array An array of questions or question details 
     */
    public function fetch($request=null, $userId=null, $id=null) {
        $user           = new user();
        $userTable      = $user->getTable();
        $lookupTable    = (new Lookup())->getTable();
        
        // Default conditions
        $conditions = [
            $lookupTable.'.is_deleted'  => config('constants.boolean_false'),
            $lookupTable.'.is_active'   => config('constants.boolean_true'),
            $userTable.'.is_deleted'    => config('constants.boolean_false'),
        ];
        
        // Owner condition
        if($userId) {
            $conditions[$userTable.'.user_id'] = $userId;
        }

        $sqlObject = $user->select([
                $userTable.'.*',  $lookupTable.'.label as user_type', 'owner_users.full_name as created_by'
            ])
            ->leftJoin($userTable.' as owner_users', $userTable.'.user_id', '=', 'owner_users.id')
            ->join($lookupTable, $userTable.'.user_type_id', '=', $lookupTable.'.id')
            ->where($conditions); 

        if(!empty($id)) {
            return $sqlObject->find($id);
        } else {
            // Apply sorting
            $sortedFields = $this->sortByField($request);
            return $sqlObject->orderBy($sortedFields['sort_by'], $sortedFields['sort_order'])->paginate(config('constants.page_limit'));
        }
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
        
        if(empty($user)) {
            $user = new User();
            $user->user_id = $userId;

            // Set default user type
            if(empty($requestFields['user_type_id'])) {
                $requestFields['user_type_id'] = Lookup::where([
                    'slug' => config('lookups.user_type.interviewee.slug')
                ])->first()->id;
            }
        }

        foreach($requestFields as $key => $value) {
            $user->$key = $value;
        }
        $user->save();
        return $user;
    }
}