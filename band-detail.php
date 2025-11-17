<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/email.php';

$bandId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$band = $bandId ? findBand($bandId) : null;

if (!$band) {
    http_response_code(404);
    echo 'Band nicht gefunden';
    exit;
}

$media = bandMedia($bandId);
$availability = bandAvailability($bandId);
$reviews = bandReviews($bandId);
$user = currentUser();
$reviewMessage = '';
$reviewError = '';
$contactMessage = '';
$contactError = '';
$contactForm = [
    'name' => '',
    'email' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['review']) && $user) {
        if (!eligibleForReview($bandId, (int) $user['id'])) {
            $reviewError = 'Für Bewertungen ist eine bestätigte Buchung nötig.';
        } else {
            $comment = trim((string) ($_POST['comment'] ?? ''));
            if (mb_strlen($comment) > 200) {
                $reviewError = 'Maximal 200 Zeichen erlaubt.';
            } else {
                storeReview([
                    'band_id' => $bandId,
                    'user_id' => (int) $user['id'],
                    'rating' => (int) $_POST['rating'],
                    'comment' => $comment,
                ]);
                $reviewMessage = 'Danke! Deine Bewertung wartet auf Freigabe.';
            }
        }
    }

    if (isset($_POST['contact'])) {
        $contactForm['name'] = trim((string) ($_POST['contact_name'] ?? ''));
        $contactForm['email'] = trim((string) ($_POST['contact_email'] ?? ''));
        $contactForm['message'] = trim((string) ($_POST['contact_message'] ?? ''));

        if ($contactForm['name'] === '' || $contactForm['message'] === '') {
            $contactError = 'Bitte Name und Nachricht ausfüllen.';
        } elseif (!filter_var($contactForm['email'], FILTER_VALIDATE_EMAIL)) {
            $contactError = 'Bitte eine gültige E-Mail-Adresse angeben.';
        } else {
            $recipient = $band['contact_email'] ?: SUPPORT_EMAIL;
            $body = sprintf(
                '<p>Neue Nachricht über die Bandseite %s.</p><p><strong>Von:</strong> %s (%s)</p><p><strong>Nachricht:</strong><br>%s</p>',
                htmlspecialchars($band['name']),
                htmlspecialchars($contactForm['name']),
                htmlspecialchars($contactForm['email']),
                nl2br(htmlspecialchars($contactForm['message']))
            );
            $sent = sendEmail($recipient, 'Kontaktformular – ' . $band['name'], $body);
            if ($sent) {
                $contactMessage = 'Nachricht an die Band wurde verschickt.';
                $contactForm = ['name' => '', 'email' => '', 'message' => ''];
            } else {
                $contactError = 'E-Mail-Versand zur Band nicht möglich. Bitte später erneut versuchen.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($band['name']) ?> – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a class="badge" href="index.php">← Zurück</a>
        <div class="hero" style="margin-top: 20px;">
            <div>
                <p class="badge"><?= htmlspecialchars($band['genre']) ?></p>
                <h1><?= htmlspecialchars($band['name']) ?></h1>
                <p><?= nl2br(htmlspecialchars($band['description'])) ?></p>
                <div class="badge-list">
                    <span class="badge">Ort: <?= htmlspecialchars($band['city'] ?? '–') ?></span>
                    <span class="badge">ab <?= formatPrice((int) $band['price']) ?></span>
                </div>
                <div class="badge-list">
                    <?php foreach (array_filter(array_map('trim', explode(',', (string) $band['style_tags']))) as $tag): ?>
                        <span class="badge">#<?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
                <p>
                    <a class="btn-primary" href="anfrage.php?band_id=<?= $bandId ?>">Verfügbarkeit anfragen</a>
                </p>
            </div>
            <div>
                <?php if (!empty($band['video_url'])): ?>
                    <iframe width="100%" height="280" src="<?= htmlspecialchars($band['video_url']) ?>" title="Bandvideo" allowfullscreen></iframe>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>
        <section class="band-detail-grid">
            <div class="gallery">
                <h3>Galerie</h3>
                <?php foreach ($media as $item): ?>
                    <?php if ($item['type'] === 'image'): ?>
                        <img src="<?= htmlspecialchars($item['url']) ?>" alt="Bandbild">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div>
                <h3>Verfügbarkeit</h3>
                <table class="table">
                    <thead>
                        <tr><th>Datum</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($availability as $slot): ?>
                            <tr>
                                <td><?= htmlspecialchars((new DateTimeImmutable($slot['event_date']))->format('d.m.Y')) ?></td>
                                <td><?= htmlspecialchars(ucfirst($slot['status'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section style="margin-top: 40px;">
            <h3>Kontakt zur Band</h3>
            <p>Schicke <?= htmlspecialchars($band['name']) ?> eine direkte Nachricht.</p>
            <?php if ($contactMessage): ?><div class="alert alert-success"><?= htmlspecialchars($contactMessage) ?></div><?php endif; ?>
            <?php if ($contactError): ?><div class="alert alert-error"><?= htmlspecialchars($contactError) ?></div><?php endif; ?>
            <form method="post">
                <input type="hidden" name="contact" value="1">
                <label>Dein Name
                    <input class="form-control" name="contact_name" value="<?= htmlspecialchars($contactForm['name']) ?>" required>
                </label>
                <label>Deine E-Mail
                    <input class="form-control" type="email" name="contact_email" value="<?= htmlspecialchars($contactForm['email']) ?>" required>
                </label>
                <label>Nachricht
                    <textarea class="form-control" name="contact_message" rows="4" required><?= htmlspecialchars($contactForm['message']) ?></textarea>
                </label>
                <button class="btn-primary">Nachricht senden</button>
            </form>
        </section>

        <section style="margin-top: 40px;">
            <h3>Bewertungen</h3>
            <?php if ($reviewMessage): ?><div class="alert alert-success"><?= htmlspecialchars($reviewMessage) ?></div><?php endif; ?>
            <?php if ($reviewError): ?><div class="alert alert-error"><?= htmlspecialchars($reviewError) ?></div><?php endif; ?>
            <?php foreach ($reviews as $review): ?>
                <article class="band-card">
                    <p><strong><?= htmlspecialchars($review['author']) ?></strong> – <?= (int) $review['rating'] ?> ★</p>
                    <p><?= htmlspecialchars($review['comment']) ?></p>
                    <p class="card-meta"><?= (new DateTimeImmutable($review['created_at']))->format('d.m.Y') ?></p>
                </article>
            <?php endforeach; ?>
            <?php if (!$reviews): ?>
                <p>Noch keine freigegebenen Bewertungen.</p>
            <?php endif; ?>
        </section>

        <?php if ($user && $user['role'] === 'kunde'): ?>
            <section style="margin-top: 40px;">
                <h3>Eigene Bewertung</h3>
                <form method="post">
                    <input type="hidden" name="review" value="1">
                    <label>Sterne
                        <select class="form-control" name="rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </label>
                    <label>Kommentar (max. 200 Zeichen)
                        <textarea class="form-control" name="comment" maxlength="200"></textarea>
                    </label>
                    <button class="btn-primary">Bewertung senden</button>
                </form>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
