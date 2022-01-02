# plan pour l'atelier de connexion

## 1 - Création de la table app_user

Avec le script SQL docs/app_user.sql

## 2 - Déclaration du Model AppUser

Avec les propriétées correspondantes aux champs de la table app_user & les méthodes qu'il faut

## 3 - Une page de connexion

Avec un formulaire simple : 2 inputs et 1 bouton submit

## 4 - Vérification si user est valide

Il va falloir vérifier en base de données si l'email est correct, puis si le mot de passe est correct !  
Si oui : on affiche OK & on save des données en SESSION (cf. Bonus)  
Sinon : on affiche PAS OK