<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';

$requestId = isset($_GET['request_id']) ? (int) $_GET['request_id'] : 0;
if (!$requestId) {
    http_response_code(400);
    echo 'Keine Anfrage-ID angegeben';
    exit;
}

$user = currentUser();

// Get request details
$stmt = db()->prepare('SELECT r.*, b.name as band_name, b.price as band_price
    FROM requests r
    JOIN bands b ON b.id = r.band_id
    WHERE r.id = :id AND r.user_id = :user_id');
$stmt->execute([':id' => $requestId, ':user_id' => $user['id']]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    http_response_code(404);
    echo 'Anfrage nicht gefunden';
    exit;
}

$settings = settings();
if ($settings['paypal_enabled'] !== '1') {
    http_response_code(403);
    echo 'PayPal-Zahlungen sind derzeit nicht aktiviert';
    exit;
}

// Calculate amounts
$bandPrice = (int) $request['band_price'];
$serviceFeePercent = (float) $settings['service_fee'];
$serviceFee = $bandPrice * ($serviceFeePercent / 100);
$totalAmount = $bandPrice + $serviceFee;

// Check if already paid
$stmt = db()->prepare('SELECT * FROM payments WHERE request_id = :id AND status = "completed"');
$stmt->execute([':id' => $requestId]);
$existingPayment = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingPayment) {
    $message = 'Diese Buchung wurde bereits bezahlt.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Zahlung – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <a class="badge" href="profil.php">← Zurück zum Profil</a>
        <h1>Zahlung für Buchung</h1>
    </header>
    <main style="max-width: 600px; margin: 0 auto;">
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php else: ?>
            <h2>Buchungsdetails</h2>
            <table class="table" style="margin-bottom: 2rem;">
                <tr><td><strong>Band:</strong></td><td><?= htmlspecialchars($request['band_name']) ?></td></tr>
                <tr><td><strong>Event-Datum:</strong></td><td><?= htmlspecialchars($request['event_date']) ?></td></tr>
                <tr><td><strong>Location:</strong></td><td><?= htmlspecialchars($request['location']) ?></td></tr>
                <tr><td><strong>Event-Typ:</strong></td><td><?= htmlspecialchars($request['event_type']) ?></td></tr>
            </table>

            <h2>Zahlungsübersicht</h2>
            <table class="table" style="margin-bottom: 2rem;">
                <tr><td><strong>Band-Gage:</strong></td><td><?= formatPrice($bandPrice) ?></td></tr>
                <tr><td><strong>Service Fee (<?= htmlspecialchars($serviceFeePercent) ?>%):</strong></td><td><?= formatPrice((int) $serviceFee) ?></td></tr>
                <tr style="border-top: 2px solid #ffb703;"><td><strong>Gesamtbetrag:</strong></td><td><strong><?= formatPrice((int) $totalAmount) ?></strong></td></tr>
            </table>

            <div id="payment-status" style="display:none; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;"></div>

            <!-- PayPal Button Container -->
            <div id="paypal-button-container" style="margin: 2rem 0;"></div>

            <p style="color: #666; font-size: 0.875rem; margin-top: 2rem;">
                <strong>Hinweis:</strong> Dies ist eine Demo-Integration. Für die Produktivumgebung benötigen Sie echte PayPal API-Credentials.
                Aktuell wird im Sandbox-Modus gearbeitet.
            </p>
        <?php endif; ?>
    </main>

    <?php if (!isset($message)): ?>
    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&currency=CHF"></script>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?= number_format($totalAmount, 2, '.', '') ?>',
                            currency_code: 'CHF',
                            breakdown: {
                                item_total: {
                                    value: '<?= number_format($bandPrice, 2, '.', '') ?>',
                                    currency_code: 'CHF'
                                },
                                tax_total: {
                                    value: '<?= number_format($serviceFee, 2, '.', '') ?>',
                                    currency_code: 'CHF'
                                }
                            }
                        },
                        description: 'Buchung: <?= htmlspecialchars($request['band_name']) ?> - <?= htmlspecialchars($request['event_date']) ?>'
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Save payment to database
                    const statusDiv = document.getElementById('payment-status');
                    statusDiv.style.display = 'block';
                    statusDiv.style.background = '#28a745';
                    statusDiv.style.color = 'white';
                    statusDiv.textContent = 'Zahlung erfolgreich! Verarbeite Transaktion...';

                    fetch('paypal-process.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            request_id: <?= $requestId ?>,
                            amount: <?= $bandPrice ?>,
                            service_fee: <?= number_format($serviceFee, 2, '.', '') ?>,
                            total_amount: <?= number_format($totalAmount, 2, '.', '') ?>,
                            paypal_order_id: data.orderID,
                            paypal_payer_id: details.payer.payer_id
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            statusDiv.textContent = 'Zahlung erfolgreich abgeschlossen! Sie werden weitergeleitet...';
                            setTimeout(() => {
                                window.location.href = 'profil.php?payment_success=1';
                            }, 2000);
                        } else {
                            statusDiv.style.background = '#dc3545';
                            statusDiv.textContent = 'Fehler beim Speichern der Zahlung: ' + result.error;
                        }
                    });
                });
            },
            onError: function(err) {
                const statusDiv = document.getElementById('payment-status');
                statusDiv.style.display = 'block';
                statusDiv.style.background = '#dc3545';
                statusDiv.style.color = 'white';
                statusDiv.textContent = 'Fehler bei der Zahlung: ' + err;
            }
        }).render('#paypal-button-container');
    </script>
    <?php endif; ?>
</body>
</html>
