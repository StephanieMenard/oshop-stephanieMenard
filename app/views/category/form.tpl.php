<div class="container-fluid mt-2">
    <?php if (!empty($errorsList)) : ?>
        <div class="alert alert-danger" role="alert">
            <ul>
                <?php foreach ($errorsList as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>
</div>

<?php
// dump($category ,$category->getName())
?>

<div class="container my-4">
    <a href="<?= $router->generate('category-list') ?>" class="btn btn-success float-right">Retour</a>
    <!-- Un seul est même fomulaire pour l'ajout et la MaJ d'1 Categorie -->
    <!-- Pour l'ajout : on a un objet Category === null  -->
    <!-- Pour la MaJ : on a un objet Category rempli -->
    <!-- On va pouvoir faire une verif sur l'objet Category -->
    <!-- Si l'objet === null : Alors on est sur la page d'ajout -->
    <!-- Autrement : Alors on est sur la page de MaJ -->
    <!-- On va faire cette verif avec une condition ternaire -->
    <h2><?= $category->getId() === null ? 'Ajouter' : 'Modifier' ?> une catégorie</h2>

    <form action="" method="POST" class="mt-5">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $category->getName() ?>" placeholder="Nom de la catégorie">
        </div>
        <div class="form-group">
            <label for="subtitle">Sous-titre</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" value="<?= $category->getSubtitle() ?>" placeholder="Sous-titre" aria-describedby="subtitleHelpBlock">
            <small id="subtitleHelpBlock" class="form-text text-muted">
                Sera affiché sur la page d'accueil comme bouton devant l'image
            </small>
        </div>
        <div class="form-group">
            <label for="picture">Image</label>
            <input type="text" class="form-control" id="picture" name="picture" value="<?= $category->getPicture() ?>" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
            <small id="pictureHelpBlock" class="form-text text-muted">
                URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
            </small>
        </div>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </form>
</div>