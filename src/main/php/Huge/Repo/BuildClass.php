<?php

namespace Huge\Repo;

use Huge\IoC\RefBean;
use Huge\IoC\Factory\ConstructFactory;

/**
 * Permet de construire des classes avec le constructeur Log4phpFactory
 */
abstract class BuildClass{
    private static $instance = null;
    
    public static function init(\Huge\IoC\Container\SuperIoC $ioc){
        self::$instance = new ConstructFactory(array(new RefBean('Huge\Repo\Log\Log4phpFactory', $ioc)));
    }
    
    public static function getInstance(){        
        return self::$instance;
    }
}

