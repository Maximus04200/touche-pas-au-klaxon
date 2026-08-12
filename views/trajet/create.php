<?php

use App\Core\Csrf;

/**
 * @var array<int, array<string, mixed>> $agences
 * @var array<string, mixed>|null $currentUser
 */
?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <h1 class="h3 mb-4">Proposer un trajet</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h6 card-title">Vos coordonnees (transmises aux collegues interesses)</h2>
                <p class="mb-1"><?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="mb-1"><?= htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="mb-0"><?= htmlspecialchars($currentUser['telephone'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <form action="/trajets/creer" method="post" novalidate>
            <?= Csrf::field() ?>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label for="id_agence_depart" class="form-label">Agence de depart</label>
                    <select class="form-select" id="id_agence_depart" name="id_agence_depart" required>
                        <option value="">Choisir...</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= (int) $agence['id_agence'] ?>">
                                <?= htmlspecialchars($agence['ville'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label for="id_agence_arrivee" class="form-label">Agence d'arrivee</label>
                    <select class="form-select" id="id_agence_arrivee" name="id_agence_arrivee" required>
                        <option value="">Choisir...</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= (int) $agence['id_agence'] ?>">
                                <?= htmlspecialchars($agence['ville'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label for="date_heure_depart" class="form-label">Date et heure de depart</label>
                    <input type="datetime-local" class="form-control" id="date_heure_depart" name="date_heure_depart" required>
                </div>
                <div class="col-6">
                    <label for="date_heure_arrivee" class="form-label">Date et heure d'arrivee</label>
                    <input type="datetime-local" class="form-control" id="date_heure_arrivee" name="date_heure_arrivee" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="nb_places_total" class="form-label">Nombre total de places proposees</label>
                <input type="number" class="form-control" id="nb_places_total" name="nb_places_total" min="1" max="9" required>
            </div>

            <button type="submit" class="btn btn-primary">Publier le trajet</button>
            <a href="/" class="btn btn-link">Annuler</a>
        </form>
    </div>
</div>
