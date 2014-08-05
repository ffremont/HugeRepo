<?php

$loader = require(__DIR__ . '/../../../vendor/autoload.php');

// LOGGER log4php
$configurator = new \LoggerConfiguratorDefault();
\Logger::configure($configurator->parse(__DIR__.'/../resources/log4php.xml'));

\Huge\IoC\Container\SuperIoC::registerLoader(array($loader, 'loadClass'));

$ioc = new \Huge\Rest\WebAppIoC('1.0');
$ioc->setCacheImpl(new \Doctrine\Common\Cache\ArrayCache());

\Huge\Repo\BuildClass::init($ioc);
$ioc->addDefinitions(array(
    array(
        'class' => 'Huge\Repo\Ressources\Livrable',
        'factory' => \Huge\Repo\BuildClass::getInstance()
    ),
    array(
        'class' => 'Huge\Rest\Interceptors\PerfInterceptor',
        'factory' => \Huge\IoC\Factory\SimpleFactory::getInstance()
    ),
    array(
        'class' => 'Huge\Repo\Log\Log4phpFactory',
        'factory' => \Huge\IoC\Factory\SimpleFactory::getInstance()
    )
));
$ioc->addFiltersMapping(array(
    'Huge\Rest\Interceptors\PerfInterceptor' => '.*'
));

$ioc->run();
?>