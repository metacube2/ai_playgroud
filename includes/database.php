<?php
require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'sqlite:' . DB_PATH;
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    initializeDatabase($pdo);

    return $pdo;
}

function initializeDatabase(PDO $pdo): void
{
    $schema = file_get_contents(__DIR__ . '/../database.sql');
    $pdo->exec($schema);

    ensureBandContactEmailColumn($pdo);

    seedData($pdo);
}

function ensureBandContactEmailColumn(PDO $pdo): void
{
    $columns = $pdo->query('PRAGMA table_info(bands)')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if (($column['name'] ?? '') === 'contact_email') {
            return;
        }
    }
    $pdo->exec('ALTER TABLE bands ADD COLUMN contact_email TEXT');
}

function seedData(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $now = (new DateTimeImmutable())->format('c');
    $password = password_hash('secret123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, verified, verification_token, created_at)
        VALUES (:name, :email, :password, :role, :verified, :token, :created)');

    $stmt->execute([
        ':name' => 'Admin',
        ':email' => 'admin@getyourband.ch',
        ':password' => $password,
        ':role' => 'admin',
        ':verified' => 1,
        ':token' => null,
        ':created' => $now,
    ]);

    $stmt->execute([
        ':name' => 'Maya Keller',
        ':email' => 'maya@getyourband.ch',
        ':password' => $password,
        ':role' => 'band',
        ':verified' => 1,
        ':token' => null,
        ':created' => $now,
    ]);

    $stmt->execute([
        ':name' => 'David Graf',
        ':email' => 'david@example.com',
        ':password' => $password,
        ':role' => 'kunde',
        ':verified' => 1,
        ':token' => null,
        ':created' => $now,
    ]);

    $bands = [
        [
            'user_id' => 2,
            'name' => 'Neon Groove Kollektiv',
            'city' => 'Zürich',
            'genre' => 'Funk / Soul',
            'price' => 4200,
            'description' => '7-köpfige Funk- und Soulband mit knalligem Brass-Sound und interaktiver Show.',
            'status' => 'aktiv',
            'style_tags' => 'Funk,Retro,Showband',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'contact_email' => 'booking@neongroove.ch',
        ],
        [
            'user_id' => null,
            'name' => 'Sonnenblitz Orchester',
            'city' => 'Bern',
            'genre' => 'Pop / Party',
            'price' => 3700,
            'description' => 'Party-Coverband mit LED-Lichtshow und zweistimmigem Gesang.',
            'status' => 'aktiv',
            'style_tags' => 'Pop,Party,LED',
            'video_url' => 'https://www.youtube.com/embed/5NV6Rdv1a3I',
            'contact_email' => 'hello@sonnenblitz.ch',
        ],
    ];

    $bandStmt = $pdo->prepare('INSERT INTO bands (user_id, name, city, genre, price, description, status, style_tags, video_url, contact_email)
        VALUES (:user_id, :name, :city, :genre, :price, :description, :status, :style_tags, :video_url, :contact_email)');

    foreach ($bands as $band) {
        $bandStmt->execute([
            ':user_id' => $band['user_id'],
            ':name' => $band['name'],
            ':city' => $band['city'],
            ':genre' => $band['genre'],
            ':price' => $band['price'],
            ':description' => $band['description'],
            ':status' => $band['status'],
            ':style_tags' => $band['style_tags'],
            ':video_url' => $band['video_url'],
            ':contact_email' => $band['contact_email'],
        ]);
        $bandId = (int) $pdo->lastInsertId();

        $mediaStmt = $pdo->prepare('INSERT INTO band_media (band_id, type, url) VALUES (:band_id, :type, :url)');
        $mediaStmt->execute([':band_id' => $bandId, ':type' => 'image', ':url' => 'https://images.unsplash.com/photo-1507878866276-a947ef722fee']);
        $mediaStmt->execute([':band_id' => $bandId, ':type' => 'image', ':url' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef']);

        $availStmt = $pdo->prepare('INSERT INTO band_availability (band_id, event_date, status) VALUES (:band_id, :event_date, :status)');
        for ($i = 0; $i < 4; $i++) {
            $availStmt->execute([
                ':band_id' => $bandId,
                ':event_date' => (new DateTimeImmutable('+' . ($i + 1) * 7 . ' days'))->format('Y-m-d'),
                ':status' => $i % 2 === 0 ? 'frei' : 'option',
            ]);
        }
    }

    $pdo->exec("INSERT INTO settings (key, value) VALUES ('paypal_enabled', '0'), ('service_fee', '8')");

    $requestStmt = $pdo->prepare('INSERT INTO requests (band_id, user_id, event_date, location, budget, event_type, message, status, created_at)
        VALUES (:band_id, :user_id, :event_date, :location, :budget, :event_type, :message, :status, :created)');

    $requestStmt->execute([
        ':band_id' => 1,
        ':user_id' => 3,
        ':event_date' => (new DateTimeImmutable('+30 days'))->format('Y-m-d'),
        ':location' => 'Basel',
        ':budget' => 5000,
        ':event_type' => 'Firmenfeier',
        ':message' => 'Wir suchen einen funky Act für die Sommerparty.',
        ':status' => 'bestätigt',
        ':created' => $now,
    ]);

    $pdo->exec("INSERT INTO reviews (band_id, user_id, rating, comment, status, created_at) VALUES (1, 3, 5, 'Mega Stimmung und super Show!', 'freigegeben', datetime('now'))");
}
