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
}