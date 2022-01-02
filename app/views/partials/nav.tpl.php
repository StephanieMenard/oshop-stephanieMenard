<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $router->generate('main-home') ?>">oShop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?= $router->generate('main-home') ?>">Accueil <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $router->generate('category-list') ?>">Catégories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $router->generate('product-list') ?>">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Types</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Marques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Tags</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Sélections Accueil &amp; Footer</a>
                </li>
                <li class="nav-item">
                    <?php if (isset($_SESSION['userId']) && isset($_SESSION['userObject'])) : ?>
                        <a class="btn btn-danger" role="button" href="<?= $router->generate('user-logout') ?>">Déconnexion</a>
                    <?php else : ?>
                        <a class="btn btn-success" role="button"  href="<?= $router->generate('user-login') ?>">Connexion</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>