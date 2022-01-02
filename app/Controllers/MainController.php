<?php

namespace App\Controllers;

// Si j'ai besoin de mes Models
use App\Models\Category;
use App\Models\Product;

class MainController extends CoreController {

    /**
     * Méthode s'occupant de la page d'accueil
     *
     * @return void
     */
    public function home()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);

        $categoriesHomepage = Category::findAllHomepage();
        $productsHomepage = Product::findAllHomepage();

        // dump($categoriesHomepage, productsHomepage);

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('main/home', 
            [
                'categories' => $categoriesHomepage,
                'products' => $productsHomepage
            ]
        );
    }
}
