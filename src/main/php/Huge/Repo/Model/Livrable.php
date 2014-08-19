<?php

namespace Huge\Repo\Model;

use Huge\Rest\Data\IValidator;

class Livrable implements IValidator{
    
    const COLLECTION = 'livrables';

    public $id;
    
    /**
     *
     * @var \MongoDate
     */
    public $created;
    
    /**
     *
     * @var string
     */
    public $vendorName;
    /**
     *
     * @var string
     */
    public $projectName;
    /**
     *
     * @var string
     */
    public $version;
    /**
     *
     * @var string
     */
    public $classifier;
    
    /**
     *
     * @var string
     */
    public $format;
    
    /**
     *
     * @var string
     */
    public $sha1;
        
    public function __construct() {
        $this->created = new \MongoDate();
    }
    
    /**
     * Créé une instance Livrable à partir d'un tableau (venant de mongo)
     * 
     * @param array $data
     * @return Livrable
     */
    public static function create(array $data){
        $l = new Livrable();
        $l->id = ''.$data['_id'];
        $l->projectName = $data['projectName'];
        $l->vendorName = $data['vendorName'];
        $l->version = $data['version'];
        $l->classifier = $data['classifier'];
        $l->format = $data['format'];
        $l->sha1 = $data['sha1'];
        $l->created = $data['created'];
        
        return $l;
    }
    
    public function getFileName(){
        return $this->projectName . ($this->classifier === null ? '' : '-' . $this->classifier) . '-' . $this->version . '.' . $this->format;
    }
    
    
    public static function getConfig() {
        return array(
            'vendorName' => array(
                'required',
                'maxLength' => 64
            ),
            'projectName' => array(
                'required',
                'maxLength' => 64
            ),
            'version' => array(
                'required', 
                'regex' => '#^[0-9]\.[0-9]\.[0-9]$#'
            )
        );
    }
}

