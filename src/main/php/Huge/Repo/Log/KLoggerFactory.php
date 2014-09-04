<?php

namespace Huge\Repo\Log;

use Huge\IoC\Annotations\Component;

use Huge\IoC\Factory\ILogFactory;
use Katzgrau\KLogger\Logger;
use Psr\Log\LogLevel;

/**
 * @Component
 */
class KLoggerFactory  implements ILogFactory {
    
    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    
    public function __construct($logDirectory, $logLevelThreshold = LogLevel::INFO){
        $this->logger = new Logger($logDirectory, $logLevelThreshold);
    }
    
     /**
     * 
     * @param string $name
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger($name){
        return $this->logger;
    }
}
