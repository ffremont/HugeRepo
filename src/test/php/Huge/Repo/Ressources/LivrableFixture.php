<?php

namespace Huge\Repo\Ressources;

use Huge\Repo\Model\Livrable;

class LivrableFixture extends \Huge\Repo\Fixture{

     public function __construct(){
        parent::__construct();
        
        $this->collections[Livrable::COLLECTION][] = array(
             '_id' => new \MongoId('53e998b55768bc8f088b4567'),
            'vendorName' => 'Florent',
            'projectName' => 'MonAppli',
            'version' => '1.2.0',
            'classifier' => 'dev',
            'format' => 'zip',
            'sha1' => 'a6387f979e9d17a1de3bd3efde69dfb461bf9888'
        );
        $this->collections[Livrable::COLLECTION][] = array(
             '_id' => new \MongoId('53e998f75768bca60d8b4567'),
            'vendorName' => 'Florent',
            'projectName' => 'MonAppli',
            'version' => '1.2.0',
            'classifier' => 'prod',
            'format' => 'zip',
            'sha1' => 'a6387f979e9d17a1de3bd3efde69dfb461bf9888'
        );
        $this->collections[Livrable::COLLECTION][] = array(
             '_id' => new \MongoId('53e998f75768bca60d9b4567'),
            'vendorName' => 'Florent',
            'projectName' => 'MonAppli',
            'version' => '1.2.1',
            'classifier' => 'prod',
            'format' => 'zip',
            'sha1' => 'a6387f979e9d17a1de3bd3efde69dfb461bf9888'
        );
    }

}

