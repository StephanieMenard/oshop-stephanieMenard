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

    <a href="<?= $router->generate('user-list') ?>" class="btn btn-success float-right">Retour</a>
    <h2><?= $user->getId() === null ? 'Ajouter' : 'Modifier' ?> un utilisateur</h2>

    <form action="" method="POST" class="mt-5">
        <div class="form-group">
            <label for="lastname">Nom</label>
            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Nom de l'utilisateur" required value="<?= $user->getLastname() ?>">
        </div>
        <div class="form-group">
            <label for="description">Prénom</label>
            <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Prénom de l'utilisateur" value="<?= $user->getFirstname() ?>">
        </div>
         <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?= $user->getEmail() ?>">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" value="<?= $user->getPassword() ?>">
        </div>
        <div class="form-group">
            <label for="role">Rôle</label>
            <select class="custom-select" name="role" id="role">
                <option value="admin" <?= $user->getRole() == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="catalog-manager" <?= $user->getRole() == 'catalog-manager' ? 'selected' : '' ?>>Catalogue manageur</option>
                </select>
        </div>
        <div class="form-group">
            <label for="status">Statut</label>
            <select class="custom-select" id="status" name="status" aria-describedby="statusHelpBlock">
                <option value="0" <?= $user->getStatus() == 0 ? 'selected' : '' ?>>Inactif</option>
                <option value="1" <?= $user->getStatus() == 1 ? 'selected' : '' ?>>Actif</option>
            </select>
            <small id="statusHelpBlock" class="form-text text-muted">
                Le statut de l'utilisateur
            </small>
        </div>
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </form>
</div> 