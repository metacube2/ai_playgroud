<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pdo = db();
$stats = [
    'bands' => (int) $pdo->query("SELECT COUNT(*) FROM bands")->fetchColumn(),
    'requests' => (int) $pdo->query("SELECT COUNT(*) FROM requests")->fetchColumn(),
    'reviews' => (int) $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn(),
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="admin-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="bands.php">Bandfreigaben</a>
            <a href="bewertungen.php">Bewertungen</a>
            <a href="settings.php">Settings</a>
        </div>
    </header>
    <main>
        <section class="band-grid">
            <article class="band-card"><h3>Bands</h3><p><?= $stats['bands'] ?></p></article>
            <article class="band-card"><h3>Anfragen</h3><p><?= $stats['requests'] ?></p></article>
            <article class="band-card"><h3>Bewertungen</h3><p><?= $stats['reviews'] ?></p></article>
        </section>
    </main>
</body>
</html>
