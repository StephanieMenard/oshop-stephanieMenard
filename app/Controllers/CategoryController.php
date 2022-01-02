<?php

namespace App\Controllers;

// Si j'ai besoin du Model Category
use App\Models\Category;

class CategoryController extends CoreController
{
    /**
     * Méthode qui va se charger d'afficher la liste des categories
     *
     * @return void
     */
    public function list()
    {
        // dump($_SESSION['userId'], $_SESSION['userObject']);

        //! NOUVELLE NOTION : Permissions
        // Pour pouvoir accèder au listing des catégories, il faut que l'utilisateur 
        // soit connécté et qu'il ait un rôle présent parmis le tableau envoyé en argument 
        // => ['admin', 'catalog-manager']
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        //! Nouvelle notion : méthode statique
        //* Avant on faisait comme ceci pour appeler des méthodes
        //* On créait un nouvel objet pour pouvoir appeler une de ses méthodes
        // $category = new Category();
        // $categories = $category->findAll();

        //* Maintenant, grâce au méthodes statiques, je n'ai plus besoin de créer un objet
        //* C'est très utile pour le méthode qui n'ont pas besoin de faire référence à un objet
        //* Typiquement, les méthodes 'finder'
        $categories = Category::findAll();

        // dump($categories);

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'category/list',
            [
                'categories' => $categories
            ]
        );
    }

    /**
     * Méthode qui va se charger d'afficher 
     * la page avec le formulaire d'ajout de catégorie
     *
     * @return void
     */
    public function add()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // dump($_SESSION['userId'], $_SESSION['userObject']);

        // On utilise un seul et même TPL qui contient le formulaire d'ajout/MaJ de categorie
        // La seul différence, c'est qu'on lui envoie des données différentes (ici une Category vide)
        $this->show('category/form', 
            [
                // On envoie un nouvel objet Category vide au TPL pour ne pas avoir d'erreur
                // Ses propriétées sont égales à null, 
                // du coup les attributs value seront vides
                // Ca n'empechera pas le bon fonctionnement du code !
                'category' => new Category() 
            ]
        );
    }

    /**
     * Méthode pour insertion d'une nouvelle categorie 
     * dans la table category
     *
     * @return void
     */
    public function addPost()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        //* La fonction filter_input permet de recup facilement des données 
        //* transportées en GET ou en POST. On vient, à la voléee, appliquer un filtre 
        //* à notre données.
        // https://www.php.net/manual/fr/function.filter-input.php
        // https://www.php.net/manual/fr/filter.filters.sanitize.php
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        // dump($_POST);
        // dump($name, $subtitle, $picture);


        //* Gestion des erreurs
        // Je prépare un tableau qui va contenir tous les messages d'erreurs
        $errorList = [];

        // Si l'utilisateur n'a pas renseigné un nom de categories
        // Ou si le nom n'est pas valide ( : qu'il ne passe pas le filtre de validation)
        // On veut, a terme, lui afficher le message d'erreur qui va bien
        if (empty($name) || $name === false) {
            $errorList[] = 'Veuillez renseigner un nom de catégorie valide';
        }
        // Idem pour le sous-titre
        if (empty($subtitle) || $subtitle === false) {
            $errorList[] = 'Veuillez renseigner un sous-titre valide';
        }
        // Idem pour l'url d'image
        if (empty($picture) || $picture === false) {
            $errorList[] = 'Veuillez renseigner une URL d\'image valide';
        }

        // Si il y a des erreurs, je veux pouvoir les afficher à l'utilisateur 
        if (!empty($errorList)) {
            // dump($errorList);
            // Affichage des erreurs de manière plus sexy
            $this->show(
                'category/form',
                [
                    'errorsList' => $errorList
                ]
            );
        } else {
            // Si il n'y a pas d'erreurs, je veux pouvoir enregistrer une nouvelle entrée en DB

            // Pour ajouter en BD :
            // 0 - Code une méthode insert() dans la classe (Model) Category

            // 1 - Créer un nouvel objet Category
            $category = new Category();

            // 2 - Remplir les propriétés de l'objet 
            $category->setName($name);
            $category->setSubtitle($subtitle);
            $category->setPicture($picture);

            // 3 - Executer la méthode insert() pour rajouter en DB
            $ok = $category->save();


            // Si l'enregistrement en DB est OK
            if ($ok) {
                // 4 - Je redirige l'utilisateur sur le listing des categories
                global $router;
                header('Location: ' . $router->generate('category-list'));
                // J'arrete le fonctionnement du script ici pour pas avoir de soucis 
                exit;
            } else {
                // Si l'enregistrement ne se passe pas bien,
                // 4bis - Je rajoute un message d'erreur au tableau et je l'affiche 
                $errorList[] = 'Problème lors de l\'insertion en base de données';
                // dump($errorList);
                // Affichage des erreurs de manière plus sexy
                $this->show(
                    'category/form',
                    [
                        'errorsList' => $errorList
                    ]
                );
            }
        }
    }

    /**
     * Méthode qui va se charger d'afficher
     * la page avec le formulaire de mise à jour de categorie
     *
     * @return void
     */
    public function update($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // On sait qu'on doit recup l'id de la catégorie à modifier
        // dump($id);

        // Grâce auquel on va recup les donnée en DB de de la Categorie
        $category = Category::find($id);
        // dump($category);

        // Qu'on va pouvoir envoyer au template pour préremplir les champs du formulaire
        $this->show(
            'category/form',
            [
                'category' => $category
            ]
        );
    }

    /**
     * Méthode pour update une categorie 
     * dans la table category
     *
     * @return void
     */
    public function updatePost($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // On a toujours l'id de la categorie qui vient de l'url
        // Du coup on peut recup l'objet Category à modifier
        $category = Category::find($id);

        // dump($category);

        // On va recup, comme pour addPost(), les valeurs du formulaire
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);

        // On va pouvoir mettre à jour notre objet Category grâce à ces valeurs
        $category->setName($name);
        $category->setSubtitle($subtitle);
        $category->setPicture($picture);

        // dump($category);

        // On veut mettre à jour notre Category 
        // en executant la méthode update() de la classe Category
        $ok = $category->save();

        // Si l'update se passe bien (la méthode update() nous renvoie true)
        if ($ok) {
            // Alors on redirige l'utilisateur vers le formulaire de modif
            // pour la category à l'id renseigné
            global $router;
            header('Location: ' . $router->generate('category-update', ['id' => $id] ));
        }
    }

    /**
     * Méthode pour update une categorie 
     * dans la table category
     *
     * @return void
     */
    public function delete($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // On a toujours l'id de la categorie qui vient de l'url
        // Du coup on peut recup l'objet Category à modifier
        $category = Category::find($id);

        // dump($category)

        // On veut supprimer notre Category 
        // en executant la méthode delete() de la classe Category
        $ok = $category->delete();

        // Si l'update se passe bien (la méthode update() nous renvoie true)
        if ($ok) {
            // Alors on redirige l'utilisateur vers le formulaire de modif
            // pour la category à l'id renseigné
            global $router;
            header('Location: ' . $router->generate('category-list'));
        }
    }

    /**
     * Méthode affichant le formulaire de selection d'emplacement
     * des categories en home page
     *
     * @return void
     */
    public function homeOrder()
    {
        $categories = Category::findAll();

        $this->show('category/home_order', 
            [
                'categories' => $categories
            ]
        );
    }

    /**
     * Méthode de MaJ des home_order dans la table category
     *
     * @return void
     */
    public function homeOrderPost()
    {
        // Dans les <select> du template, tous les attributs name valent «emplacement[]»
        // Ça va donner une indication à PHP de créer une clé `emplacement` dans $_POST.
        // La valeur de $_POST['emplacement'] est un tableau indexé dont chacune des valeurs est l'id
        // de chacune des catégories sélectionnées dans le formulaire
        // dump($_POST);
        
        // L'option FILTER_REQUIRE_ARRAY permet de renseigner que la variable à filtrer est un tableau
        $ids = filter_input(INPUT_POST, 'emplacement', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        // dump($ids);

        // On a créé une méthode statique dans Category qui définit les home_order en BDD
        // On lui envoie la liste des id sélectionnés depuis le formulaire
        // Cette liste est un simple tableau indexé du genre : [5, 7, 12, 3, 19]
        // Ce tableau contient les id des catégories
        if (Category::updateHomeOrder($ids)) {
            // On redirige vers la liste des catégories
            global $router;
            header('Location: ' . $router->generate('category-home-order'));
        }
    }
}
