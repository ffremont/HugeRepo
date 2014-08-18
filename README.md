HugeRepo
========

Application permettant de stocker des livrables via une API en REST.


##Installation
Installer avec composer
``` json
    {
        "require": {
           "huge/repo": "..."
        }
    }
```

## Fonctionnalités
 * Api REST 
    * GET : récupérer le(s) livrables
    * POST : ajout le livrable
    * DELETE : supprimer le livrable
 * Stockage souple grâce à Mongo
 
## Livrable
  * vendorName (obligatoire): Nom du vendor
  * projectName (obligatoire): Nom du projet
  * version (obligatoire): X.Y.Z
  * classifier (facultatif): déclinaison de votre livrable (dev, prod, ...)
  * sha1 (facultatif) : permet de réaliser une vérification côté serveur

## Configuration
* VirtualHost sur src/main/webapp
* Module rewrite obligatoire
* src/main/resources/config.php
```php
return array(
    'mongo.server' => 'mongodb://localhost:27018',
    'mongo.dbName' => 'hugeRepo',
    
    'memcache.enable' => true,
    'memcache.host' => '127.0.0.1',
    'memcache.port' => 11211
);
```
## Ajouter un livrable
 * Path : /livrable
 * Méthode : POST
 * Paramètres : attributs du livrable
 * Accept : application/json
 
## Télécharger un livrable
 * Path : /livrable/{ID}
 * Méthode : GET

## Rechercher
 * Path : /livrable/search
 * Méthode : GET
 * Paramètres en GET : vendorName, projectName, version, classifier
 
## Supprimer
 * Path : /livrable/{ID}
 * Méthode : DELETE
 
## Limitations
 * Mongo pour le stockage
 * Interconnexion avec d'autres Huge\Repo non géré (pour l'instant)

## Envoyer automatiquement votre livrable
 * curl -i http://hugerepo.fr/livrable/ -F file=@/var/www/pays_out.json -F vendorName="Huge" -F projectName="Toto" -F version="1.0.0" -H "Accept: application/json"

## Télécharger
 * wget http://hugerepo.test.fr/livrable/53e998f75768bca60d9b4567
 
## Tests
 * VirtualHost sur le répertoire /src/test/webapp
 * Variabiliser /src/test/resources/variables.ini
 * phpunit -c src/test/resources/phpunit.xml --testsuite IT


