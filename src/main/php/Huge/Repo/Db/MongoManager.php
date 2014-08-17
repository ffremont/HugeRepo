<?php

namespace Huge\Repo\Db;

use Huge\IoC\Annotations\Component;

/**
 * @Component
 */
class MongoManager {

    /**
     *
     * @var \MongoClient
     */
    private $mongoClient;
    
    /**
     *
     * @var string
     */
    private $dbName;
    
    public function __construct(\Huge\Repo\ConfigIniHelper $config) {
        $this->mongoClient = new \MongoClient($config->getConfig('mongo.server'));
        $this->dbName = $config->getConfig('mongo.dbName');
    }

    /**
     * 
     * @return \MongoClient
     */
    public function getMongoClient() {
        return $this->mongoClient;
    }
    
    /**
     * 
     * @return \MongoGridFS
     */
    public function getGridFS(){
        return $this->mongoClient->selectDB($this->dbName)->getGridFS();
    }
    
    /**
     * Retourne une collection
     * 
     * @param string $name
     * @return \MongoCollection
     */
    public function getCollection($name){
        return $this->mongoClient->selectCollection($this->dbName, $name);
    }
}

