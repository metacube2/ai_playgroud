<?php
function sendEmail(string $to, string $subject, string $message): void
{
    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }
    $entry = sprintf("%s\nTo: %s\nSubject: %s\n%s\n---\n", date('c'), $to, $subject, $message);
    file_put_contents($logDir . '/mail.log', $entry, FILE_APPEND);
}
