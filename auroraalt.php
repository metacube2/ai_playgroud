<?php
/**
 * AuroraALT Mailer - kapselt den HTML-Mailversand.
 */
if (!function_exists('auroraalt_mail')) {
    function auroraalt_mail(string $to, string $subject, string $htmlBody, array $options = []): bool
    {
        $fromEmail = $options['from_email'] ?? ($options['reply_to'] ?? ini_get('sendmail_from') ?: 'no-reply@getyourband.ch');
        $fromName = $options['from_name'] ?? 'GetYourBand';
        $replyTo = $options['reply_to'] ?? $fromEmail;

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . (function_exists('mb_encode_mimeheader') ? mb_encode_mimeheader($fromName) : $fromName) . " <{$fromEmail}>",
            'Reply-To: ' . $replyTo,
        ];

        if (!empty($options['cc'])) {
            $headers[] = 'Cc: ' . $options['cc'];
        }

        if (!empty($options['bcc'])) {
            $headers[] = 'Bcc: ' . $options['bcc'];
        }

        $sendmailPath = ini_get('sendmail_path');
        $transportAvailable = true;
        if (stripos(PHP_OS, 'WIN') !== 0) {
            $binary = trim(strtok((string) $sendmailPath, ' '));
            if ($binary && !is_executable($binary)) {
                $transportAvailable = false;
            }
        }

        if (!$transportAvailable) {
            return false;
        }

        $additionalHeaders = implode("\r\n", $headers);
        $encodedSubject = function_exists('mb_encode_mimeheader')
            ? mb_encode_mimeheader($subject, 'UTF-8')
            : $subject;

        return @mail($to, $encodedSubject, $htmlBody, $additionalHeaders);
    }
}
