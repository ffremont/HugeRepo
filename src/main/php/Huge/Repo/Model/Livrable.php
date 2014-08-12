<?php

namespace Huge\Repo\Model;

use Huge\Rest\Data\IValidator;

class Livrable implements IValidator{
    
    const COLLECTION = 'livrables';

    public $id;
    
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
     * Créé une instance Livrable à partir d'un tableau (venant de mongo)
     * 
     * @param array $data
     */
    public static function create($data){
        $l = new Livrable();
        $l->id = ''.$data['_id'];
        $l->projectName = $data['projectName'];
        $l->vendorName = $data['vendorName'];
        $l->version = $data['version'];
        $l->classifier = $data['classifier'];
        $l->format = $data['format'];
        
        return $l;
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

