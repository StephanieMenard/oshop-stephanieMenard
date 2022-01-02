# Atelier "Connexion"

## Objectifs

On veut pouvoir connecter un utilisateur au backOffice

## Analyse

- le formulaire de connexion n'est qu'un formulaire comme un autre, avec 2 champs `<input>`
- les données envoyées sont en POST (plus sécurisé que GET)
- la liste des utilisateurs autorisés est dans la table `app_user` qu'on vient de créer

## Etapes

### #1 _Model_ `AppUser` :floppy_disk:

- on vient de créer une nouvelle table
- mais on n'a pas encore le _Model_ correspondant
- et ce _Model_ sera nécessaire pour la suite
- :warning: Désormais, pour chaque _Model_, on est obligé de coder toutes les méthodes du CRUD :
  - `insert()` pour Create
  - `find()` & `findAll()` pour Read
  - `update()` pour Update
  - `delete()` pour Delete

### #2 Page de connexion (GET)

- mettre en place la route si ce n'est pas déjà fait
- faire de même pour le _Controller_, la méthode de _Controller_ et la _View_ pour cette page
- afficher le formulaire de connexion
  - 2 inputs : Email et Password
  - 1 button : Se connecter

### #3 Récupération des données (POST)

- on commence à y être habitué
- récupérer les données email et password du formulaire

### #4 Vérifier les accès

- une fois l'email récupéré
- on va pouvoir chercher s'il y a un enregistrement dans la table `app_user` pour l'email fourni
- actuellement, aucune méthode du _Model_ `AppUser` ne permet une recherche par email
- coder la méthode statique `findByEmail($email)`
  - si on trouve un résultat pour l'email, retourner une instance de `AppUser`
  - sinon, retourner `false`
- utiliser cette méthode
- une fois l'objet récupéré, comparer le password fourni et le password de l'objet
  - s'ils sont égaux => :tada: on affiche "ok !!!"
- si le mot de passe est incorrect ou l'email inconnu, afficher un message d'erreur simple avec `echo`
  - on améliorera l'affichage des erreurs en _bonus_

## Bonus

<details><summary>C'est déjà bien d'avoir réussi à authentifier un utilisateur...</summary>

Et dans le [bonus suivant](bonus.md), on veut pouvoir "connecter" l'utilisateur :champagne:

</details>
