<?php

use App\Core\Csrf;

/** @var array<int, array<string, mixed>> $agences */
?>
<h1 class="h3 mb-4">Agences</h1>

<div class="card mb-4">
    <div class="card-body">
        <h2 class="h6 card-title">Ajouter une agence</h2>
        <form action="/admin/agences/creer" method="post" class="row g-2" novalidate>
            <?= Csrf::field() ?>
            <div class="col-8 col-md-6">
                <input type="text" class="form-control" name="ville" maxlength="100" placeholder="Nom de la ville" required>
            </div>
            <div class="col-4 col-md-3">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-klaxon align-middle">
        <thead>
            <tr>
                <th>Ville</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agences as $agence): ?>
                <tr>
                    <td><?= htmlspecialchars($agence['ville'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-bs-toggle="modal" data-bs-target="#editAgenceModal"
                                data-agence-id="<?= (int) $agence['id_agence'] ?>"
                                data-agence-ville="<?= htmlspecialchars($agence['ville'], ENT_QUOTES, 'UTF-8') ?>">
                            Modifier
                        </button>
                        <form action="/admin/agences/<?= (int) $agence['id_agence'] ?>/supprimer" method="post"
                              class="d-inline" onsubmit="return confirm('Supprimer cette agence ?');">
                            <?= Csrf::field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="editAgenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editAgenceForm" method="post" novalidate>
                <div class="modal-header">
                    <h2 class="h5 modal-title">Modifier l'agence</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <?= Csrf::field() ?>
                    <label for="editAgenceVille" class="form-label">Nom de la ville</label>
                    <input type="text" class="form-control" id="editAgenceVille" name="ville" maxlength="100" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('editAgenceModal').addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-agence-id');
        const ville = button.getAttribute('data-agence-ville');
        const form = document.getElementById('editAgenceForm');
        form.action = `/admin/agences/${id}/modifier`;
        document.getElementById('editAgenceVille').value = ville;
    });
</script>
