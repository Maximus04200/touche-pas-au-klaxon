<?php
/** @var array<int, array<string, mixed>> $utilisateurs */
?>
<h1 class="h3 mb-4">Utilisateurs</h1>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Role</th>
                <th>Actif</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($utilisateur['telephone'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span class="badge bg-<?= $utilisateur['role'] === 'admin' ? 'dark' : 'secondary' ?>">
                            <?= htmlspecialchars($utilisateur['role'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td><?= ((int) $utilisateur['actif']) === 1 ? 'Oui' : 'Non' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
