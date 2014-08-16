<?php

namespace Huge\Repo;

abstract class Fixture {
    
    /**
     *
     * @var array
     */
    protected $collections;

    public function __construct() {
        $this->collections = array();
    }
    
    /**
     * 
     * @param \MongoDB $mongoDb
     */
    public function apply(\MongoDB $mongoDb) {   
        foreach($this->collections as $collectionName => $models){
            
            $collection = $mongoDb->selectCollection($collectionName);
            
            $collection->remove(array()); // purge
            $collection->batchInsert($models);
        }
    }
    
    /**
     * 
     * @return array
     */
    public function getCollections() {
        return $this->collections;
    }
    
    /**
     * 
     * @param string $collectionName
     * @return array
     */
    public function getCollection($collectionName, $index = null){
        $collection = isset($collectionName, $this->collections) ? $this->collections[$collectionName] : array();
        
        if(!is_null($index) && isset($collection[$index])){
            return $collection[$index];
        }
        
        return $collection;
    }
}

