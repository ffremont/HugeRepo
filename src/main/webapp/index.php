<?php

$loader = require(__DIR__ . '/../../../vendor/autoload.php');

$resource = __DIR__.'/../resources';

// LOGGER log4php
$configurator = new \LoggerConfiguratorDefault();
\Logger::configure($configurator->parse($resource.'/log4php.xml'));

\Huge\IoC\Container\SuperIoC::registerLoader(array($loader, 'loadClass'));

$ioc = new \Huge\Rest\WebAppIoC('huge-repo', '1.0');

/*$memcache = new Memcache();
$memcache->pconnect('127.0.0.1', 11211);
$cache = new \Doctrine\Common\Cache\MemcacheCache();
$cache->setMemcache($memcache);
$ioc->setApiCacheImpl($cache);
$ioc->setCacheImpl($cache);*/

$ioc->addOtherContainers(array(
    new \Huge\Repo\RepoIoC()
));

$ioc->addFiltersMapping(array(
    'Huge\Rest\Interceptors\PerfInterceptor' => '.*'
));

$ioc->run();
?>