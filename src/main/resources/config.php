<?php

return array(
    'version' => '${version}',
    // nom de l'instance master, slave1, slave2 ...
    'instance.name' => 'master',
    'mongo.server' => 'mongodb://localhost:27018',
    'mongo.dbName' => 'hugeRepo',
    'debug' => false,
    'memcache.enable' => true,
    'memcache.host' => '127.0.0.1',
    'memcache.port' => 11211,
    // liste des slaves
    'slaves' => array(
    /* 'http://slave1.fr' */
    ),
    
    'klogger' => array(
        'level' => 'warn',
        'path' => __DIR__.'/../../../log/'
    )
);

