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
            array('class' => 'Huge\Repo\Ressources\Livrable', 'factory' => new ConstructFactory(array(new RefBean('Huge\Repo\Log\KLoggerFactory')))),
            array('class' => 'Huge\Repo\Controller\StoreCtrl', 'factory' => new ConstructFactory(array(new RefBean('Huge\Repo\Log\KLoggerFactory')))),
            array('class' => 'Huge\Repo\Db\MongoManager', 'factory' => new ConstructFactory(array(new \Huge\IoC\RefBean('Huge\Repo\ConfigIniHelper', $this)))),
            array('class' => 'Huge\Rest\Interceptors\PerfInterceptor', 'factory' => SimpleFactory::getInstance()),
            array('class' => 'Huge\Repo\Log\KLoggerFactory', 'factory' => new ConstructFactory(array($configs['klogger']['path'], $configs['klogger']['level']))),
            array('class' => 'Huge\Repo\Filters\PoweredByFilter', 'factory' => SimpleFactory::getInstance()),
        ));
    }

}
