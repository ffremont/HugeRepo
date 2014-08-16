<?php

namespace Huge\Repo;

use Huge\IoC\Annotations\Component;

/**
 * @Component
 */
class ConfigIniHelper {

    /**
     *
     * @var array
     */
    private $configs;
    
    public function __construct($configs = array()) {
        $this->configs = $configs;
    }
    
    public function getConfigs() {
        return $this->configs;
    }
    
     /**
     * 
     * @param string $name
     * @return mixed
     */
    public function getConfig($name){
        return isset($this->configs[$name]) ? $this->configs[$name] : null;
    }

}

