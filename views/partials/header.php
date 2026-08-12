<?php

use App\Core\Csrf;

/** @var array<string, mixed>|null $currentUser */
$isAdmin = $currentUser !== null && $currentUser['role'] === 'admin';
$brandHref = $isAdmin ? '/admin' : '/';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= htmlspecialchars($brandHref, ENT_QUOTES, 'UTF-8') ?>">
            Touche pas au klaxon
        </a>

        <?php if ($currentUser === null): ?>
            <a href="/login" class="btn btn-light">Connexion</a>
        <?php elseif ($isAdmin): ?>
            <div class="d-flex align-items-center gap-2">
                <ul class="navbar-nav flex-row gap-3 me-3">
                    <li class="nav-item"><a class="nav-link text-white" href="/admin/utilisateurs">Utilisateurs</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/admin/agences">Agences</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="/admin/trajets">Trajets</a></li>
                </ul>
                <form action="/logout" method="post" class="mb-0">
                    <?= Csrf::field() ?>
                    <button type="submit" class="btn btn-outline-light btn-sm">Deconnexion</button>
                </form>
            </div>
        <?php else: ?>
            <div class="d-flex align-items-center gap-3">
                <a href="/trajets/creer" class="btn btn-light">Proposer un trajet</a>
                <span class="text-white">
                    <?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <form action="/logout" method="post" class="mb-0">
                    <?= Csrf::field() ?>
                    <button type="submit" class="btn btn-outline-light btn-sm">Deconnexion</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>
