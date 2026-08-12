<?php
/**
 * @var array<string, mixed>|null $currentUser
 * @var array<int, array{type: string, message: string}> $flashMessages
 * @var string $content
 */
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Touche pas au klaxon</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>

<main class="container py-4">
    <?php require __DIR__ . '/partials/flash.php'; ?>
    <?= $content ?>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js" defer></script>
</body>
</html>
