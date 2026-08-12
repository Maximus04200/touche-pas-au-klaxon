<?php
/** @var array<int, array{type: string, message: string}> $flashMessages */
foreach ($flashMessages as $flash): ?>
    <div class="klaxon-flash mb-4">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endforeach; ?>
