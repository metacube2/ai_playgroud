<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$token = $_GET['token'] ?? '';
$message = 'Ungültiger Token.';

if ($token) {
    $stmt = db()->prepare('UPDATE users SET verified = 1, verification_token = NULL WHERE verification_token = :token');
    $stmt->execute([':token' => $token]);
    if ($stmt->rowCount() > 0) {
        $message = 'Perfekt! Dein Account ist nun verifiziert. Du kannst dich einloggen.';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Verifizierung – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main style="padding: 60px 5vw;">
        <div class="hero">
            <p><?= htmlspecialchars($message) ?></p>
            <a class="btn-primary" href="login.php">Zum Login</a>
        </div>
    </main>
</body>
</html>
