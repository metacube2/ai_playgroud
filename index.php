<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$filters = [
    'genre' => $_GET['genre'] ?? '',
    'city' => $_GET['city'] ?? '',
];
$bands = allBands($filters);
$settings = settings();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> – Bands buchen</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="badge-list" style="justify-content: flex-end;">
            <?php if ($user): ?>
                <span class="badge">Hallo <?= htmlspecialchars($user['name']) ?></span>
                <a class="badge" href="profil.php">Mein Profil</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a class="badge" href="admin/dashboard.php">Admin</a>
                <?php endif; ?>
                <a class="badge" href="login.php?action=logout">Logout</a>
            <?php else: ?>
                <a class="badge" href="login.php">Login / Registrieren</a>
            <?php endif; ?>
        </nav>
        <section class="hero">
            <div>
                <p class="badge">Schritt 3 · Frontend Release</p>
                <h1>Finde deine <span style="color: var(--primary);">Funky Liveband</span></h1>
                <p>GetYourBand bringt verifizierte Live-Acts mit Veranstalter:innen in der ganzen Schweiz zusammen. Mit Bewertungen,
                    moderner Suche und aktivierbarer Vermittlungsgebühr.</p>
                <div class="badge-list">
                    <span class="badge">Bewertungen geprüft</span>
                    <span class="badge badge-rating">PayPal <?= $settings['paypal_enabled'] === '1' ? 'aktiv' : 'optional' ?></span>
                    <span class="badge">Service Fee <?= htmlspecialchars($settings['service_fee']) ?>%</span>
                </div>
            </div>
            <form class="search-panel" method="get" data-filter-form>
                <div>
                    <label for="genre">Stil / Genre</label>
                    <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($filters['genre']) ?>" placeholder="Funk, Party, Jazz">
                </div>
                <div>
                    <label for="city">Ort / PLZ</label>
                    <input type="text" id="city" name="city" value="<?= htmlspecialchars($filters['city']) ?>" placeholder="Zürich, Basel">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-primary">Filtern</button>
                </div>
            </form>
        </section>
    </header>
    <main>
        <h2>Aktive Bands (<?= count($bands) ?>)</h2>
        <section class="band-grid">
            <?php foreach ($bands as $band): $rating = averageRating((int) $band['id']); ?>
                <article class="band-card">
                    <p class="badge"><?= htmlspecialchars($band['genre']) ?></p>
                    <h3><?= htmlspecialchars($band['name']) ?></h3>
                    <p class="card-meta">Standort: <?= htmlspecialchars($band['city'] ?? '–') ?></p>
                    <p><?= htmlspecialchars($band['description']) ?></p>
                    <p class="price-tag">ab <?= formatPrice((int) $band['price']) ?></p>
                    <?php if ($rating): ?>
                        <p class="card-meta">Bewertung: <?= $rating ?> ★</p>
                    <?php endif; ?>
                    <div class="badge-list">
                        <?php foreach (array_filter(array_map('trim', explode(',', (string) $band['style_tags']))) as $tag): ?>
                            <span class="badge">#<?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <p>
                        <a class="btn-primary" href="band-detail.php?id=<?= (int) $band['id'] ?>">Band ansehen</a>
                    </p>
                </article>
            <?php endforeach; ?>
            <?php if (!$bands): ?>
                <p>Keine Bands gefunden – ändere deine Filter.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <div>
            <strong>Legal</strong><br>
            <a href="#">Datenschutz</a> · <a href="#">AGB</a>
        </div>
        <div>
            <strong>Kontakt</strong><br>
            support@getyourband.ch
        </div>
    </footer>

    <div class="cookie-banner">
        <p>Wir verwenden Cookies für Performance-Analysen. Mit Klick auf "Okay" akzeptierst du das.</p>
        <button class="btn-primary" data-cookie-accept>Okay!</button>
    </div>
    <script src="assets/js/app.js" defer></script>
</body>
</html>
