<?php
/**
 * @var int $nbUtilisateurs
 * @var int $nbAgences
 * @var int $nbTrajets
 */
?>
<h1 class="h3 mb-4">Tableau de bord administrateur</h1>

<div class="row g-3">
    <div class="col-12 col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <p class="display-6 mb-0"><?= $nbUtilisateurs ?></p>
                <p class="text-muted mb-2">Utilisateurs</p>
                <a href="/admin/utilisateurs" class="btn btn-sm btn-outline-primary">Voir la liste</a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <p class="display-6 mb-0"><?= $nbAgences ?></p>
                <p class="text-muted mb-2">Agences</p>
                <a href="/admin/agences" class="btn btn-sm btn-outline-primary">Gerer</a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <p class="display-6 mb-0"><?= $nbTrajets ?></p>
                <p class="text-muted mb-2">Trajets</p>
                <a href="/admin/trajets" class="btn btn-sm btn-outline-primary">Voir la liste</a>
            </div>
        </div>
    </div>
</div>
