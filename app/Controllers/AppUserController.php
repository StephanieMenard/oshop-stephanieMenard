<?php

namespace App\Controllers;

// Si j'ai besoin du Model AppUser
use App\Models\AppUser;

class AppUserController extends CoreController
{

    /**
     * Page de formualire d'ajout d'un utilisateur
     * 
     * @return void
     */
    public function add()
    {
        // On restreint l'acces au formulaire d'ajout utilisateur aux admins
        // $this->checkAuthorization(['admin']);

        $this->show(
            'app_user/form', 
            [
                // On envoie un nouvel objet AppUser, ses propriétes valent toutes null
                'user' => new AppUser(),
            ]
        );
    }

    public function addPost()
    {
        // On restreint l'acces au formulaire d'ajout utilisateur aux admins
        // $this->checkAuthorization(['admin']);

        // Récupération des données du formulaire
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);


        // Gestion des erreurs
        $errorList = [];
      
        if (empty($firstname) || $firstname === false) {
            $errorList[] = 'Veuillez renseigner votre prénom';
        }
        // Idem pour le sous-titre
        if (empty($lastname) || $lastname === false) {
            $errorList[] = 'Veuillez renseigner votre nom';
        }
        // Idem pour l'url d'image
        if (empty($email) || $email === false) {
            $errorList[] = 'Veuillez renseigner un email valide';
        }
        if (empty($password) || $password === false) {
            $errorList[] = 'Veuillez renseigner un MDP valide';
        }
        if (empty($role) || $role === false) {
            $errorList[] = 'Veuillez selectionner un role';
        }
        if (empty($status) || $status === false) {
            $errorList[] = 'Veuillez selectionner un statut';
        }


        // Si on a aucunes erreurs on peut procéder à l'enregistrement en DB
        if (empty($errorList)) {
         
            // On crée un nouvel objet AppUser
            $user = new AppUser();

            // On hash le mot de passe
            $password = password_hash($password, PASSWORD_ARGON2ID);

            // On affecte les valeurs du formulaire aux propriétés de l'objet
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setRole($role);
            $user->setStatus($status);


            // On tente d'enregistrer en DB
            if ($user->save()) {
                
                // Si l'enregistrement en DB se passe bien
                // On redirige sur la liste des utilisateurs
                global $router;
                header('Location: ' . $router->generate('user-list'));
                exit;

            } else {
                // Sinon on ajoute un message d'erreur 
                $errorList[] = 'Utilisateur non enregistrée';

                // On demande à générer une vue avec le même template auquel on envoie un $appUser prérempli, même si les données sont erronnées
                $this->show('app_user/form', [
                    'user' => $user,
                    'errorsList' => $errorList,
                ]);
            }

        } else {
            // Si on a une ou plusieurs erreurs
            // On demande à générer une vue avec le même template 
            // auquel on envoie un $user vide, même si les données sont erronnées
            $this->show('app_user/form', [
                'user' => new AppUser(),
                'errorsList' => $errorList,
            ]);
        }
    }

    /**
     * Méthode qui va afficher le listing des utilisateurs
     *
     * @return void
     */
    public function list()
    {
        // On veut restreindre l'accès au listing ustilisateurs au admins
        // $this->checkAuthorization(['admin']);

        // On récupère tous les utilisateurs
        $users = AppUser::findAll();

        // On les envoie à la vue
        $this->show(
            'app_user/list',
            [
                "users" => $users
            ]
        );
    }

    /**
     * Méthode qui va se charger d'afficher 
     * le formulaire de connexion
     *
     * @return void
     */
    public function login()
    {
        $this->show('app_user/login');
    }

    /**
     * Méthode qui va vérif les informations de connexion
     * et connecter l'utilisateur au Back-Office
     */
    public function loginPost()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        // dump($email, $password);

        // Méthode qui permet de recup une instance de la class AppUser 
        // Si l'email renseigné est présent dans la table app_user
        // Nous renvoie false, si aucune entrée n'est trouvé depuis la table app_user 
        $user = AppUser::findByEmail($email);
        // dump($user);

        // Avant de vérifier le mot de passe, il faut s'assurer qu'on a bien trouvé un email valide. C'est a dire que $user contient un objet de type AppUser
        // instanceof est un opérateur de comparaison, comme un == ou un !=
        // Il retourne true ou false selon si l'objet à sa gauche est bien une instance de la classe à sa droite
        if ($user instanceof AppUser) {
            // On vérif que le MDP en DB corresponde à celui entrée dans le formulaire
            // On utilise désormais des mots de passe hashé en BDD. On les obtient avec password_hash('mot de passe', PASSWORD_ARGON2ID)
            // On les vérifie avec password_verify(). Cette fonction s'occupe de faire les calculs nécessaire pour nous dire si le mot de passe est le bon ou non
            //* https://www.php.net/password_verify
            //* https://www.php.net/manual/fr/function.password-hash.php
            if (password_verify($password, $user->getPassword())) {
                // Si c'est le cas, on affiche OK
                // echo 'OK !';
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userObject'] = $user;

                // dump($_SESSION['userId'], $_SESSION['userObject']);

                // Comme l'utilisateur est connecte
                // on le redirige vers la page d'accueil du Back Office
                global $router;
                header('Location: ' . $router->generate('main-home'));
            } else {
                // Autrement, on affiche PAS OK
                echo 'PAS OK !';
            }
        } else {
            echo 'Utilisateur introuvable';
        }
    }

    /**
     * Méthode pour déconnecter l'utilisateur
     * On va supprmier les variables de session
     * qui permettent de garder l'état connécté de l'utilisateur
     *
     * @return void
     */
    public function logout()
    {
        // On considère qu'un utilisateur est connecté simplement avec la présence ou l'absence
        // des clés userId et userObject dans $_SESSION
        // Pour déconnecter l'utilisateur, on va donc supprimer ces informations
        // unset() est une fonction de PHP qui détruit une variable
        //      On peut détruire une seule entrée dans un tableau ou tout une variable
        unset($_SESSION['userId']);
        unset($_SESSION['userObject']);

        // Comme l'utilisateur n'est plus identifiable et qu'on est sur un Back Office
        // on le redirige vers la route /login
        global $router;
        header('Location: ' . $router->generate('user-login'));
    }
}
