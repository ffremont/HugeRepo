<?php

namespace Huge\Repo\Filters;

use Huge\IoC\Annotations\Component;
use Huge\IoC\Annotations\Autowired;
use Huge\Rest\Process\IResponseFilter;

/**
 * @Component
 */
class PoweredByFilter implements IResponseFilter{
    
    /**
     * @Autowired("Huge\Repo\ConfigIniHelper")
     * @var \Huge\Repo\ConfigIniHelper
     */
    private $config;

    public function doFilter(\Huge\Rest\Http\HttpResponse $response) {
        $response->addHeader('x-powered-by', $this->config->getConfig('instance.name'));
    }
    
    public function setConfig(\Huge\Repo\ConfigIniHelper $config) {
        $this->config = $config;
    }
}

