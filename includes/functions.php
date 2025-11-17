<?php
require_once __DIR__ . '/database.php';

function allBands(array $filters = []): array
{
    $pdo = db();
    $where = ['status = :status'];
    $params = [':status' => 'aktiv'];

    if (!empty($filters['genre'])) {
        $where[] = 'genre LIKE :genre';
        $params[':genre'] = '%' . $filters['genre'] . '%';
    }

    if (!empty($filters['city'])) {
        $where[] = 'city LIKE :city';
        $params[':city'] = '%' . $filters['city'] . '%';
    }

    $sql = 'SELECT * FROM bands WHERE ' . implode(' AND ', $where) . ' ORDER BY name';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findBand(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM bands WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $band = $stmt->fetch(PDO::FETCH_ASSOC);

    return $band ?: null;
}

function bandMedia(int $bandId): array
{
    $stmt = db()->prepare('SELECT * FROM band_media WHERE band_id = :id');
    $stmt->execute([':id' => $bandId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function bandAvailability(int $bandId): array
{
    $stmt = db()->prepare('SELECT * FROM band_availability WHERE band_id = :id ORDER BY event_date');
    $stmt->execute([':id' => $bandId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function bandReviews(int $bandId): array
{
    $stmt = db()->prepare('SELECT r.*, u.name AS author
        FROM reviews r
        JOIN users u ON u.id = r.user_id
        WHERE r.band_id = :id AND r.status = "freigegeben"
        ORDER BY r.created_at DESC');
    $stmt->execute([':id' => $bandId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function averageRating(int $bandId): ?float
{
    $stmt = db()->prepare('SELECT AVG(rating) FROM reviews WHERE band_id = :id AND status = "freigegeben"');
    $stmt->execute([':id' => $bandId]);
    $value = $stmt->fetchColumn();
    return $value ? round((float) $value, 1) : null;
}

function formatPrice(int $amount): string
{
    return number_format($amount, 0, ',', '.') . ' CHF';
}

function createRequest(array $data): void
{
    $stmt = db()->prepare('INSERT INTO requests (band_id, user_id, event_date, location, budget, event_type, message, status, created_at)
        VALUES (:band_id, :user_id, :event_date, :location, :budget, :event_type, :message, :status, :created_at)');
    $stmt->execute([
        ':band_id' => $data['band_id'],
        ':user_id' => $data['user_id'],
        ':event_date' => $data['event_date'],
        ':location' => $data['location'],
        ':budget' => $data['budget'],
        ':event_type' => $data['event_type'],
        ':message' => $data['message'],
        ':status' => 'neu',
        ':created_at' => (new DateTimeImmutable())->format('c'),
    ]);
}

function userRequests(int $userId): array
{
    $stmt = db()->prepare('SELECT * FROM requests WHERE user_id = :id ORDER BY created_at DESC');
    $stmt->execute([':id' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function storeReview(array $data): void
{
    $stmt = db()->prepare('INSERT INTO reviews (band_id, user_id, rating, comment, status, created_at)
        VALUES (:band_id, :user_id, :rating, :comment, :status, :created_at)');
    $stmt->execute([
        ':band_id' => $data['band_id'],
        ':user_id' => $data['user_id'],
        ':rating' => $data['rating'],
        ':comment' => $data['comment'],
        ':status' => 'wartend',
        ':created_at' => (new DateTimeImmutable())->format('c'),
    ]);
}

function eligibleForReview(int $bandId, int $userId): bool
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM requests WHERE band_id = :band AND user_id = :user AND status = "bestätigt"');
    $stmt->execute([':band' => $bandId, ':user' => $userId]);
    return (int) $stmt->fetchColumn() > 0;
}

function settings(): array
{
    $stmt = db()->query('SELECT key, value FROM settings');
    $data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    return $data ?: ['paypal_enabled' => '0', 'service_fee' => '0'];
}

function updateSetting(string $key, string $value): void
{
    $stmt = db()->prepare('INSERT INTO settings (key, value) VALUES (:key, :value)
        ON CONFLICT(key) DO UPDATE SET value = excluded.value');
    $stmt->execute([':key' => $key, ':value' => $value]);
}

function moderationItems(string $type): array
{
    $pdo = db();
    if ($type === 'bands') {
        return $pdo->query('SELECT * FROM bands WHERE status != "aktiv"')->fetchAll(PDO::FETCH_ASSOC);
    }
    if ($type === 'reviews') {
        return $pdo->query('SELECT r.*, b.name AS band_name, u.name AS author
            FROM reviews r
            JOIN bands b ON b.id = r.band_id
            JOIN users u ON u.id = r.user_id
            WHERE r.status = "wartend"')->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}

function changeBandStatus(int $bandId, string $status): void
{
    $stmt = db()->prepare('UPDATE bands SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $bandId]);
}

function changeReviewStatus(int $reviewId, string $status): void
{
    $stmt = db()->prepare('UPDATE reviews SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $reviewId]);
}
