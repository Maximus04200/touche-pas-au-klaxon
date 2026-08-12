<?php
/** @var array<int, array{type: string, message: string}> $flashMessages */
foreach ($flashMessages as $flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endforeach; ?>
