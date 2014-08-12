<?php

namespace Huge\Repo\Controller;

use Huge\Repo\Model\Livrable;
use Huge\Repo\Model\Store;
use Huge\IoC\Annotations\Component;
use Huge\IoC\Annotations\Autowired;

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

    private function buildPath($livrable, $full = true) {
        $store = rtrim($this->config->getConfig('store', 'repository'), '/');
        $out = $store . '/' . $livrable->vendorName . '/' . $livrable->projectName . '/' . $livrable->version;
        if ($full) {
            $out = $out . '/' . $livrable->projectName . '-' . ($livrable->classifier === null ? '' : $livrable->classifier . '-') . $livrable->version . '.' . $livrable->format;
        }

        return $out;
    }
    
    /**
     * 
     * @param string $vendor
     * @param string $project
     * @param string $version
     * @param string $classifier
     */
    public function search($vendor, $project, $version, $classifier, $currentPage){
        $store = new Store();
        
        $query = array();
        if($vendor !== null){
            $query['vendorName'] = new \MongoRegex('/'.$vendor.'/i');
        }
        if($project !== null){
            $query['projectName'] = new \MongoRegex('/'.$project.'/i');
        }
        if($version !== null){
            $query['version'] = new \MongoRegex('/'.$version.'/i');
        }
        if($classifier !== null){
            $query['classifier'] = new \MongoRegex('/'.$classifier.'/i');
        }
        
        $store->totalRows = $this->mongo->getCollection(Livrable::COLLECTION)->count($query);
        $store->totalPage = ($store->totalRows / self::LIMIT_PAGE) > 1 ? ($store->totalRows / self::LIMIT_PAGE) + 1 : 1;

        $data = $this->mongo->getCollection(Livrable::COLLECTION)->find($query)->skip( $currentPage === 1 ? 0 : $currentPage*self::LIMIT_PAGE)->limit(self::LIMIT_PAGE);
        foreach($data as $row){
            $store->data[] = Livrable::create($row);
        }        
                
        $store->currentPage = $currentPage;
        
        return $store;
    }

    /**
     * 
     * @param \Huge\Repo\Model\Livrable $livrable
     * @param string $tmpFile
     * @return string
     */
    public function creerLivrable(Livrable $livrable, $tmpFile) {
        mkdir($this->buildPath($livrable, false), 0777, true);
        copy($tmpFile, $this->buildPath($livrable));

        $aLivrable = (array) $livrable;
        $this->mongo->getCollection(Livrable::COLLECTION)->insert($aLivrable);
        
        return $aLivrable['_id'];
    }

    /**
     * 
     * @param string $id
     * @return array
     */
    public function getLivrable($id) {
        $livrable = $this->mongo->getCollection(Livrable::COLLECTION)->findOne(array(
            '_id' => new \MongoId($id)
        ));
        
        if ($livrable === null) {
            return null;
        } else {
            $livrable = (object) $livrable;
        }
        /* @var $livrable \Huge\Repo\Model\Livrable */

        return array(
            'stream' => fopen($this->buildPath($livrable, true), 'r'),
            'filename' => $livrable->projectName.($livrable->classifier === null ? '' : '-'.$livrable->classifier).'-'.$livrable->version.'.'.$livrable->format
        );
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

