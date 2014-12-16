<form action="<?= HTML::link('admin1259/users/signin') ?>" method="POST">
    <?= Core\Session::getFlash() ?>
    <div class="form-control">
        <label for="username">Nom d'utilisateur</label>
        <input class="input" id="username" name="username" type="text">
    </div>
    <div class="form-control">
        <label for="password">Mot de passe</label>
        <input class="input" id="password" name="password" type="password">
    </div>
    <div class="row">
        <div class="form-control left">
            <input type="hidden" name="remember" value="0">
            <input type="checkbox" class="checkbox" value="1" id="remember" name="remember">
            <label for="remember">Se souvenir de moi</label>
        </div>
        <div class="form-control right">
            <button class="btn btn-primary" type="submit">Se connecter</button>
        </div>
    </div>
</form>