<?php

namespace App\Controllers;

class CoreController 
{
    /**
     * Méthode constructeur :
     * Lancée lors de l'instanciation d'un Controller
     */
    public function __construct() 
    {
        //! NOUVELLE NOTION : ACL - Access Control List
        // 1 - On définit notre liste de permission : routes <----> rôles 
        // pour les routes nécessitant une connexion utilisateur
        $acl = [
            //'user-login' => [], //=> pas besoin, la route est libre d'accès
            'main-home' =>              ['admin', 'catalog-manager'],
            'user-list' =>              ['admin'],
            'user-add' =>               ['admin'],
            'user-add-post' =>          ['admin'],
            'category-list' =>          ['admin', 'catalog-manager'],
            'category-add' =>           ['admin', 'catalog-manager'],
            'category-add-post' =>      ['admin', 'catalog-manager'],
            'category-update' =>        ['admin', 'catalog-manager'],
            'category-update-post' =>   ['admin', 'catalog-manager'],
            'category-delete' =>        ['admin', 'catalog-manager'],
            'category-home-order' =>    ['admin', 'catalog-manager'],
            'category-home-order-post'=>['admin', 'catalog-manager'],
            'product-list' =>           ['admin', 'catalog-manager'],
            'product-add' =>            ['admin', 'catalog-manager'],
            'product-add-post' =>       ['admin', 'catalog-manager'],
            'product-update' =>         ['admin', 'catalog-manager'],
            'product-update-post' =>    ['admin', 'catalog-manager'],
            'product-delete' =>         ['admin', 'catalog-manager']
        ];

        // 2 - On recup la route courante pour la faire coïncider avec notre ACL
        // On veut, pour la route courante, recupérer la liste des rôles permits
        global $match;
        $routeName = $match['name'];
        // dd($match, $routeName, $acl[$routeName]);

        // 3 - On vérifit que la route demandée est présente dans notre ACL
        // On utilise array_key_exists() qui vérifie la présence 
        // d'une clé dans un tableau associatif
        // C'est un peu comme in_array mais avec les clés et pas les valeurs
        if (array_key_exists($routeName, $acl)) {
            // Alors on recup le tableau des rôles autorisés
            $authorizedRoles = $acl[$routeName];
            // dd($authorizedRoles);

            // On exécute checkAuthorization() ici 
            // au lieu de l'exécuter dans chacune des méthodes
            $this->checkAuthorization($authorizedRoles);
        }

        //! NOUVELLE NOTION : Token CSRF
        //* Plan d'action
        // 1 - On va mettre en place un token en session pour les routes à sécuriser
        //  On va definir la liste des routes à sécuriser : pour lesquelles on va créer un token
        //  Si la page demandée par l'utilisateur fait partie de cette liste, 
        //  on génère un token qu'on sauvegarde en SESSION
        //  On rajoute des input hidden sur nos formulaires 
        //  qui vont contenir la valeur du token qu'on vient de créer

        // 2 - On vérifit le token reçu en POST avec celui qu'on a sauvegardé en SESSION
        //  On va déclarer une liste de routes (POST) 
        //  pour lesquelles on doit vérifier que le token reçu est le bon
        //  Si la route actuelle nécessite une verif de token CSRF : 
        //  on compare le token reçu en POST avec celui sauvegaré en SESSION



        //* 1 - Mettre en place un token en session pour les routes à sécuriser
        // Liste des pages avec formulaire
        // Sur ces pages, on va rajouter un input hidden avec la valeur du token générée en Session plus bas
        $csrfTokenToCreate = [
            'user-login',
            'user-add',
            'category-add',
            'category-update',
            'category-delete',
            'category-home-order',
            'product-add',
            'product-update',
            'product-delete',
        ];

        // Si la page demandée par l'utilisateur fait partie de la liste du dessus
        if (in_array($routeName, $csrfTokenToCreate)) {
            // On est sur une url qui affiche un formulaire
            // Désormais, pour se prémunir d'une potentielle attaque de type CSRF
            // Il faut générer un token, et pour ça on choisit la logique qu'on veut
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }

        //* 2 - On vérifit le token reçu en POST avec celui en SESSION
        // Liste des routes en POST sur lequelles il faut vérifier le token
        $csrfTokenToCheckInPost = [
            'user-login-post',
            'user-add-post',
            'category-add-post',
            'category-update-post',
            'category-home-order-post',
            'product-add-post',
            'product-update-post',
        ];

        // Si la route actuelle nécessite la vérification d'un token anti-CSRF
        if (in_array($routeName, $csrfTokenToCheckInPost)) {
            // On récupère le token en POST
            $token = isset($_POST['token']) ? $_POST['token'] : '';

            // On récupère le token en SESSION
            $sessionToken = isset($_SESSION['token']) ? $_SESSION['token'] : '';

            // Si les 2 tokens sont différents OU que le token de formulaire est vide
            if ($token !== $sessionToken || empty($token)) {
                // Alors on affiche une 403, ou on annonce une erreur dans le formulaire puis on demande de le renvoyer
                http_response_code(403);
                $this->show('error/err403');
                exit;
            } else {
                // Si tout va bien
                // On supprime le token en session
                // Ainsi, on ne pourra pas soumettre plusieurs fois le même formulaire, 
                // ni réutiliser ce token
                unset($_SESSION['token']);
            }
        }
    }

    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewData Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewData = []) {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewData est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewData['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewData['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewData['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewData, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewData);

        // dump($viewData);

        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewData est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }

    /**
     * Méthode permettant de vérifier les permissions d'un utilisateur connécté
     *
     * @param array $roles
     * @return void
     */
    public function checkAuthorization($roles = [])
    {
        // Si l'utilisateur est connécté
        if (isset($_SESSION['userObject']) && isset($_SESSION['userId'])) {

            // Alors on le recup
            $user = $_SESSION['userObject'];

            // Et on recup son rôle
            $role = $user->getRole();

            // Si le rôle fait partie des rôles authorisés (fournis en paramètres)
            if (in_array($role, $roles) ) {
                // Alors on va retourner vrai
                return true;
            } else {
            // Sinon, l'utilisateur n'a pas la permission d'accéder à la page
                // => On envoie une erreur "403 Forbidden" avec http_response_code(403)
                http_response_code(403);
                // Puis on affiche l'erreur 403 
                $this->show('error/err403');
                // Enfin on arrete le script pour que la page demandée ne s'affiche pas 
                exit;
            }
        } else {
        // Sinon : l'utilisateur n'est pas connecté 
            // Alors on le redirige vers la page de connexion
            global $router;
            header('Location: ' . $router->generate('user-login'));
        }
        
    }
}
