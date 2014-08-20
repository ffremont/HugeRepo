HugeRepo
========

Application permettant de stocker des livrables via une API en REST. 
Vous pouvez organiser votre stockage en fonction de votre organisation, par exemple :
 * multi-instances
    * dev. mobile (slave): instance Huge/Repo avec sa base Mongo
    * dev. desktop (slave): instance Huge/Repo avec sa base Mongo
    * master : instance Huge/Repo avec sa base Mongo et pointe vers les 2 slaves
 * une instance
    * master : instance Huge/Repo avec sa base Mongo


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
* Répertoire log en écriture pour www-data
* src/main/resources/config.php
```php
return array(
    // nom de l'instance master, slave1, slave2 ...
    'instance.name' => 'master',
    'mongo.server' => 'mongodb://localhost:27018',
    'mongo.dbName' => 'hugeRepo',
    'debug' => false,
    'memcache.enable' => true,
    'memcache.host' => '127.0.0.1',
    'memcache.port' => 11211,
    // liste des slaves
    'slaves' => array(
    /* 'http://slave1.fr' */
    ),
    
    'log4phpConfig' => array(
        'rootLogger' => array(
            'level' => 'WARN',
            'appenders' => array('default'),
        ),
        'appenders' => array(
            'default' => array(
                'class' => 'LoggerAppenderFile',
                'layout' => array(
                    'class' => 'LoggerLayoutPattern',
                    'params' => array(
                        'conversionPattern' => '%date{Y-m-d H:i} - %logger %-5level : %msg%n%ex'
                    )
                ),
                'params' => array(
                    'file' => __DIR__.'/../../../log/repo.log',
                    'append' => true
                )
            )
        )
    )
);
```

## Ordonner votre stockage
 * Possibilité d'installer plusieurs dépôts de livrables
    * master 
        * Redispatch la récupération des livrables (GET du livrable)
    * slaves
 * Chaque instance est autonome (sa base mongo)
 * La seule différence entre master/slave, c'est que le dépôt "master" permet de récupérer un livrable sur un slave
 * Pour connaître l'instance utilisée : X-Powered-By: NOM_INSTANCE
 * Votre gestionnaire de déploiement se base UNIQUEMENT sur le master

## Ajouter un livrable
 * Path : /livrable
 * Méthode : POST
 * Paramètres : attributs du livrable
 * Accept : application/json
 * Description
    * Ajout d'un livrable sur l'instance
 
## Télécharger un livrable
 * Path : /livrable/{ID} ou /livrable/{vendorName}/{projectName}/{version}/{classifier}
 * Méthode : GET
 * Description
    * Télécharge un livrable depuis l'instance courante ou depuis une autre instance

## Rechercher
 * Path : /livrable/search
 * Méthode : GET
 * Paramètres en GET : vendorName, projectName, version, classifier
 * Description
    * Recherche * livrables sur l'instance courante
 
## Supprimer
 * Path : /livrable/{ID}
 * Méthode : DELETE
 * Description
    * Supprime un livrable sur l'instance courante

## Authentification
 * Délégué à apache2

## Limitations
 * Mongo pour le stockage
 * Non prise en charge de l'authentification

## Envoyer automatiquement votre livrable
 * curl -i http://hugerepo.fr/livrable/ -F file=@/var/www/pays_out.json -F vendorName="Huge" -F projectName="Toto" -F version="1.0.0" -H "Accept: application/json"

## Télécharger
 * wget http://hugerepo.test.fr/livrable/53e998f75768bca60d9b4567
 
## Tests
 * VirtualHost sur le répertoire /src/test/webapp
 * Variabiliser /src/test/resources/variables.ini
 * phpunit -c src/test/resources/phpunit.xml --testsuite IT


