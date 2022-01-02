# Atelier "update"

## Objectifs

- On veut pouvoir mettre à jour une catégorie via le backOffice
- On veut pouvoir mettre à jour un produit via le backOffice

## Analyse

- quelles sont les différences entre le formulaire d'ajout et le formulaire de mise à jour ?
- au niveau des requêtes SQL, de quelles données a-t-on besoin pour la mise à jour ? Et si on compare à l'ajout ?

<details><summary>Réponses</summary>

- quelles sont les _différences_ entre le **formulaire d'ajout** et le **formulaire de mise à jour** ?
  - visuellement, il n'y a aucune différence car les informations demandées sont exactement les mêmes
  - cependant, pour l'UX (expérience utilisateur), il est préférable de pré-remplir les champs (input, select, etc.) par leur valeur actuelle dans le cas d'une mise à jour
- au niveau des requêtes SQL, de _quelles données_ a-t-on besoin pour la **mise à jour** ? Et si on compare à l'**ajout** ?
  - à priori, on a besoin des mêmes données pour les requêtes `INSERT INTO` que `UPDATE`
  - sauf qu'on ne veut mettre à jour qu'un seul enregistrement dans la table
  - donc, on doit ajouter la mention de l'`id` de cet enregistrement dans la requête `UPDATE` => `WHERE id = :id`
  - c'est la seule donnée à ajouter

- **et pour récupérer l'`id`, 2 possibilités s'offrent à nous** :
  - soit _directement depuis l'URL_ si on gère une route contenant l'id de l'entité à modifier
  - soit en ajoutant un `<input type="hidden" name="id" value="xx">` dans le formulaire de mise à jour, afin de transmettre la donnée `id` en POST
  - :warning: la suite de l'énoncé utilisera l'`id` fourni dans l'URL

</details>

## Etapes

### #1 _View_ catégorie :lipstick:

- on peut choisir de réutiliser le template de l'ajout d'une catégorie
- ou bien de créer une seconde template (une copie), juste pour la mise à jour
- dans les 2 cas, il faudra ajouter l'élément suivant :
  - des attributs `value` sur les inputs pour les pré-remplir avec les données de l'objet `Category`

### #2 Récupérer les données envoyées en POST :bird:

- si le formulaire est le même que pour l'ajout
- alors les données sont les mêmes
- sauf qu'il faut, en plus, récupérer l'id de la catégorie

<details><summary>En clair</summary>

- tu peux copier coller le code de récupération des données du formulaire d'ajout
- pense juste à ajouter la récupération de l' `id` (qui est dans l'URL :wink:)
- celui-ci est récupérable en paramètre de la méthode du contrôleur, c'est AltoDisptacher qui nous le fournit
- rajoute donc un paramètre à la méthode du contrôleur et `dump` sa valeur pour vérifier que tu reçois bien l'`id`

</details>

### #3 Mettre à jour en Database :floppy_disk:

On dispose des données dans des variables.  
Pour mettre à jour en base de données, on va :  
\- utiliser la connexion (`PDO`)  
\- ~~avec la [méthode `exec()`](https://www.php.net/manual/fr/pdo.exec)~~ non car ce n'est pas sécurisé  
\- avec les méthodes [`prepare()`](https://www.php.net/manual/fr/pdo.prepare.php), [`bindValue()`](https://www.php.net/manual/fr/pdostatement.bindvalue.php) et [`execute()`](https://www.php.net/manual/fr/pdostatement.execute.php)  
\- utiliser le langage SQL (`UPDATE`, [doc sql.sh :eyes:](https://sql.sh/cours/update))  
\- (ne pas oublier le `wHERE id = :id` pour ne mettre à jour que l'enregistrement souhaité)  
\- déclarer et coder une méthode `update()` dans le _Model_ `Category`

:warning: Avec l'_Active Record_, le code se base sur le _Model_ pour modifier les données

```php
<?php

// [...]

// Pour mettre à jour en DB, je dois d'abord récupérer l'instance du Model correspondant pour l'id donné (ici Post pour la table post, pour l'id 4)
$post = Post::find(4);
// Puis je renseigne les valeurs pour chaque propriété correspondantes
$post->setTitle("First Post"); // title pour le champ du même nom
$post->setBody("This is the body of the first post"); // body pour le champ du même nom
// En dernier, j'appelle la méthode du Model permettant de mettre à jour en DB
$post->update();
```

Le _Model_ `Brand` contient déjà une méthode `update()` et peut servir de modèle (:wink:).  
:warning: Mais attention, cette méthode n'utilise pas la méthode `prepare()`, il faudra donc la modifier (:wink::wink:).

### #4 Rediriger vers le formulaire de mise à jour

- une fois la catégorie modifiée grâce à la requête SQL
- rediriger l'internaute vers la page de modification de cette même catégorie

### #5 Tout pareil pour produit :astonished:

Reprendre les étapes précédentes et les appliquer à la modification d'un produit.

:information_source: Pour pré-remplir un `<select>`, il faut ajouter un attribut `selected` sans valeur dans la balise `<option>` "sélectionnée" ([doc MDN](https://developer.mozilla.org/fr/docs/Web/HTML/Element/Option)).

## Bonus

Maintenant que la mise à jour de catégorie et de produit est fonctionnelle, on peut déplacer les cartes **Trello** et s'attaquer au code des méthodes `insert()`, `update()` et `delete()` sur tous les _Models_ :champagne:

[Enoncé détaillé du bonus](bonus.md)
