<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$user = currentUser();
$band = null;
$message = '';

if ($user['role'] === 'band') {
    $stmt = db()->prepare('SELECT * FROM bands WHERE user_id = :id');
    $stmt->execute([':id' => $user['id']]);
    $band = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = db()->prepare('UPDATE bands SET name = :name, email = :email, city = :city, genre = :genre, price = :price, description = :description, style_tags = :tags WHERE id = :id');
        $stmt->execute([
            ':name' => $_POST['name'],
            ':email' => $_POST['email'] ?? null,
            ':city' => $_POST['city'],
            ':genre' => $_POST['genre'],
            ':price' => (int) $_POST['price'],
            ':description' => $_POST['description'],
            ':tags' => $_POST['style_tags'],
            ':id' => $band['id'],
        ]);
        $message = 'Bandprofil aktualisiert (wartet ggf. auf Freigabe).';
        $band = findBand((int) $band['id']);
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mein Bereich – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a class="badge" href="index.php">← Startseite</a>
        <h1>Hallo <?= htmlspecialchars($user['name']) ?></h1>
        <p>Rolle: <?= htmlspecialchars($user['role']) ?></p>
    </header>
    <main>
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($band): ?>
            <h2>Bandprofil</h2>
            <form method="post">
                <label>Bandname
                    <input class="form-control" name="name" value="<?= htmlspecialchars($band['name']) ?>" required>
                </label>
                <label>Email für Buchungsanfragen
                    <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($band['email'] ?? '') ?>" placeholder="band@example.ch">
                    <small>An diese Adresse werden Buchungsanfragen gesendet</small>
                </label>
                <label>Ort
                    <input class="form-control" name="city" value="<?= htmlspecialchars($band['city']) ?>">
                </label>
                <label>Genre
                    <input class="form-control" name="genre" value="<?= htmlspecialchars($band['genre']) ?>">
                </label>
                <label>Tags
                    <input class="form-control" name="style_tags" value="<?= htmlspecialchars($band['style_tags']) ?>">
                </label>
                <label>Preis (CHF)
                    <input class="form-control" type="number" name="price" value="<?= (int) $band['price'] ?>">
                </label>
                <label>Beschreibung
                    <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($band['description']) ?></textarea>
                </label>
                <button class="btn-primary">Speichern</button>
            </form>
        <?php else: ?>
            <p>Du hast noch kein Bandprofil angelegt.</p>
        <?php endif; ?>

        <?php if ($user['role'] === 'kunde'): ?>
            <h2>Meine Anfragen</h2>
            <table class="table">
                <thead><tr><th>Band</th><th>Datum</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach (userRequests((int) $user['id']) as $request): $bandName = findBand((int) $request['band_id']); ?>
                        <tr>
                            <td><?= htmlspecialchars($bandName['name'] ?? 'Band #' . $request['band_id']) ?></td>
                            <td><?= htmlspecialchars($request['event_date']) ?></td>
                            <td><?= htmlspecialchars($request['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
