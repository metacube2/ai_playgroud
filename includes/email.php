<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../auroraalt.php';

function sendEmail(string $to, string $subject, string $message): void
{
    $sent = auroraalt_mail($to, $subject, $message, [
        'from_email' => SUPPORT_EMAIL,
        'from_name' => SITE_NAME,
    ]);

    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }
    $entry = sprintf(
        "%s\nTo: %s\nSubject: %s\nStatus: %s\n%s\n---\n",
        date('c'),
        $to,
        $subject,
        $sent ? 'SENT' : 'FAILED',
        $message
    );
    file_put_contents($logDir . '/mail.log', $entry, FILE_APPEND);
}
