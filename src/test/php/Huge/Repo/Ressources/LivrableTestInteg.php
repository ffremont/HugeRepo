<?php

namespace Huge\Repo\Ressources;

use Guzzle\Http as GuzzleHttp;

class LivrableTestInteg extends \PHPUnit_Framework_TestCase {

    /**
     *
     * @var \MongoClient
     */
    private static $MONGO;

    public function __construct() {
        parent::__construct();
    }

    public static function setUpBeforeClass() {
        self::$MONGO = new \MongoClient($GLOBALS['variables']['mongo.server']);
    }

    /**
     * Avant chaque test
     * RESET de la base de données
     */
    protected function setUp() {
        $livrableF = new \Huge\Repo\Ressources\LivrableFixture();
        $livrableF->apply(self::$MONGO->selectDB($GLOBALS['variables']['mongo.dbName']));
    }
    
    /**
     * @test
     */
    public function get_livrableByCriteria_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $status = null;
        $response = null;
        try {
            $response = $client->get('/livrable/Florent/MonAppli/1.2.0/dev')->addHeader('Accept', '*/*')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(200, $status);
        $this->assertEquals('master', $response->getHeader('x-powered-by'));
        $this->assertEquals('application/octet-stream', $response->getHeader('Content-Type'));
    }
    
    /**
     * @test
     */
    public function get_livrableByCriteriaWithoutClassifier_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $status = null;
        $response = null;
        try {
            $response = $client->get('/livrable/Florent/MonAppli/1.3.0')->addHeader('Accept', '*/*')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(200, $status);
        $this->assertEquals('master', $response->getHeader('x-powered-by'));
        $this->assertEquals('application/octet-stream', $response->getHeader('Content-Type'));
    }

    /**
     * @test
     */
    public function get_livrable_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $status = null;
        $response = null;
        try {
            $response = $client->get('/livrable/53e998b55768bc8f088b4567')->addHeader('Accept', '*/*')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(200, $status);
        $this->assertEquals('master', $response->getHeader('x-powered-by'));
        $this->assertEquals('application/octet-stream', $response->getHeader('Content-Type'));
    }

    /**
     * @test
     */
    public function get_search_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $status = null;
        $response = null;
        try {
            $response = $client->get('/livrable/search')->addHeader('Accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(200, $status);
        $this->assertEquals('application/json', $response->getHeader('Content-Type'));
        
        $store = json_decode($response->getBody(true));
        $this->assertTrue(is_object($store));
        $this->assertEquals(4, $store->totalRows);
        $this->assertEquals(1, $store->currentPage);
        $this->assertEquals(1, $store->totalPage);        
        $this->assertTrue(is_array($store->data));
    }
    
    /**
     * @test
     */
    public function get_search_v121_prod_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $status = null;
        $response = null;
        try {
            $response = $client->get('/livrable/search?version=1.2.1&classifier=prod')->addHeader('Accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(200, $status);
        $this->assertEquals('application/json', $response->getHeader('Content-Type'));
        
        $store = json_decode($response->getBody(true));
        $this->assertTrue(is_object($store));
        $this->assertEquals(1, $store->totalRows);
    }
    
    /**
     * @test
     */
    public function post_livrable_invalid_ko() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $file = $GLOBALS['resourcesDir'].'/test.zip';
        $status = null;
        $response = null;
        try {
            $response = $client->post('/livrable', array(), array(
                'myFile' => '@'.$file
            ))->setHeader('accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(400, $status);
    }
    
     /**
     * @test
     */
    public function post_livrable_sansSha1_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $file = $GLOBALS['resourcesDir'].'/test.zip';
        
        $status = null;
        $response = null;
        try {
            $response = $client->post('/livrable', array(), array(
                'myFile' => '@'.$file,
                'vendorName' => 'Huge',
                'projectName' => 'MonAppli',
                'version' => '1.0.0',
                'classifier' => 'dev'
            ))->setHeader('accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(201, $status);
        $livrable = json_decode($response->getBody(true));
        
        $this->assertEquals('Huge', $livrable->vendorName);
        $this->assertEquals('MonAppli', $livrable->projectName);
        $this->assertEquals('1.0.0', $livrable->version);
        $this->assertEquals('dev', $livrable->classifier);
        $this->assertEquals('zip', $livrable->format);
    }
    
    /**
     * @test
     */
    public function post_livrable_avecSha1_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $file = $GLOBALS['resourcesDir'].'/test.zip';
        
        $status = null;
        $response = null;
        try {
            $response = $client->post('/livrable', array(), array(
                'myFile' => '@'.$file,
                'vendorName' => 'Huge',
                'projectName' => 'MonAppli',
                'version' => '1.0.0',
                'classifier' => 'dev',
                'sha1' => sha1_file($file)
            ))->setHeader('accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(201, $status);
    }
    
    /**
     * @test
     */
    public function post_livrable_force_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $file = $GLOBALS['resourcesDir'].'/test.zip';
        
        $status = null;
        $response = null;
        try {
            $response = $client->post('/livrable', array(), array(
                'myFile' => '@'.$file,
                'vendorName' => 'Huge',
                'projectName' => 'MonAppli',
                'version' => '1.0.0',
                'classifier' => 'dev',
                'force' => 1
            ))->setHeader('accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(201, $status);
        
        try {
            $response = $client->post('/livrable', array(), array(
                'myFile' => '@'.$file,
                'vendorName' => 'Huge',
                'projectName' => 'MonAppli',
                'version' => '1.0.0',
                'classifier' => 'dev',
                'force' => 1
            ))->setHeader('accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(201, $status);
    }
    
    /**
     * @test
     */
    public function post_livrable_snapshot_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $file = $GLOBALS['resourcesDir'].'/test.zip';
        
        $status = null;
        $response = null;
        try {
            $response = $client->post('/livrable', array(), array(
                'myFile' => '@'.$file,
                'vendorName' => 'Huge',
                'projectName' => 'MonAppli',
                'version' => '2.0.0-SNAPSHOT'
            ))->setHeader('accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(201, $status);
    }
    
    /**
     * @test
     */
    public function post_livrable_avecSha1_kp() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $file = $GLOBALS['resourcesDir'].'/test.zip';
        
        $status = null;
        $response = null;
        try {
            $response = $client->post('/livrable', array(), array(
                'myFile' => '@'.$file,
                'vendorName' => 'Huge',
                'projectName' => 'MonAppli',
                'version' => '1.0.0',
                'classifier' => 'dev',
                'sha1' => 'oo'
            ))->setHeader('accept', 'application/json')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(400, $status);
    }
    
    /**
     * @test
     */
    public function get_delete_ok() {
        $client = new GuzzleHttp\Client($GLOBALS['variables']['apache.integrationTest.baseUrl']);

        $status = null;
        $response = null;
        try {
            $response = $client->delete('/livrable/53e998b55768bc8f088b4567')->send();
            $status = $response->getStatusCode();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
        }

        $this->assertEquals(200, $status);
        $this->assertEquals('application/json', $response->getHeader('Content-Type'));
    }

    public static function tearDownAfterClass() {
        self::$MONGO->close();
    }
}

