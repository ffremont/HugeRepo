<?php

namespace Huge\Repo\Model;

class Store {
    
    public $data;
    
    public $totalPage;
    
    public $currentPage;
    
    public $totalRows;
    
    public function __construct() {
        $this->data = array();
    }

}

