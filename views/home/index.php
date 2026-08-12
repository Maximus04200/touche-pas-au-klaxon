<?php

use App\Core\Auth;

/**
 * @var array<int, array<string, mixed>> $trajets
 * @var array<string, mixed>|null $currentUser
 */
?>
<h1 class="h3 mb-4">Trajets disponibles</h1>

<?php if ($trajets === []): ?>
    <p class="text-muted">Aucun trajet a venir avec des places disponibles pour le moment.</p>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($trajets as $trajet): ?>
            <?php $estAuteur = $currentUser !== null && (int) $trajet['id_utilisateur'] === (int) $currentUser['id_utilisateur']; ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card trajet-card h-100">
                    <div class="card-body">
                        <h2 class="h5 card-title mb-3">
                            <?= htmlspecialchars($trajet['ville_depart'], ENT_QUOTES, 'UTF-8') ?>
                            <span class="text-muted">&rarr;</span>
                            <?= htmlspecialchars($trajet['ville_arrivee'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>
                        <p class="mb-1">
                            <strong>Depart :</strong>
                            <?= htmlspecialchars((new DateTime($trajet['date_heure_depart']))->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <p class="mb-1">
                            <strong>Arrivee :</strong>
                            <?= htmlspecialchars((new DateTime($trajet['date_heure_arrivee']))->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <p class="mb-3">
                            <strong>Places disponibles :</strong>
                            <span class="badge bg-success"><?= (int) $trajet['nb_places_dispo'] ?></span>
                        </p>

                        <?php if ($currentUser !== null): ?>
                            <div class="d-flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-trajet-details="<?= (int) $trajet['id_trajet'] ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#trajetDetailsModal"
                                >
                                    Details
                                </button>

                                <?php if ($estAuteur): ?>
                                    <a href="/trajets/<?= (int) $trajet['id_trajet'] ?>/modifier" class="btn btn-sm btn-outline-secondary">
                                        Modifier
                                    </a>
                                    <form action="/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer" method="post"
                                          onsubmit="return confirm('Supprimer ce trajet ?');">
                                        <?= \App\Core\Csrf::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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
            </div>
        </div>
    </div>
<?php endif; ?>
