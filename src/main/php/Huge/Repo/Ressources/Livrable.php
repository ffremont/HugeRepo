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
use Huge\Rest\Http\HttpFiles;
use Huge\Rest\Http\HttpFile;

use Huge\Repo\Model\Livrable as MLivrable;

/** * 
 * @Component
 * @Resource
 * @Path("livrable")
 * 
 */
class Livrable {

    /**
     * @Autowired("Huge\Rest\Http\HttpRequest")
     * @var \Huge\Rest\Http\HttpRequest
     */
    private $request;

    /**
     *
     * @Autowired("Huge\Repo\Controller\StoreCtrl")
     * @var \Huge\Repo\Controller\StoreCtrl
     */
    private $ctrl;

    /**
     * @Autowired("Huge\Rest\Http\BodyReader")
     * @var \Huge\Rest\Http\BodyReader
     */
    private $bodyReader;

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
     * @Post
     * @Consumes({"multipart/form-data"})
     * @Produces({"application/json"})
     */
    public function create() {
        $entity = $this->request->getEntity();
        if ($entity instanceof HttpFiles) {
            $files = $entity->getFiles();
            if(count($files) !== 1){
                return HttpResponse::status(400);
            }
            
            $file = new HttpFile(array_shift($files));

            $params = $this->request->getParams();
            $this->bodyReader->validate('Huge\Repo\Model\Livrable', $params);
            
            $livrable = new MLivrable();
            $livrable->vendorName = $params['vendorName'];
            $livrable->projectName = $params['projectName'];
            $livrable->version = $params['version'];
            $livrable->classifier = isset($params['classifier']) ? $params['classifier'] : null;
            $livrable->format = strtolower($file->getExtension());

            $id = $this->ctrl->creerLivrable($livrable, $file->getTmpFile());
            
            return HttpResponse::ok()->status(201)->entity($livrable)->addHeader('Location', '/livrable/'.$id);
        } else {
            HttpResponse::status(400);
        }
    }
    
    /**
     * @Get
     * @Path("search")
     * @Produces({"application/json"})
     * 
     * @param string $vendor
     * @param string $project
     * @param string $version
     * @param string $classifier
     */
    public function search() {    
        $vendor = $this->request->getParam('vendorName');
        $project = $this->request->getParam('projectName');
        $version = $this->request->getParam('version');
        $classifier = $this->request->getParam('classifier');
        
        return HttpResponse::ok()->entity($this->ctrl->search($vendor, $project, $version, $classifier, $this->request->getParam('page') === null ? 1 : $this->request->getParam('page')));
    }

    /**
     * @Get
     * @Path(":mAlpha")
     * @Produces({"application/octet-stream"})
     * 
     * @param string $vendor
     * @param string $project
     * @param string $version
     * @param string $classifier
     */
    public function get($id) {        
        $info = $this->ctrl->getLivrable($id);
        
        return HttpResponse::ok()->addHeader('Content-Disposition', 'attachment; filename="'.$info['filename'].'"')->entity($info['stream']);
    }

     /**
     * @Delete
     * @Path(":mAlpha")
     * 
     * @param string $vendor
     * @param string $project
     * @param string $version
     * @param string $classifier
     */
    public function delete($id) {
        
        
        return HttpResponse::ok();
    }

    public function getRequest() {
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
    }

    public function getCtrl() {
        return $this->ctrl;
    }

    public function setCtrl(\Huge\Repo\Controller\StoreCtrl $ctrl) {
        $this->ctrl = $ctrl;
    }

    public function getBodyReader() {
        return $this->bodyReader;
    }

    public function setBodyReader(\Huge\Rest\Http\BodyReader $bodyReader) {
        $this->bodyReader = $bodyReader;
    }

}

