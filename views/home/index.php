<?php

use App\Core\Csrf;

/**
 * @var array<int, array<string, mixed>> $trajets
 * @var array<string, mixed>|null $currentUser
 */
?>
<h1 class="h4 mb-4">
    <?= $currentUser === null
        ? "Pour obtenir plus d'informations sur un trajet, veuillez vous connecter"
        : 'Trajets proposes' ?>
</h1>

<?php if ($trajets === []): ?>
    <p class="text-muted">Aucun trajet a venir avec des places disponibles pour le moment.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-klaxon align-middle">
            <thead>
                <tr>
                    <th>Depart</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places</th>
                    <?php if ($currentUser !== null): ?>
                        <th></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                    <?php
                        $depart = new DateTime($trajet['date_heure_depart']);
                        $arrivee = new DateTime($trajet['date_heure_arrivee']);
                        $estAuteur = $currentUser !== null
                            && (int) $trajet['id_utilisateur'] === (int) $currentUser['id_utilisateur'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($trajet['ville_depart'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $depart->format('d/m/y') ?></td>
                        <td><?= $depart->format('H:i') ?></td>
                        <td><?= htmlspecialchars($trajet['ville_arrivee'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $arrivee->format('d/m/y') ?></td>
                        <td><?= $arrivee->format('H:i') ?></td>
                        <td><?= (int) $trajet['nb_places_dispo'] ?></td>
                        <?php if ($currentUser !== null): ?>
                            <td class="text-nowrap">
                                <button
                                    type="button"
                                    class="btn-icon text-primary"
                                    title="Details"
                                    data-trajet-details="<?= (int) $trajet['id_trajet'] ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#trajetDetailsModal"
                                >
                                    <?php require __DIR__ . '/../partials/icon-eye.php'; ?>
                                </button>

                                <?php if ($estAuteur): ?>
                                    <a href="/trajets/<?= (int) $trajet['id_trajet'] ?>/modifier" class="btn-icon text-primary" title="Modifier">
                                        <?php require __DIR__ . '/../partials/icon-pencil.php'; ?>
                                    </a>
                                    <form action="/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer" method="post"
                                          class="d-inline" onsubmit="return confirm('Supprimer ce trajet ?');">
                                        <?= Csrf::field() ?>
                                        <button type="submit" class="btn-icon text-danger" title="Supprimer">
                                            <?php require __DIR__ . '/../partials/icon-trash.php'; ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($currentUser !== null): ?>
    <div class="modal fade" id="trajetDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h5 modal-title">Details du trajet</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
