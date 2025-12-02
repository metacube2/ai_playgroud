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

    if (!$data['event_date'] || !$data['location']) {
        $error = 'Bitte Datum und Ort ausfüllen.';
    } else {
        createRequest($data);
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
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <label>Event-Datum
                <input type="date" class="form-control" name="event_date" required>
            </label>
            <label>Ort / Location
                <input type="text" class="form-control" name="location" placeholder="Zürich, Kaufleuten" required>
            </label>
            <label>Event-Typ
                <input type="text" class="form-control" name="event_type" placeholder="Hochzeit, Firmenfeier">
            </label>
            <label>Budget (CHF)
                <input type="number" class="form-control" name="budget" placeholder="4500">
            </label>
            <label>Nachricht
                <textarea class="form-control" name="message" rows="4"></textarea>
            </label>
            <button class="btn-primary">Anfrage senden</button>
        </form>
    </main>
</body>
</html>
