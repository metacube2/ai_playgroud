<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/email.php';

$bandId = isset($_GET['band_id']) ? (int) $_GET['band_id'] : 0;
$band = $bandId ? findBand($bandId) : null;
if (!$band) {
    http_response_code(404);
    echo 'Band nicht gefunden';
    exit;
}

$user = currentUser();
$message = '';
$error = '';

$requestId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'band_id' => $bandId,
        'user_id' => $user['id'] ?? null,
        'event_date' => $_POST['event_date'] ?? '',
        'location' => trim((string) $_POST['location'] ?? ''),
        'budget' => (int) ($_POST['budget'] ?? 0),
        'event_type' => trim((string) $_POST['event_type'] ?? ''),
        'message' => trim((string) $_POST['message'] ?? ''),
    ];

    $guestName = trim($_POST['guest_name'] ?? '');
    $guestEmail = trim($_POST['guest_email'] ?? '');

    if (!$data['event_date'] || !$data['location']) {
        $error = 'Bitte Datum und Ort ausfüllen.';
    } elseif (!$user && (!$guestName || !$guestEmail)) {
        $error = 'Bitte geben Sie Ihren Namen und Email-Adresse an.';
    } elseif (!$user && !filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Bitte geben Sie eine gültige Email-Adresse an.';
    } else {
        createRequest($data);
        $requestId = (int) db()->lastInsertId();
        $message = 'Anfrage gespeichert und an die Band gemeldet.';
        sendEmail('info@' . preg_replace('/\s+/', '', strtolower($band['name'])) . '.ch', 'Neue Anfrage', 'Neue Anfrage für ' . $band['name']);
    }
}

$settings = settings();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anfrage – <?= htmlspecialchars($band['name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a class="badge" href="band-detail.php?id=<?= $bandId ?>">← Zurück</a>
        <h1>Anfrage an <?= htmlspecialchars($band['name']) ?></h1>
        <p>PayPal Zahlungsabwicklung ist <?= $settings['paypal_enabled'] === '1' ? 'aktiviert' : 'optional' ?>, Service Fee: <?= htmlspecialchars($settings['service_fee']) ?>%.</p>
    </header>
    <main>
        <?php if ($message): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($message) ?>
                <?php if ($requestId && $settings['paypal_enabled'] === '1'): ?>
                    <div style="margin-top: 1rem;">
                        <a href="paypal-checkout.php?request_id=<?= $requestId ?>" class="btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem; text-decoration: none;">
                            Jetzt mit PayPal bezahlen
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <?php if (!$message): ?>
        <form method="post">
            <?php if (!$user): ?>
                <div style="background: #fff3cd; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <strong>Gast-Buchung</strong>
                    <p style="margin: 5px 0 0 0; font-size: 14px;">Sie sind nicht eingeloggt. Bitte geben Sie Ihre Kontaktdaten an.</p>
                </div>
                <label>Ihr Name *
                    <input type="text" class="form-control" name="guest_name" required>
                </label>
                <label>Ihre Email *
                    <input type="email" class="form-control" name="guest_email" required>
                </label>
                <hr style="margin: 20px 0;">
            <?php endif; ?>

            <label>Event-Datum *
                <input type="date" class="form-control" name="event_date" min="<?= date('Y-m-d') ?>" required>
            </label>
            <label>Ort / Location *
                <input type="text" class="form-control" name="location" placeholder="Zürich, Kaufleuten" required>
            </label>
            <label>Event-Typ
                <input type="text" class="form-control" name="event_type" placeholder="Hochzeit, Firmenfeier, Geburtstag">
            </label>
            <label>Budget (CHF)
                <input type="number" class="form-control" name="budget" placeholder="4500" min="0">
            </label>
            <label>Nachricht / Besondere Wünsche
                <textarea class="form-control" name="message" rows="4" placeholder="Erzählen Sie uns mehr über Ihr Event..."></textarea>
            </label>
            <button class="btn-primary">Anfrage senden</button>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>
