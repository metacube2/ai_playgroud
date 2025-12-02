<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    changeBandStatus((int) $_POST['band_id'], $_POST['status']);
}
$bands = moderationItems('bands');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bands moderieren – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header><div class="admin-nav"><a href="dashboard.php">Dashboard</a><a href="bands.php">Bands</a><a href="bewertungen.php">Bewertungen</a><a href="settings.php">Settings</a></div></header>
    <main>
        <h1>Bandfreigaben</h1>
        <table class="table">
            <thead><tr><th>Name</th><th>Ort</th><th>Status</th><th>Aktion</th></tr></thead>
            <tbody>
                <?php foreach ($bands as $band): ?>
                    <tr>
                        <td><?= htmlspecialchars($band['name']) ?></td>
                        <td><?= htmlspecialchars($band['city']) ?></td>
                        <td><?= htmlspecialchars($band['status']) ?></td>
                        <td>
                            <form method="post" style="display:inline-flex; gap:4px;">
                                <input type="hidden" name="band_id" value="<?= $band['id'] ?>">
                                <select name="status" class="form-control" style="width:auto;">
                                    <option value="aktiv">Freigeben</option>
                                    <option value="archiv">Archivieren</option>
                                    <option value="prüfung">Zur Prüfung</option>
                                </select>
                                <button class="btn-primary">Speichern</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!$bands): ?><p>Keine Bands warten auf Moderation.</p><?php endif; ?>
    </main>
</body>
</html>
