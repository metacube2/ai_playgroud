<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
requireLogin();

header('Content-Type: application/json');

$user = currentUser();
if ($user['role'] !== 'band') {
    http_response_code(403);
    echo json_encode(['error' => 'Nur Bands können Bilder hochladen']);
    exit;
}

// Get band
$stmt = db()->prepare('SELECT * FROM bands WHERE user_id = :id');
$stmt->execute([':id' => $user['id']]);
$band = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$band) {
    http_response_code(404);
    echo json_encode(['error' => 'Kein Bandprofil gefunden']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Ungültiger Dateityp. Erlaubt sind: JPG, PNG, GIF, WEBP']);
        exit;
    }

    if ($file['size'] > $maxSize) {
        http_response_code(400);
        echo json_encode(['error' => 'Datei zu groß (max 5MB)']);
        exit;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(500);
        echo json_encode(['error' => 'Upload-Fehler']);
        exit;
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'band_' . $band['id'] . '_' . uniqid() . '.' . $extension;
    $uploadPath = __DIR__ . '/storage/uploads/bands/' . $filename;

    // Move file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Datei konnte nicht gespeichert werden']);
        exit;
    }

    // Save to database
    $url = 'storage/uploads/bands/' . $filename;
    $stmt = db()->prepare('INSERT INTO band_media (band_id, type, url) VALUES (:band_id, :type, :url)');
    $stmt->execute([
        ':band_id' => $band['id'],
        ':type' => 'image',
        ':url' => $url
    ]);

    $mediaId = (int) db()->lastInsertId();

    echo json_encode([
        'success' => true,
        'id' => $mediaId,
        'url' => $url,
        'message' => 'Bild erfolgreich hochgeladen'
    ]);
    exit;
}

// Delete image
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $deleteData);
    $mediaId = (int) ($deleteData['media_id'] ?? 0);

    if (!$mediaId) {
        http_response_code(400);
        echo json_encode(['error' => 'Keine Media-ID angegeben']);
        exit;
    }

    // Check ownership
    $stmt = db()->prepare('SELECT * FROM band_media WHERE id = :id AND band_id = :band_id');
    $stmt->execute([':id' => $mediaId, ':band_id' => $band['id']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$media) {
        http_response_code(404);
        echo json_encode(['error' => 'Bild nicht gefunden']);
        exit;
    }

    // Delete file
    $filePath = __DIR__ . '/' . $media['url'];
    if (file_exists($filePath) && strpos($media['url'], 'storage/uploads/') === 0) {
        unlink($filePath);
    }

    // Delete from database
    $stmt = db()->prepare('DELETE FROM band_media WHERE id = :id');
    $stmt->execute([':id' => $mediaId]);

    echo json_encode(['success' => true, 'message' => 'Bild gelöscht']);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Ungültige Anfrage']);
