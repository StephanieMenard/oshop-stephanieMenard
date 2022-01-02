<?php

// POINT D'ENTRÉE UNIQUE : 
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)
require_once '../vendor/autoload.php';

//! NOUVELLE NOTION : Session
//* On vient ouvrir notre session au début du script 
//* Pour en profiter sur toutes nos pages / fichiers
session_start();

/* ------------
--- ROUTAGE ---
-------------*/


// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va 
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
    // Alors on définit le basePath d'AltoRouter
    $router->setBasePath($_SERVER['BASE_URI']);
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
}
// sinon
else {
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter, afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"
$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => '\App\Controllers\MainController'
    ],
    'main-home'
);

//* =========
//* CATEGORIE
//* =========
// Liste des catégories
$router->map(
    'GET',
    '/category/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-list'
);

// Page du formulaire d'ajout de catégorie
$router->map(
    'GET',
    '/category/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-add'
);

// Création d'une catégorie en DB
$router->map(
    'POST',
    '/category/add',
    [
        'method' => 'addPost',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-add-post'
);

// Page du formulaire de mise à jour de catégorie
$router->map(
    'GET',
    '/category/update/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-update'
);

// MaJ d'une catégorie en DB
$router->map(
    'POST',
    '/category/update/[i:id]',
    [
        'method' => 'updatePost',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-update-post'
);

// MaJ d'une catégorie en DB
$router->map(
    'GET',
    '/category/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-delete'
);

// Page formulaire home categories
$router->map(
    'GET',
    '/category/home-order',
    [
        'method' => 'homeOrder',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-home-order'
);

// MaJ emplacaements home categories
$router->map(
    'POST',
    '/category/home-order',
    [
        'method' => 'homeOrderPost',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-home-order-post'
);


//* =========
//* PRODUIT
//* =========
// Liste des produits
$router->map(
    'GET',
    '/product/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-list'
);

// Page du formulaire d'ajout de produit
$router->map(
    'GET',
    '/product/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-add'
);

// Création d'un produit en DB
$router->map(
    'POST',
    '/product/add',
    [
        'method' => 'addPost',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-add-post'
);

// Page du formulaire de mise à jour d'un produit
$router->map(
    'GET',
    '/product/update/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-update'
);

// MaJ d'un produit en DB
$router->map(
    'POST',
    '/product/update/[i:id]',
    [
        'method' => 'updatePost',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-update-post'
);

//* =========
//* USERS
//* =========
// Page de connexion au Back-Office
$router->map(
    'GET',
    '/user/login',
    [
        'method' => 'login',
        'controller' => '\App\Controllers\AppUserController'
    ],
    'user-login'
);

// Connexion au Back-Office
$router->map(
    'POST',
    '/user/login',
    [
        'method' => 'loginPost',
        'controller' => '\App\Controllers\AppUserController'
    ],
    'user-login-post'
);

// Déconnexion du Back-Office
$router->map(
    'GET',
    '/user/logout',
    [
        'method' => 'logout',
        'controller' => '\App\Controllers\AppUserController'
    ],
    'user-logout'
);

// Page liste utilisateurs
$router->map(
    'GET',
    '/user/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\AppUserController'
    ],
    'user-list'
);

// Page du formulaire d'ajout d'utilisateur
$router->map(
    'GET',
    '/user/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\AppUserController'
    ],
    'user-add'
);

// Création d'un user en DB
$router->map(
    'POST',
    '/user/add',
    [
        'method' => 'addPost',
        'controller' => '\App\Controllers\AppUserController'
    ],
    'user-add-post'
);

/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();