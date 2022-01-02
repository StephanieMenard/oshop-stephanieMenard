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

<div class="container my-4">
    <a href="<?= $router->generate('product-list') ?>" class="btn btn-success float-right">Retour</a>
    <h2><?= $product->getId() === null ? 'Ajouter' : 'Modifier' ?> un produit</h2>

    <form action="" method="POST" class="mt-5">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" value="<?= $product->getName() ?>" name="name" placeholder="Nom du produit">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description"  value="<?= $product->getDescription() ?>" name="description" placeholder="Sous-titre" aria-describedby="descriptionHelpBlock">
            <small id="subtitleHelpBlock" class="form-text text-muted">
                La description du produit
            </small>
        </div>
        <div class="form-group">
            <label for="picture">Image</label>
            <input type="text" class="form-control" id="picture"  value="<?= $product->getPicture() ?>" name="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
            <small id="pictureHelpBlock" class="form-text text-muted">
                URL relative d'une image (jpg, gif, svg ou png) fournie sur
                <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
            </small>
        </div>
        <div class="form-group">
            <label for="price">Prix</label>
            <input type="number" class="form-control" id="price"  value="<?= $product->getPrice() ?>" name="price" placeholder="Prix" aria-describedby="priceHelpBlock">
            <small id="priceHelpBlock" class="form-text text-muted">
                Le prix du produit
            </small>
        </div>
        <div class="form-group">
            <label for="rate">Note</label>
            <input type="text" class="form-control" id="rate"  value="<?= $product->getRate() ?>" name="rate" placeholder="Note" aria-describedby="rateHelpBlock">
            <small id="rateHelpBlock" class="form-text text-muted">
                Le note du produit
            </small>
        </div>
        <div class="form-group">
            <label for="status">Statut</label>
            <select class="custom-select" id="status"  value="<?= $product->getStatus() ?>" name="status" aria-describedby="statusHelpBlock">
                <option value="2" <?= $product->getStatus() == 2 ? 'selected' : '' ?>>Inactif</option>
                <option value="1" <?= $product->getStatus() == 1 ? 'selected' : '' ?> >Actif</option>
            </select>
            <small id="statusHelpBlock" class="form-text text-muted">
                Le statut du produit
            </small>
        </div>
        <div class="form-group">
            <label for="category">Catégorie</label>
            <select class="custom-select" id="category" value="<?= $product->getCategoryId() ?>" name="category_id" aria-describedby="categoryHelpBlock">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>" <?= $category->getId() == $product->getCategoryId() ? ' selected' : '' ?> ><?= $category->getName() ?></option>
                <?php endforeach ?>
            </select>
            <small id="categoryHelpBlock" class="form-text text-muted">
                La catégorie du produit
            </small>
        </div>
        <div class="form-group">
            <label for="brand">Marque</label>
            <select class="custom-select" id="brand"  value="<?= $product->getBrandId() ?>" name="brand_id" aria-describedby="brandHelpBlock">
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand->getId() ?>" <?= $brand->getId() == $product->getBrandId() ? ' selected' : '' ?>  ><?= $brand->getName() ?></option>
                <?php endforeach ?>
            </select>
            <small id="brandHelpBlock" class="form-text text-muted">
                La marque du produit
            </small>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="custom-select" id="type"  value="<?= $product->getTypeId() ?>" name="type_id" aria-describedby="typeHelpBlock">
                <?php foreach ($types as $type): ?>
                    <option value="<?= $type->getId() ?>" <?= $type->getId() == $product->getTypeId() ? ' selected' : '' ?> ><?= $type->getName() ?></option>
                <?php endforeach ?>
            </select>
            <small id="typeHelpBlock" class="form-text text-muted">
                Le type de produit
            </small>
        </div>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </form>
</div>