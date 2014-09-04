<?php

return array(
    'version' => '${version}',
    // nom de l'instance master, slave1, slave2 ...
    'instance.name' => 'master',
    'mongo.server' => 'mongodb://localhost:27017',
    'mongo.dbName' => 'hugeRepoTest',
    'debug' => true,
    'memcache.enable' => false,
    'memcache.host' => '127.0.0.1',
    'memcache.port' => 11211,
    // liste des claves
    'slaves' => array(),
    
    'klogger' => array(
        'level' => 'debug',
        'path' => '/var/log/apps/hugeRepo/'
    )
);

