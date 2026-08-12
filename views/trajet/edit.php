<?php

use App\Core\Csrf;

/**
 * @var array<string, mixed> $trajet
 * @var array<int, array<string, mixed>> $agences
 */
$departValue = (new DateTime($trajet['date_heure_depart']))->format('Y-m-d\TH:i');
$arriveeValue = (new DateTime($trajet['date_heure_arrivee']))->format('Y-m-d\TH:i');
?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <h1 class="h3 mb-4">Modifier le trajet</h1>

        <form action="/trajets/<?= (int) $trajet['id_trajet'] ?>/modifier" method="post" novalidate>
            <?= Csrf::field() ?>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label for="id_agence_depart" class="form-label">Agence de depart</label>
                    <select class="form-select" id="id_agence_depart" name="id_agence_depart" required>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= (int) $agence['id_agence'] ?>"
                                <?= (int) $agence['id_agence'] === (int) $trajet['id_agence_depart'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['ville'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label for="id_agence_arrivee" class="form-label">Agence d'arrivee</label>
                    <select class="form-select" id="id_agence_arrivee" name="id_agence_arrivee" required>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= (int) $agence['id_agence'] ?>"
                                <?= (int) $agence['id_agence'] === (int) $trajet['id_agence_arrivee'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['ville'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label for="date_heure_depart" class="form-label">Date et heure de depart</label>
                    <input type="datetime-local" class="form-control" id="date_heure_depart" name="date_heure_depart"
                           value="<?= htmlspecialchars($departValue, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="col-6">
                    <label for="date_heure_arrivee" class="form-label">Date et heure d'arrivee</label>
                    <input type="datetime-local" class="form-control" id="date_heure_arrivee" name="date_heure_arrivee"
                           value="<?= htmlspecialchars($arriveeValue, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label for="nb_places_total" class="form-label">Nombre total de places</label>
                    <input type="number" class="form-control" id="nb_places_total" name="nb_places_total"
                           min="1" max="9" value="<?= (int) $trajet['nb_places_total'] ?>" required>
                </div>
                <div class="col-6">
                    <label for="nb_places_dispo" class="form-label">Places encore disponibles</label>
                    <input type="number" class="form-control" id="nb_places_dispo" name="nb_places_dispo"
                           min="0" max="9" value="<?= (int) $trajet['nb_places_dispo'] ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/" class="btn btn-link">Annuler</a>
        </form>
    </div>
</div>
