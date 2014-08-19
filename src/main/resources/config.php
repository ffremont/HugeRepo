<?php

return array(
    // nom de l'instance master, slave1, slave2 ...
    'instance.name' => 'master',
    'mongo.server' => 'mongodb://localhost:27018',
    'mongo.dbName' => 'hugeRepo',
    'debug' => false,
    'memcache.enable' => true,
    'memcache.host' => '127.0.0.1',
    'memcache.port' => 11211,
    // liste des claves
    'slaves' => array(
    /* 'http://slave1.fr' */
    ),
    
    'log4phpConfig' => array(
        'rootLogger' => array(
            'level' => 'WARN',
            'appenders' => array('default'),
        ),
        'appenders' => array(
            'default' => array(
                'class' => 'LoggerAppenderFile',
                'layout' => array(
                    'class' => 'LoggerLayoutPattern',
                    'params' => array(
                        'conversionPattern' => '%date{Y-m-d H:i} - %logger %-5level : %msg%n%ex'
                    )
                ),
                'params' => array(
                    'file' => __DIR__.'/../../../log/repo.log',
                    'append' => true
                )
            )
        )
    )
);

