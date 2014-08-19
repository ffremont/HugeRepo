<?php

namespace Huge\Repo;

use Huge\IoC\Container\SuperIoC;

class RepoIoC extends SuperIoC {

    public function __construct($configs = array()) {
        parent::__construct(__CLASS__, '1.0');
        
        \Huge\Repo\BuildClass::init($this);
        $this->addDefinitions(array(
            array(
                'class' => 'Huge\Repo\ConfigIniHelper',
                'factory' => new \Huge\IoC\Factory\ConstructFactory(array($configs))
            ),
            array( 'class' => 'Huge\Repo\Ressources\Livrable', 'factory' => \Huge\Repo\BuildClass::getInstance() ),
            array( 'class' => 'Huge\Repo\Controller\StoreCtrl', 'factory' => \Huge\Repo\BuildClass::getInstance() ),
            array( 'class' => 'Huge\Repo\Db\MongoManager', 'factory' => new \Huge\IoC\Factory\ConstructFactory(array(new \Huge\IoC\RefBean('Huge\Repo\ConfigIniHelper', $this)))),
            
            array( 'class' => 'Huge\Rest\Interceptors\PerfInterceptor', 'factory' => \Huge\IoC\Factory\SimpleFactory::getInstance() ),
            array('class' => 'Huge\Repo\Log\Log4phpFactory', 'factory' => \Huge\IoC\Factory\SimpleFactory::getInstance() ),
            
            array( 'class' => 'Huge\Repo\Filters\PoweredByFilter', 'factory' => \Huge\IoC\Factory\SimpleFactory::getInstance() ),
        ));
    }

}

