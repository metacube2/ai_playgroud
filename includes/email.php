<?php
require_once __DIR__ . '/config.php';

function sendEmail(string $to, string $subject, string $message, bool $isHtml = true): bool
{
    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }

    $entry = sprintf("%s\nTo: %s\nSubject: %s\n%s\n---\n", date('c'), $to, $subject, $message);
    file_put_contents($logDir . '/mail.log', $entry, FILE_APPEND);

    $headers = [
        'From: ' . SITE_NAME . ' <' . SUPPORT_EMAIL . '>',
        'Reply-To: ' . SUPPORT_EMAIL,
        'X-Mailer: PHP/' . phpversion(),
        'MIME-Version: 1.0'
    ];

    if ($isHtml) {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
    } else {
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    }

    return mail($to, $subject, $message, implode("\r\n", $headers));
}

function sendBookingRequestEmail(array $band, array $requestData, ?array $customer = null): bool
{
    $bandEmail = $band['email'] ?? 'info@' . preg_replace('/\s+/', '', strtolower($band['name'])) . '.ch';

    $subject = 'Neue Buchungsanfrage für ' . $band['name'];

    $message = emailTemplate('booking_request', [
        'band_name' => $band['name'],
        'event_date' => date('d.m.Y', strtotime($requestData['event_date'])),
        'location' => $requestData['location'],
        'event_type' => $requestData['event_type'] ?: 'Nicht angegeben',
        'budget' => $requestData['budget'] ? formatPrice($requestData['budget']) : 'Nicht angegeben',
        'message' => $requestData['message'] ?: 'Keine Nachricht',
        'customer_name' => $customer['name'] ?? 'Gast',
        'customer_email' => $customer['email'] ?? 'Keine Email angegeben',
    ]);

    return sendEmail($bandEmail, $subject, $message);
}

function sendBookingConfirmationEmail(string $customerEmail, array $band, array $requestData): bool
{
    $subject = 'Ihre Anfrage an ' . $band['name'] . ' wurde gesendet';

    $message = emailTemplate('booking_confirmation', [
        'band_name' => $band['name'],
        'event_date' => date('d.m.Y', strtotime($requestData['event_date'])),
        'location' => $requestData['location'],
        'site_name' => SITE_NAME,
    ]);

    return sendEmail($customerEmail, $subject, $message);
}

function emailTemplate(string $templateName, array $data): string
{
    $templates = [
        'booking_request' => '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #f4b807; padding: 20px; text-align: center; }
                    .content { background: #fff; padding: 20px; border: 1px solid #ddd; }
                    .info-row { margin: 10px 0; padding: 10px; background: #f9f9f9; }
                    .label { font-weight: bold; color: #666; }
                    .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1 style="margin:0; color: #fff;">🎸 Neue Buchungsanfrage</h1>
                    </div>
                    <div class="content">
                        <p>Hallo <strong>' . htmlspecialchars($data['band_name']) . '</strong>,</p>
                        <p>Sie haben eine neue Buchungsanfrage erhalten:</p>

                        <div class="info-row">
                            <span class="label">Event-Datum:</span> ' . htmlspecialchars($data['event_date']) . '
                        </div>
                        <div class="info-row">
                            <span class="label">Ort:</span> ' . htmlspecialchars($data['location']) . '
                        </div>
                        <div class="info-row">
                            <span class="label">Event-Typ:</span> ' . htmlspecialchars($data['event_type']) . '
                        </div>
                        <div class="info-row">
                            <span class="label">Budget:</span> ' . htmlspecialchars($data['budget']) . '
                        </div>
                        <div class="info-row">
                            <span class="label">Nachricht:</span><br>' . nl2br(htmlspecialchars($data['message'])) . '
                        </div>

                        <h3>Kontaktdaten:</h3>
                        <div class="info-row">
                            <span class="label">Name:</span> ' . htmlspecialchars($data['customer_name']) . '
                        </div>
                        <div class="info-row">
                            <span class="label">Email:</span> <a href="mailto:' . htmlspecialchars($data['customer_email']) . '">' . htmlspecialchars($data['customer_email']) . '</a>
                        </div>

                        <p style="margin-top: 20px;">
                            Bitte kontaktieren Sie den Kunden direkt, um die Details zu besprechen.
                        </p>
                    </div>
                    <div class="footer">
                        Gesendet von ' . SITE_NAME . ' - Ihre Band-Vermittlungsplattform
                    </div>
                </div>
            </body>
            </html>
        ',

        'booking_confirmation' => '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #f4b807; padding: 20px; text-align: center; }
                    .content { background: #fff; padding: 20px; border: 1px solid #ddd; }
                    .info-row { margin: 10px 0; padding: 10px; background: #f9f9f9; }
                    .label { font-weight: bold; color: #666; }
                    .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                    .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 15px 0; border-radius: 4px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1 style="margin:0; color: #fff;">✅ Anfrage gesendet</h1>
                    </div>
                    <div class="content">
                        <div class="success">
                            <strong>Ihre Anfrage wurde erfolgreich gesendet!</strong>
                        </div>

                        <p>Vielen Dank für Ihre Anfrage an <strong>' . htmlspecialchars($data['band_name']) . '</strong>.</p>

                        <h3>Details Ihrer Anfrage:</h3>
                        <div class="info-row">
                            <span class="label">Event-Datum:</span> ' . htmlspecialchars($data['event_date']) . '
                        </div>
                        <div class="info-row">
                            <span class="label">Ort:</span> ' . htmlspecialchars($data['location']) . '
                        </div>

                        <p style="margin-top: 20px;">
                            Die Band wird sich in Kürze bei Ihnen melden. Bitte überprüfen Sie auch Ihren Spam-Ordner.
                        </p>

                        <p>
                            Bei Fragen können Sie uns jederzeit unter <a href="mailto:' . SUPPORT_EMAIL . '">' . SUPPORT_EMAIL . '</a> erreichen.
                        </p>
                    </div>
                    <div class="footer">
                        Vielen Dank, dass Sie ' . htmlspecialchars($data['site_name']) . ' nutzen!
                    </div>
                </div>
            </body>
            </html>
        ',
    ];

    return $templates[$templateName] ?? '';
}
