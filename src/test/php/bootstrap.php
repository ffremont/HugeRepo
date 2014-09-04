<?php

$loader = require(__DIR__ . '/../../../vendor/autoload.php');

$loader->add('Huge\Repo\\', 'src/test/php/');
\Huge\IoC\Container\SuperIoC::registerLoader(array($loader, 'loadClass'));

$GLOBALS['resourcesDir'] = __DIR__ . '/../resources';
$GLOBALS['variables'] = parse_ini_file($GLOBALS['resourcesDir'] . '/variables.ini');
$configs = require($GLOBALS['resourcesDir'].'/config.php');






