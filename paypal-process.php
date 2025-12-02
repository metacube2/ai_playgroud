<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/email.php';
requireLogin();

header('Content-Type: application/json');

$user = currentUser();

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['request_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Ungültige Anfrage']);
    exit;
}

$requestId = (int) $input['request_id'];
$amount = (float) $input['amount'];
$serviceFee = (float) $input['service_fee'];
$totalAmount = (float) $input['total_amount'];
$paypalOrderId = $input['paypal_order_id'] ?? '';
$paypalPayerId = $input['paypal_payer_id'] ?? '';

// Verify request belongs to user
$stmt = db()->prepare('SELECT r.*, b.name as band_name, b.user_id as band_user_id
    FROM requests r
    JOIN bands b ON b.id = r.band_id
    WHERE r.id = :id AND r.user_id = :user_id');
$stmt->execute([':id' => $requestId, ':user_id' => $user['id']]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    http_response_code(404);
    echo json_encode(['error' => 'Anfrage nicht gefunden']);
    exit;
}

// Check if already paid
$stmt = db()->prepare('SELECT * FROM payments WHERE request_id = :id AND status = "completed"');
$stmt->execute([':id' => $requestId]);
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    http_response_code(400);
    echo json_encode(['error' => 'Diese Buchung wurde bereits bezahlt']);
    exit;
}

try {
    // Save payment
    $stmt = db()->prepare('INSERT INTO payments (request_id, amount, service_fee, total_amount, paypal_order_id, paypal_payer_id, status, completed_at)
        VALUES (:request_id, :amount, :service_fee, :total_amount, :paypal_order_id, :paypal_payer_id, :status, :completed_at)');

    $stmt->execute([
        ':request_id' => $requestId,
        ':amount' => $amount,
        ':service_fee' => $serviceFee,
        ':total_amount' => $totalAmount,
        ':paypal_order_id' => $paypalOrderId,
        ':paypal_payer_id' => $paypalPayerId,
        ':status' => 'completed',
        ':completed_at' => (new DateTimeImmutable())->format('c')
    ]);

    // Update request status to confirmed
    $stmt = db()->prepare('UPDATE requests SET status = :status WHERE id = :id');
    $stmt->execute([':status' => 'bestätigt', ':id' => $requestId]);

    // Send confirmation emails
    sendEmail($user['email'], 'Zahlungsbestätigung',
        'Ihre Zahlung für die Buchung von ' . $request['band_name'] . ' wurde erfolgreich verarbeitet.');

    // Notify band
    if ($request['band_user_id']) {
        $bandUserStmt = db()->prepare('SELECT email FROM users WHERE id = :id');
        $bandUserStmt->execute([':id' => $request['band_user_id']]);
        $bandUser = $bandUserStmt->fetch(PDO::FETCH_ASSOC);

        if ($bandUser) {
            sendEmail($bandUser['email'], 'Neue bezahlte Buchung',
                'Sie haben eine neue bezahlte Buchung für ' . $request['event_date'] . ' erhalten.');
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Zahlung erfolgreich verarbeitet',
        'payment_id' => (int) db()->lastInsertId()
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Fehler beim Speichern der Zahlung: ' . $e->getMessage()]);
}
