<?php

use App\Core\Csrf;

/** @var array<string, mixed>|null $currentUser */
$isAdmin = $currentUser !== null && $currentUser['role'] === 'admin';
$brandHref = $isAdmin ? '/admin' : '/';
?>
<div class="container pt-4">
    <header class="klaxon-panel d-flex justify-content-between align-items-center flex-wrap gap-3">
        <a class="klaxon-brand fs-4" href="<?= htmlspecialchars($brandHref, ENT_QUOTES, 'UTF-8') ?>">
            Touche pas au klaxon
        </a>

        <?php if ($currentUser === null): ?>
            <a href="/login" class="btn btn-dark rounded-pill px-3">Connexion</a>
        <?php elseif ($isAdmin): ?>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="/admin/utilisateurs" class="btn btn-secondary rounded-pill px-3">Utilisateurs</a>
                <a href="/admin/agences" class="btn btn-secondary rounded-pill px-3">Agences</a>
                <a href="/admin/trajets" class="btn btn-secondary rounded-pill px-3">Trajets</a>
                <span class="ms-2">
                    Bonjour <?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <form action="/logout" method="post" class="mb-0">
                    <?= Csrf::field() ?>
                    <button type="submit" class="btn btn-dark rounded-pill px-3">Deconnexion</button>
                </form>
            </div>
        <?php else: ?>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="/trajets/creer" class="btn btn-dark rounded-pill px-3">Creer un trajet</a>
                <span class="ms-2">
                    Bonjour <?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <form action="/logout" method="post" class="mb-0">
                    <?= Csrf::field() ?>
                    <button type="submit" class="btn btn-dark rounded-pill px-3">Deconnexion</button>
                </form>
            </div>
        <?php endif; ?>
    </header>
</div>
