<?php

use App\Core\Csrf;
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-4">
        <h1 class="h3 mb-4">Connexion</h1>

        <form action="/login" method="post" novalidate>
            <?= Csrf::field() ?>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</div>
