<?php

namespace Huge\Repo\Ressources;

use Huge\Repo\Model\Livrable;

class LivrableFixture extends \Huge\Repo\Fixture {

    public function __construct() {
        parent::__construct();

        $this->files = array(
            array(
                'filename' => $GLOBALS['resourcesDir'] . '/test.zip',
                'meta' => array(
                    '_id' => new \MongoId('53e998b55768bc8f088b4567'),
                    'vendorName' => 'Florent',
                    'projectName' => 'MonAppli',
                    'version' => '1.2.0',
                    'classifier' => 'dev',
                    'format' => 'zip',
                    'created' => new \MongoDate(),
                    'sha1' => 'a6387f979e9d17a1de3bd3efde69dfb461bf9888'
                )
            ), array(
                'filename' => $GLOBALS['resourcesDir'] . '/test.zip',
                'meta' => array(
                    '_id' => new \MongoId('53e998f75768bca60d8b4567'),
                    'vendorName' => 'Florent',
                    'projectName' => 'MonAppli',
                    'version' => '1.2.0',
                    'classifier' => 'prod',
                    'format' => 'zip',
                    'created' => new \MongoDate(),
                    'sha1' => 'a6387f979e9d17a1de3bd3efde69dfb461bf9888'
                )
            ), array(
                'filename' => $GLOBALS['resourcesDir'] . '/test.zip',
                'meta' => array(
                    '_id' => new \MongoId('53e998f75768bca60d9b4567'),
                    'vendorName' => 'Florent',
                    'projectName' => 'MonAppli',
                    'version' => '1.2.1',
                    'classifier' => 'prod',
                    'format' => 'zip',
                    'created' => new \MongoDate(),
                    'sha1' => 'a6387f979e9d17a1de3bd3efde69dfb461bf9888'
                )
            ), array(
                'filename' => $GLOBALS['resourcesDir'] . '/test.zip',
                'meta' => array(
                    '_id' => new \MongoId('53e998f75768bca60d9b4557'),
                    'vendorName' => 'Florent',
                    'projectName' => 'MonAppli',
                    'version' => '1.3.0',
                    'classifier' => null,
                    'format' => 'zip',
                    'created' => new \MongoDate(),
                    'sha1' => 'a6387f979e9d17a1de3bd3efde69dfb461bf9888'
                )
           )
        );
    }

}

