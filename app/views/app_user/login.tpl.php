<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <h1>Connexion</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="" method="POST">
                <input type="email" name="email" class="form-control my-4" placeholder="Veuillez renseigner votre e-mail">
                <input type="password" name="password" class="form-control my-4" placeholder="Veuillez renseigner votre MDP">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <button type="submit" class="btn btn-dark ">Connexion</button>
            </form>
        </div>
    </div>
</div>
