<?php
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function currentUser(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    static $user;
    if ($user) {
        return $user;
    }

    $stmt = db()->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    return $user;
}

function login(string $email, string $password): bool
{
    $stmt = db()->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }
    if ((int) $user['verified'] !== 1) {
        throw new RuntimeException('Bitte verifiziere zuerst deine E-Mail.');
    }
    $_SESSION['user_id'] = $user['id'];
    return true;
}

function logout(): void
{
    session_destroy();
}

function register(array $data): array
{
    $token = bin2hex(random_bytes(16));
    $stmt = db()->prepare('INSERT INTO users (name, email, password, role, city, verified, verification_token, created_at)
        VALUES (:name, :email, :password, :role, :city, 0, :token, :created)');
    $stmt->execute([
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ':role' => $data['role'],
        ':city' => $data['city'] ?? null,
        ':token' => $token,
        ':created' => (new DateTimeImmutable())->format('c'),
    ]);
    $userId = (int) db()->lastInsertId();

    if ($data['role'] === 'band') {
        $band = db()->prepare('INSERT INTO bands (user_id, name, city, genre, price, description, status, contact_email)
            VALUES (:user_id, :name, :city, :genre, :price, :description, :status, :contact_email)');
        $bandEmail = $data['band_email'] ?? $data['email'];
        $band->execute([
            ':user_id' => $userId,
            ':name' => $data['band_name'] ?? 'Neue Band',
            ':city' => $data['city'] ?? '',
            ':genre' => $data['genre'] ?? '',
            ':price' => 0,
            ':description' => 'Bitte Profil ergänzen.',
            ':status' => 'prüfung',
            ':contact_email' => $bandEmail,
        ]);
    }

    return ['token' => $token];
}

function requireLogin(): void
{
    if (!currentUser()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin(): void
{
    $user = currentUser();
    if (!$user || $user['role'] !== 'admin') {
        http_response_code(403);
        echo 'Keine Berechtigung';
        exit;
    }
}
