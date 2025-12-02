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
        $stmt = db()->prepare('UPDATE bands SET name = :name, city = :city, genre = :genre, price = :price, description = :description, style_tags = :tags WHERE id = :id');
        $stmt->execute([
            ':name' => $_POST['name'],
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
                    <input class="form-control" name="name" value="<?= htmlspecialchars($band['name']) ?>">
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

            <h2 style="margin-top: 2rem;">Band-Galerie</h2>
            <div id="upload-status" style="display:none; padding: 1rem; margin-bottom: 1rem; background: #28a745; color: white; border-radius: 4px;"></div>
            <div style="margin-bottom: 1rem;">
                <label class="btn-primary" style="display: inline-block; cursor: pointer;">
                    <input type="file" id="image-upload" accept="image/*" style="display: none;">
                    + Bild hochladen
                </label>
                <small style="display: block; margin-top: 0.5rem; color: #666;">Max 5MB (JPG, PNG, GIF, WEBP)</small>
            </div>
            <div id="gallery" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                <?php foreach (bandMedia((int) $band['id']) as $media): ?>
                    <div class="gallery-item" data-media-id="<?= $media['id'] ?>">
                        <img src="<?= htmlspecialchars($media['url']) ?>" alt="Band Foto" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
                        <button class="delete-image" data-id="<?= $media['id'] ?>" style="margin-top: 0.5rem; background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; width: 100%;">Löschen</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <script>
            document.getElementById('image-upload').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('image', file);

                const statusDiv = document.getElementById('upload-status');
                statusDiv.style.display = 'block';
                statusDiv.style.background = '#ffc107';
                statusDiv.textContent = 'Uploading...';

                fetch('upload-handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        statusDiv.style.background = '#28a745';
                        statusDiv.textContent = data.message;

                        // Add to gallery
                        const gallery = document.getElementById('gallery');
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.setAttribute('data-media-id', data.id);
                        div.innerHTML = `
                            <img src="${data.url}" alt="Band Foto" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
                            <button class="delete-image" data-id="${data.id}" style="margin-top: 0.5rem; background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; width: 100%;">Löschen</button>
                        `;
                        gallery.appendChild(div);

                        setTimeout(() => { statusDiv.style.display = 'none'; }, 3000);
                    } else {
                        statusDiv.style.background = '#dc3545';
                        statusDiv.textContent = data.error;
                    }
                })
                .catch(error => {
                    statusDiv.style.background = '#dc3545';
                    statusDiv.textContent = 'Upload fehlgeschlagen: ' + error.message;
                });

                e.target.value = '';
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-image')) {
                    if (!confirm('Bild wirklich löschen?')) return;

                    const mediaId = e.target.getAttribute('data-id');
                    const galleryItem = e.target.closest('.gallery-item');

                    fetch('upload-handler.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'media_id=' + mediaId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            galleryItem.remove();
                        } else {
                            alert(data.error);
                        }
                    });
                }
            });
            </script>
        <?php else: ?>
            <p>Du hast noch kein Bandprofil angelegt.</p>
        <?php endif; ?>

        <?php if ($user['role'] === 'kunde'): ?>
            <?php if (isset($_GET['payment_success'])): ?>
                <div class="alert alert-success">Zahlung erfolgreich abgeschlossen! Vielen Dank für Ihre Buchung.</div>
            <?php endif; ?>

            <h2>Meine Anfragen</h2>
            <table class="table">
                <thead><tr><th>Band</th><th>Datum</th><th>Status</th><th>Zahlung</th><th>Aktion</th></tr></thead>
                <tbody>
                    <?php
                    $settings = settings();
                    foreach (userRequests((int) $user['id']) as $request):
                        $bandName = findBand((int) $request['band_id']);

                        // Check payment status
                        $stmt = db()->prepare('SELECT * FROM payments WHERE request_id = :id AND status = "completed"');
                        $stmt->execute([':id' => $request['id']]);
                        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($bandName['name'] ?? 'Band #' . $request['band_id']) ?></td>
                            <td><?= htmlspecialchars($request['event_date']) ?></td>
                            <td><?= htmlspecialchars($request['status']) ?></td>
                            <td>
                                <?php if ($payment): ?>
                                    <span style="color: #28a745;">✓ Bezahlt</span><br>
                                    <small style="color: #666;"><?= formatPrice((int) $payment['total_amount']) ?></small>
                                <?php else: ?>
                                    <span style="color: #dc3545;">Ausstehend</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$payment && $settings['paypal_enabled'] === '1'): ?>
                                    <a href="paypal-checkout.php?request_id=<?= $request['id'] ?>" class="badge" style="background: #0070ba; color: white; text-decoration: none;">
                                        PayPal bezahlen
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
