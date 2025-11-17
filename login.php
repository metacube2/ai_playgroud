<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/email.php';

$action = $_GET['action'] ?? '';
if ($action === 'logout') {
    logout();
    header('Location: index.php');
    exit;
}

$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        try {
            if (!login($_POST['email'], $_POST['password'])) {
                $error = 'Login fehlgeschlagen.';
            } else {
                header('Location: index.php');
                exit;
            }
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        }
    } elseif (isset($_POST['register'])) {
        if ($_POST['password'] !== $_POST['password_confirm']) {
            $error = 'Passwörter stimmen nicht überein.';
        } else {
            $result = register([
                'name' => trim((string) $_POST['name']),
                'email' => trim((string) $_POST['email']),
                'password' => $_POST['password'],
                'role' => $_POST['role'],
                'city' => trim((string) $_POST['city']),
                'band_name' => $_POST['band_name'] ?? null,
                'genre' => $_POST['genre'] ?? null,
            ]);
            $verificationLink = BASE_URL . '/verify-email.php?token=' . urlencode($result['token']);
            sendEmail($_POST['email'], 'E-Mail bestätigen', 'Bitte bestätige dein Konto: ' . $verificationLink);
            $message = 'Check deine Inbox – wir haben dir den Verifizierungslink geschickt: ' . htmlspecialchars($verificationLink);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registrierung – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a class="badge" href="index.php">← Zurück</a>
        <h1>Login / Registrieren</h1>
    </header>
    <main>
        <?php if ($message): ?><div class="alert alert-success"><?= $message ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <section class="band-detail-grid">
            <div>
                <h2>Login</h2>
                <form method="post">
                    <input type="hidden" name="login" value="1">
                    <label>E-Mail
                        <input class="form-control" type="email" name="email" required>
                    </label>
                    <label>Passwort
                        <input class="form-control" type="password" name="password" required>
                    </label>
                    <button class="btn-primary">Einloggen</button>
                </form>
            </div>
            <div>
                <h2>Registrierung</h2>
                <form method="post">
                    <input type="hidden" name="register" value="1">
                    <label>Name
                        <input class="form-control" type="text" name="name" required>
                    </label>
                    <label>E-Mail
                        <input class="form-control" type="email" name="email" required>
                    </label>
                    <label>Ort
                        <input class="form-control" type="text" name="city">
                    </label>
                    <label>Rolle
                        <select class="form-control" name="role">
                            <option value="kunde">Veranstalter:in</option>
                            <option value="band">Band</option>
                        </select>
                    </label>
                    <label>Bandname (falls Band)
                        <input class="form-control" type="text" name="band_name">
                    </label>
                    <label>Genre
                        <input class="form-control" type="text" name="genre">
                    </label>
                    <label>Passwort
                        <input class="form-control" type="password" name="password" required>
                    </label>
                    <label>Passwort wiederholen
                        <input class="form-control" type="password" name="password_confirm" required>
                    </label>
                    <button class="btn-primary">Account anlegen</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
