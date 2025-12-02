<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    changeReviewStatus((int) $_POST['review_id'], $_POST['status']);
}
$reviews = moderationItems('reviews');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bewertungen prüfen – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header><div class="admin-nav"><a href="dashboard.php">Dashboard</a><a href="bands.php">Bands</a><a href="bewertungen.php">Bewertungen</a><a href="settings.php">Settings</a></div></header>
    <main>
        <h1>Bewertungen moderieren</h1>
        <table class="table">
            <thead><tr><th>Band</th><th>Autor</th><th>Bewertung</th><th>Kommentar</th><th>Aktion</th></tr></thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= htmlspecialchars($review['band_name']) ?></td>
                        <td><?= htmlspecialchars($review['author']) ?></td>
                        <td><?= (int) $review['rating'] ?> ★</td>
                        <td><?= htmlspecialchars($review['comment']) ?></td>
                        <td>
                            <form method="post" style="display:inline-flex; gap:4px;">
                                <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                <select name="status" class="form-control" style="width:auto;">
                                    <option value="freigegeben">Freigeben</option>
                                    <option value="abgelehnt">Ablehnen</option>
                                </select>
                                <button class="btn-primary">Speichern</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!$reviews): ?><p>Keine Bewertungen in Moderation.</p><?php endif; ?>
    </main>
</body>
</html>
