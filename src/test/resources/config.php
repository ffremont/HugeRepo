<?php

return array(
    'version' => '${version}',
    // nom de l'instance master, slave1, slave2 ...
    'instance.name' => 'master',
    'mongo.server' => 'mongodb://localhost:27018',
    'mongo.dbName' => 'hugeRepoTest',
    'debug' => true,
    'memcache.enable' => false,
    'memcache.host' => '127.0.0.1',
    'memcache.port' => 11211,
    // liste des claves
    'slaves' => array(),
    
    'log4phpConfig' => array(
        'rootLogger' => array(
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
                    'file' => '/var/log/apps/hugeRepo/hugeRepoTest.log',
                    'append' => true
                )
            )
        )
    )
);

