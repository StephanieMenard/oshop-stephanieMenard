<?php

namespace App\Controllers;

// Si j'ai besoin de mes Models
use App\Models\Product;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Category;


class ProductController extends CoreController
{
    /**
     * Méthode qui va se charger d'afficher la liste des produits
     *
     * @return void
     */
    public function list()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        //! Nouvelle notion : méthode statique
        //* Avant on faisait comme ceci pour appeler des méthodes
        //* On créait un nouvel objet pour pouvoir appeler une de ses méthodes
        // $product = new Product();
        // $products = $product->findAll();

        //* Maintenant, grâce au méthodes statiques, je n'ai plus besoin de créer un objet
        //* C'est très utile pour le méthode qui n'ont pas besoin de faire référence à un objet
        //* Typiquement, les méthodes 'finder'
        $products = Product::findAll();

        // dump($products);

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'product/list',
            [
                'products' => $products
            ]
        );
    }

    /**
     * Méthode qui va se charger d'afficher 
     * la page avec le formulaire d'ajout de produit
     *
     * @return void
     */
    public function add()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        $this->show(
            'product/form',
            [
                'product' => new Product(),
                'brands' => Brand::findAll(),
                'types' => Type::findAll(),
                'categories' => Category::findAll(),
            ]
        );
    }

    /**
     * Méthode pour insertion d'un nouveau produit 
     * dans la table product
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
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        $price = trim(filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT));
        $status = trim(filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT));
        $rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_NUMBER_INT);
        $brand_id = trim(filter_input(INPUT_POST, 'brand_id', FILTER_SANITIZE_NUMBER_INT));
        $category_id = trim(filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT));
        $type_id = trim(filter_input(INPUT_POST, 'type_id', FILTER_SANITIZE_NUMBER_INT));

        // dd($status);


        //* Gestion des erreurs
        // Je prépare un tableau qui va contenir tous les messages d'erreurs
        $errorList = [];

        // Si l'utilisateur n'a pas renseigné un nom de categories
        // Ou si le nom n'est pas valide ( : qu'il ne passe pas le filtre de validation)
        // On veut, a terme, lui afficher le message d'erreur qui va bien
        if (empty($name) || $name === false) {
            $errorList[] = 'Veuillez renseigner un nom de produit valide';
        }
        // Idem pour le sous-titre
        if (empty($description) || $description === false) {
            $errorList[] = 'Veuillez renseigner une description valide';
        }
        // Idem pour l'url d'image
        if (empty($picture) || $picture === false) {
            $errorList[] = 'Veuillez renseigner une URL d\'image valide';
        }
        // etc...
        if (empty($price) || $price === false) {
            $errorList[] = 'Veuillez renseigner prix valide';
        }
        if (empty($status) || $status === false) {
            $errorList[] = 'Veuillez renseigner un état valide';
        }
        if (empty($rate) || $rate === false) {
            $errorList[] = 'Veuillez renseigner une note valide';
        }
        if (empty($brand_id) || $brand_id === false) {
            $errorList[] = 'Veuillez renseigner une marque valide';
        }
        if (empty($category_id) || $category_id === false) {
            $errorList[] = 'Veuillez renseigner une categorie valide';
        }
        if (empty($type_id) || $type_id === false) {
            $errorList[] = 'Veuillez renseigner un type valide';
        }

        // Si il y a des erreurs, je veux pouvoir les afficher à l'utilisateur 
        if (!empty($errorList)) {
            // dump($errorList);

            // Affichage des erreurs de manière plus sexy
            $this->show(
                'product/form',
                [
                    'errorsList' => $errorList
                ]
            );
        } else {
            // Si il n'y a pas d'erreurs, je veux pouvoir enregistrer une nouvelle entrée en DB

            // Pour ajouter en BD :
            // 0 - Code une méthode insert() dans la classe (Model) Product

            // 1 - Créer un nouvel objet Product
            $product = new Product();

            // 2 - Remplir les propriétés de l'objet 
            $product->setName($name);
            $product->setDescription($description);
            $product->setPicture($picture);
            $product->setPrice($price);
            $product->setRate($rate);
            $product->setStatus($status);
            $product->setBrandId($brand_id);
            $product->setCategoryId($category_id);
            $product->setTypeId($type_id);

            // 3 - Executer la méthode insert() pour rajouter en DB
            $ok = $product->save();


            // Si l'enregistrement en DB est OK
            if ($ok) {
                // 4 - Je redirige l'utilisateur sur le listing des produits
                global $router;
                header('Location: ' . $router->generate('product-list'));
                // J'arrete le fonctionnement du script ici pour pas avoir de soucis 
                exit;
            } else {
                // Si l'enregistrement ne se passe pas bien,
                // 4bis - Je rajoute un message d'erreur au tableau et je l'affiche 
                $errorList[] = 'Problème lors de l\'insertion en base de données';
                // dump($errorList);

                // Affichage des erreurs de manière plus sexy
                $this->show(
                    'product/form',
                    [
                        'errorsList' => $errorList
                    ]
                );
            }
        }
    }

    /**
     * Méthode qui va se charger d'afficher
     * la page avec le formulaire de mise à jour de produit
     *
     * @return void
     */
    public function update($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // On sait qu'on doit recup l'id du produit à modifier
        // dump($id);

        // Grâce auquel on va recup les donnée en DB
        $product = Product::find($id);
        // dump($product);

        // Qu'on va pouvoir envoyer au template pour préremplir les champs du formulaire
        $this->show(
            'product/form',
            [
                'product' => $product,
                'brands' => Brand::findAll(),
                'types' => Type::findAll(),
                'categories' => Category::findAll(),
            ]
        );
    }

    /**
     * Méthode pour update un produit 
     * dans la table product
     *
     * @return void
     */
    public function updatePost($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // On a toujours l'id du produit qui vient de l'url
        // Du coup on peut recup l'objet Product à modifier
        $product = Product::find($id);

        // dump($product);

        // On va recup, comme pour addPost(), les valeurs du formulaire
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        $price = trim(filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT));
        $status = trim(filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT));
        $rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_NUMBER_INT);
        $brand_id = trim(filter_input(INPUT_POST, 'brand_id', FILTER_SANITIZE_NUMBER_INT));
        $category_id = trim(filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT));
        $type_id = trim(filter_input(INPUT_POST, 'type_id', FILTER_SANITIZE_NUMBER_INT));

        // On va pouvoir mettre à jour notre objet product grâce à ces valeurs
        $product->setName($name);
        $product->setDescription($description);
        $product->setPicture($picture);
        $product->setPrice($price);
        $product->setStatus($status);
        $product->setRate($rate);
        $product->setBrandId($brand_id);
        $product->setCategoryId($category_id);
        $product->setTypeId($type_id);

        // dump($product);

        // On veut mettre à jour notre product 
        // en executant la méthode update() de la classe Product
        $ok = $product->save();

        // Si l'update se passe bien (la méthode update() nous renvoie true)
        if ($ok) {
            // Alors on redirige l'utilisateur vers le formulaire de modif
            // pour le product à l'id renseigné
            global $router;
            header('Location: ' . $router->generate('product-update', ['id' => $id]));
        }
    }
}
