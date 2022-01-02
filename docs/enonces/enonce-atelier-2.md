# Atelier "Ajout de données"

## Objectifs

- On veut pouvoir ajouter une catégorie via le backOffice.
- On veut pouvoir ajouter un produit via le backOffice.

## Étapes

### #1 Récupérer les données envoyées en POST :bird:

Le formulaire d'ajout d'une catégorie est déjà en place.  
Après soumission du formulaire, les données sont bien envoyées par le navigateur (ou pas... il manque peut-être un petit truc dans le code HTML... :smiling_imp:).  
Mais, pour l'instant, notre code n'en fait rien :cry:

- créer la route nécessaire, de type POST et ayant la même URL que la route en GET déjà existante
- dans cette nouvelle route, spécifier une nouvelle méthode de _Controller_ (ex: `create`)
- définir cette méthode dans le _Controller_ correspondant
- pour chaque donnée reçue du formulaire :
  - récupérer la donnée dans une variable
    - _dans cette méthode, les données du formulaire sont accessibles dans le tableau `$_POST`_
    - _un petit `dump()` peut aider à se représenter le contenu du tableau :wink:_
  - s'assurer que la clé du tableau `$_POST` existe avant de récupérer la donnée
- une fois les données correctement récupérées, on peut passer à l'étape suivante :tada:

<details><summary>Astuce</summary>

Si vous ne la connaissez pas déjà, la [fonction `filter_input`](https://www.php.net/manual/fr/function.filter-input.php) de PHP peut être très utile pour récupérer simplement les données transportées en GET ou en POST.

**Exemple avec une donnée `name` envoyée en GET**

```php
<?php
// [...]

$name = filter_input(INPUT_GET, 'name');
```

**Exemple avec une donnée `name` envoyée en POST**

```php
<?php
// [...]

$name = filter_input(INPUT_POST, 'name');
```

Et avec un peu d'expérience et de curiosité, vous pourrez même ajouter un « filtre personnalisé » pour vous assurer de récupérer, par exemple, un int, un float, un email ou une URL :ok_hand:
  
</details>

### #2 Ajouter en Database :floppy_disk:

Dans la méthode du _Controller_, on dispose désormais de variables contenat les données venant du formulaire.
Pour les ajouter en base de données (BDD), on va :  
\- utiliser la connexion à la BDD (`PDO`, [méthode `exec()`](https://www.php.net/manual/fr/pdo.exec))  
\- utiliser le langage SQL (`INSERT INTO`, [doc sql.sh :eyes:](https://sql.sh/cours/insert-into))  
\- déclarer et coder une méthode `insert()` dans le _Model_ `Category` (elle n'est pas `static`, car elle utilise l'objet courant `$this`)

:warning: Rappel : avec l'approche _Active Record_, notre code se base sur un _Model_ pour insérer les données en BDD.

```php
<?php

// [...]

// Pour insérer en DB, je crée d'abord une nouvelle instance du Model correspondant
// (ici Post pour la table post).
$post = new Post();

// Puis je renseigne les valeurs pour chaque propriété correspondantes dans l'instance.
$post->setTitle("First Post"); // title pour le champ du même nom
$post->setBody("This is the body of the first post"); // body pour le champ du même nom

// En dernier, j'appelle la méthode du Model permettant d'ajouter en DB.
$post->insert();
```

Le _Model_ `Brand` contient déjà une méthode `insert()` et peut servir de modèle (:wink:)

### #3 Ajout d'un produit

- faire de même avec l'ajout de produit
- le processus est exactement le même que pour une catégorie !
- cependant le formulaire, et donc le _Model_, contiennent plus de données

### #4 Rediriger sur la page liste

- une fois l'ajout effectué sans erreur…
- … rediriger l'internaute vers la page liste (fonction `header()` :wink:)

## Bonus

Maintenant que l'ajout de catégorie et de produit est fonctionnel, on peut déplacer les cartes **Trello** et s'attaquer à la mise à jour des catégories et des produits :champagne:

[Enoncé détaillé du bonus](bonus.md)