<?php

namespace App\Traits;

trait Sortable {
    
    public function sortable($request) {
        print_r($this->sortable);
        print_r($request->all());
        echo "We are in sortable traits";
        die;
    }
}