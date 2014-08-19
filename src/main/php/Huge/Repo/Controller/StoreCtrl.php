<?php

namespace Huge\Repo\Controller;

use Huge\Repo\Model\Livrable;
use Huge\Repo\Model\Store;
use Huge\IoC\Annotations\Component;
use Huge\IoC\Annotations\Autowired;

use Guzzle\Http as GuzzleHttp;

/**
 * @Component
 */
class StoreCtrl {
    /**
     * Taille limite d'une page
     */
    const LIMIT_PAGE = 30;

    /**
     * @Autowired("Huge\Repo\Db\MongoManager")
     * @var \Huge\Repo\Db\MongoManager 
     */
    private $mongo;

    /**
     * @Autowired("Huge\Repo\ConfigIniHelper")
     * @var \Huge\Repo\ConfigIniHelper
     */
    private $config;

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
     * 
     * @param string $vendor
     * @param string $project
     * @param string $version
     * @param string $classifier
     */
    public function search($vendor, $project, $version, $classifier, $currentPage) {
        $store = new Store();

        $query = array();
        if ($vendor !== null) {
            $query['vendorName'] = new \MongoRegex('/' . $vendor . '/i');
        }
        if ($project !== null) {
            $query['projectName'] = new \MongoRegex('/' . $project . '/i');
        }
        if ($version !== null) {
            $query['version'] = new \MongoRegex('/' . $version . '/i');
        }
        if ($classifier !== null) {
            $query['classifier'] = new \MongoRegex('/' . $classifier . '/i');
        }

        $store->totalRows = $this->mongo->getGridFS()->count($query);
        $store->totalPage = ($store->totalRows / self::LIMIT_PAGE) > 1 ? ($store->totalRows / self::LIMIT_PAGE) + 1 : 1;

        $data = $this->mongo->getGridFS()->find($query)->skip($currentPage === 1 ? 0 : $currentPage * self::LIMIT_PAGE)->limit(self::LIMIT_PAGE);
        foreach ($data as $row) {
            $store->data[] = Livrable::create($row->file);
        }

        $store->currentPage = $currentPage;

        return $store;
    }
    
    /**
     * 
     * @param array $query critères Mongo
     * @return array
     */
    public function get($query, $uri){
        $livrable = $this->mongo->getGridFS()->findOne($query);
        
        if ($livrable === null) {
            foreach($this->config->getConfig('slaves') as $slaveUrl){
                $slaveUrl = trim($slaveUrl, '/');
                $client = new GuzzleHttp\Client($slaveUrl);
                $status = 500;
                try{
                    $response = $client->head($uri)->send();
                    $status = $response->getStatusCode();
                }catch(Exception $e){}                
                
                if($status === 200){
                    return array('redirect' => $slaveUrl.$uri);
                }
            }
            return null;
        } else {
            $data = Livrable::create($livrable->file);
        }
        
        return array(
            'stream' => $livrable->getResource(),
            'filename' => $data->projectName . ($data->classifier === null ? '' : '-' . $data->classifier) . '-' . $data->version . '.' . $data->format
        );
    }
    
    /**
     * 
     * @param string $vendorName
     * @param string $projectName
     * @param string $version
     * @param string $classifier
     * @return \Huge\Repo\Model\Livrable
     */
    public function getLivrableByCriteria($vendorName, $projectName, $version, $classifier){
        return $this->get(array(
            'vendorName' => $vendorName,
            'projectName' => $projectName,
            'version' => $version,
            'classifier' => $classifier
        ), "/livrable/$vendorName/$projectName/$version/$classifier");
    }

    /**
     * 
     * @param \Huge\Repo\Model\Livrable $livrable
     * @param string $tmpFile
     * @return \MongoId
     */
    public function creerLivrable(Livrable $livrable, $tmpFile) {
        $aLivrable = (array) $livrable;
        
        return $this->mongo->getGridFS()->storeFile($tmpFile, $aLivrable);
    }

    /**
     * 
     * @param string $id
     * @return boolean
     */
    public function supprimer($id) {
        $result = $this->mongo->getGridFS()->remove(array('_id' => new \MongoId($id)));
        
        return ($result === true) || (isset($result['ok']) && $result['ok']);
    }

    /**
     * Recherche le livrable dans l'instance courante, sinon dans les slaves
     * 
     * @param string $id
     * @return array
     */
    public function getLivrable($id) {
        return $this->get(array(
            '_id' => new \MongoId($id)
        ), "/livrable/$id");
    }

    public function getMongo() {
        return $this->mongo;
    }

    public function setMongo(\Huge\Repo\Db\MongoManager $mongo) {
        $this->mongo = $mongo;
    }

    public function getConfig() {
        return $this->config;
    }

    public function setConfig(\Huge\Repo\ConfigIniHelper $config) {
        $this->config = $config;
    }

}

