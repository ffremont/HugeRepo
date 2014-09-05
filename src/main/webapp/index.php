<?php

$loader = require(__DIR__ . '/../../../vendor/autoload.php');

$resource = __DIR__.'/../resources';
$configs = require($resource.'/config.php');

\Huge\IoC\Container\SuperIoC::registerLoader(array($loader, 'loadClass'));

$ioc = new \Huge\Rest\WebAppIoC('huge-repo-'.$configs['instance.name'], $configs['version']);

$cache = new \Doctrine\Common\Cache\ArrayCache();
if($configs['memcache.enable']){
    $memcache = new Memcache();
    $memcache->pconnect($configs['memcache.host'], $configs['memcache.port']);
    $cache = new \Doctrine\Common\Cache\MemcacheCache();
    $cache->setMemcache($memcache);
}
$ioc->setApiCacheImpl($cache);
$ioc->setCacheImpl($cache);

$ioc->addOtherContainers(array(
    new \Huge\Repo\RepoIoC($configs)
));

$ioc->addResponseFiltersMapping(array(
    'Huge\Repo\Filters\PoweredByFilter' => '.*'
));

if($configs['debug']){
    $ioc->addFiltersMapping(array(
        'Huge\Rest\Interceptors\PerfInterceptor' => '.*'
    ));
}

$ioc->run();
?>