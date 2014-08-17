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

## Envoyer automatiquement votre livrable
 * curl -i http://hugerepo.fr/livrable/ -F file=@/var/www/pays_out.json -F vendorName="Huge" -F projectName="Toto" -F version="1.0.0" -H "Accept: application/json"


