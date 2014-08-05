<?php

namespace Huge\Repo\Ressources;

use Huge\Ioc\Annotations\Component;
use Huge\IoC\Annotations\Autowired;
use Huge\Rest\Annotations\Resource;
use Huge\Rest\Annotations\Path;
use Huge\Rest\Annotations\Produces;
use Huge\Rest\Annotations\Get;
use Huge\Rest\Annotations\Delete;
use Huge\Rest\Annotations\Put;
use Huge\Rest\Annotations\Post;
use Huge\Rest\Annotations\Consumes;
use Huge\Rest\Http\HttpRequest;
use Huge\Rest\Http\HttpResponse;

/** * 
 * @Component
 * @Resource
 * @Path("livrable")
 * 
 * @Consumes({"application/vnd.livrable.v1+json", "application/json"})
 * @Produces({"application/vnd.livrable.v1+json"})
 */
class Livrable {

    /**
     * @Autowired("Huge\Rest\Http\HttpRequest")
     * @var \Huge\Rest\Http\HttpRequest
     */
    private $request;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger; 
   
    /**
     * 
     * @param \Huge\IoC\Factory\ILogFactory $factoryLogger
     */
    public function __construct(\Huge\IoC\Factory\ILogFactory $factoryLogger) {
        $this->logger = $factoryLogger->getLogger(__CLASS__);
    }

    /**
     * @Get
     * @Consumes({"text/plain"})
     * @Produces({"text/plain"})
     */
    public function ping() {        
        return HttpResponse::ok();
    }

    public function getRequest() {
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
    }

}

