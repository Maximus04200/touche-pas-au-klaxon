<?php

use App\Core\Csrf;

/** @var array<int, array<string, mixed>> $trajets */
?>
<h1 class="h3 mb-4">Trajets</h1>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Depart</th>
                <th>Arrivee</th>
                <th>Date depart</th>
                <th>Date arrivee</th>
                <th>Places (dispo / total)</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trajets as $trajet): ?>
                <tr>
                    <td><?= htmlspecialchars($trajet['ville_depart'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($trajet['ville_arrivee'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((new DateTime($trajet['date_heure_depart']))->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((new DateTime($trajet['date_heure_arrivee']))->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $trajet['nb_places_dispo'] ?> / <?= (int) $trajet['nb_places_total'] ?></td>
                    <td class="text-end">
                        <form action="/admin/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer" method="post"
                              class="d-inline" onsubmit="return confirm('Supprimer ce trajet ?');">
                            <?= Csrf::field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
