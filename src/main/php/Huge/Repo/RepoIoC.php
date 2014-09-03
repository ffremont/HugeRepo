<?php

namespace Huge\Repo;

use Huge\IoC\Container\SuperIoC;
use Huge\IoC\Factory\ConstructFactory;
use Huge\IoC\Factory\SimpleFactory;
use Huge\IoC\RefBean;
;

class RepoIoC extends SuperIoC {

    public function __construct($configs = array()) {
        parent::__construct(__CLASS__ . $configs['instance.name'], $configs['version']);

        $this->addDefinitions(array(
            array(
                'class' => 'Huge\Repo\ConfigIniHelper',
                'factory' => new ConstructFactory(array($configs))
            ),
            array('class' => 'Huge\Repo\Ressources\Livrable', 'factory' => new ConstructFactory(array(new RefBean('Huge\Repo\Log\Log4phpFactory', $this)))),
            array('class' => 'Huge\Repo\Controller\StoreCtrl', 'factory' => new ConstructFactory(array(new RefBean('Huge\Repo\Log\Log4phpFactory', $this)))),
            array('class' => 'Huge\Repo\Db\MongoManager', 'factory' => new ConstructFactory(array(new \Huge\IoC\RefBean('Huge\Repo\ConfigIniHelper', $this)))),
            array('class' => 'Huge\Rest\Interceptors\PerfInterceptor', 'factory' => SimpleFactory::getInstance()),
            array('class' => 'Huge\Repo\Log\Log4phpFactory', 'factory' => SimpleFactory::getInstance()),
            array('class' => 'Huge\Repo\Filters\PoweredByFilter', 'factory' => SimpleFactory::getInstance()),
        ));
    }

}
