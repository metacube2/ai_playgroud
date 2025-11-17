<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateSetting('paypal_enabled', isset($_POST['paypal_enabled']) ? '1' : '0');
    updateSetting('service_fee', (string) (int) $_POST['service_fee']);
    $message = 'Einstellungen gespeichert.';
}
$config = settings();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Settings – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header><div class="admin-nav"><a href="dashboard.php">Dashboard</a><a href="bands.php">Bands</a><a href="bewertungen.php">Bewertungen</a><a href="settings.php">Settings</a></div></header>
    <main>
        <h1>Vermittlungsgebühr & PayPal</h1>
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <form method="post">
            <label>
                <input type="checkbox" name="paypal_enabled" <?= $config['paypal_enabled'] === '1' ? 'checked' : '' ?>> PayPal aktivieren
            </label>
            <label>Service Fee (%)
                <input type="number" class="form-control" name="service_fee" value="<?= htmlspecialchars($config['service_fee']) ?>">
            </label>
            <button class="btn-primary">Speichern</button>
        </form>
    </main>
</body>
</html>
