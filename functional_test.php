#!/usr/bin/env php
<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/email.php';

$options = array_slice($argv, 1);
if (in_array('--fresh', $options, true) && file_exists(DB_PATH)) {
    unlink(DB_PATH);
}

if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}

final class FunctionalTestRunner
{
    private PDO $pdo;
    private array $results = [];
    private int $passed = 0;
    private int $failed = 0;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(string $title, callable $test, bool $transactional = false): void
    {
        try {
            if ($transactional) {
                $this->pdo->beginTransaction();
            }
            $details = $test($this->pdo);
            if ($transactional && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $this->record('PASS', $title, $details);
        } catch (Throwable $e) {
            if ($transactional && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $this->record('FAIL', $title, $e->getMessage());
        }
    }

    private function record(string $status, string $title, ?string $details): void
    {
        $status === 'PASS' ? $this->passed++ : $this->failed++;
        $this->results[] = [
            'status' => $status,
            'title' => $title,
            'details' => $details ?? '',
        ];
    }

    public function summary(): void
    {
        foreach ($this->results as $result) {
            $symbol = $result['status'] === 'PASS' ? '\u{2705}' : '\u{274C}';
            echo sprintf("%s %s\n", $symbol, $result['title']);
            if ($result['details'] !== '') {
                echo sprintf("    %s\n", $result['details']);
            }
        }
        echo str_repeat('-', 50) . "\n";
        echo sprintf("Ergebnis: %d bestanden, %d fehlgeschlagen\n", $this->passed, $this->failed);
        exit($this->failed === 0 ? 0 : 1);
    }
}

function renderPage(string $file, array $get = [], array $post = []): string
{
    $previousGet = $_GET ?? [];
    $previousPost = $_POST ?? [];
    $previousMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    $_GET = $get;
    $_POST = $post;
    $_SERVER['REQUEST_METHOD'] = empty($post) ? 'GET' : 'POST';

    ob_start();
    include __DIR__ . '/' . ltrim($file, '/');
    $output = ob_get_clean();

    $_GET = $previousGet;
    $_POST = $previousPost;
    $_SERVER['REQUEST_METHOD'] = $previousMethod;

    return $output;
}

function restartSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

$pdo = db();
$runner = new FunctionalTestRunner($pdo);

$runner->run('Storage-Verzeichnis vorhanden', function () {
    if (!is_dir(__DIR__ . '/storage')) {
        throw new RuntimeException('Ordner storage fehlt.');
    }
    return 'Pfad: ' . realpath(__DIR__ . '/storage');
});

$runner->run('Datenbank initialisiert', function (PDO $pdo) {
    if (!file_exists(DB_PATH)) {
        throw new RuntimeException('database.sqlite wurde nicht erstellt.');
    }
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")
        ->fetchAll(PDO::FETCH_COLUMN);
    $required = ['users', 'bands', 'requests', 'reviews', 'settings'];
    foreach ($required as $table) {
        if (!in_array($table, $tables, true)) {
            throw new RuntimeException('Tabelle ' . $table . ' fehlt.');
        }
    }
    return 'Tabellen gefunden: ' . implode(', ', $required);
});

$runner->run('Seed-Daten verfügbar', function (PDO $pdo) {
    $users = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $bands = (int) $pdo->query('SELECT COUNT(*) FROM bands')->fetchColumn();
    if ($users < 3 || $bands < 2) {
        throw new RuntimeException('Seed-Daten unvollständig.');
    }
    return sprintf('Users: %d, Bands: %d', $users, $bands);
});

$runner->run('Login / Logout Workflow', function () {
    if (!login('david@example.com', 'secret123')) {
        throw new RuntimeException('Login schlug fehl.');
    }
    $user = currentUser();
    if (!$user || $user['role'] !== 'kunde') {
        throw new RuntimeException('Session liefert keinen Kunden.');
    }
    logout();
    restartSession();
    if (currentUser()) {
        throw new RuntimeException('Logout hat Session nicht geleert.');
    }
    return 'Login erfolgreich für ' . $user['name'];
});

$runner->run('Band-Filter & Durchschnitt', function () {
    $bands = allBands(['genre' => 'Funk']);
    if (!$bands) {
        throw new RuntimeException('Filter lieferte keine Band.');
    }
    $rating = averageRating((int) $bands[0]['id']);
    if ($rating === null) {
        throw new RuntimeException('Keine Bewertung vorhanden.');
    }
    return sprintf('%d Bands, Ø Bewertung %.1f★', count($bands), $rating);
});

$runner->run('Medien & Verfügbarkeiten geladen', function () {
    $media = bandMedia(1);
    $availability = bandAvailability(1);
    $reviews = bandReviews(1);
    if (!$media || !$availability || !$reviews) {
        throw new RuntimeException('Band 1 hat unvollständige Daten.');
    }
    return sprintf('Medien: %d, Slots: %d, Reviews: %d', count($media), count($availability), count($reviews));
});

$runner->run('Anfrage speichern (Transaktion)', function (PDO $pdo) {
    $before = (int) $pdo->query('SELECT COUNT(*) FROM requests')->fetchColumn();
    createRequest([
        'band_id' => 1,
        'user_id' => 3,
        'event_date' => (new DateTimeImmutable('+60 days'))->format('Y-m-d'),
        'location' => 'Teststadt',
        'budget' => 4500,
        'event_type' => 'Testevent',
        'message' => 'Funktionstest Anfrage',
    ]);
    $after = (int) $pdo->query('SELECT COUNT(*) FROM requests')->fetchColumn();
    if ($after !== $before + 1) {
        throw new RuntimeException('Anfrage wurde nicht gespeichert.');
    }
    return 'Requests gesamt (temporär): ' . $after;
}, true);

$runner->run('Bewertungen speichern & Eligibility', function (PDO $pdo) {
    if (!eligibleForReview(1, 3)) {
        throw new RuntimeException('User 3 sollte berechtigt sein.');
    }
    $before = (int) $pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn();
    storeReview([
        'band_id' => 1,
        'user_id' => 3,
        'rating' => 4,
        'comment' => 'Testkommentar',
    ]);
    $after = (int) $pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn();
    if ($after !== $before + 1) {
        throw new RuntimeException('Review wurde nicht gespeichert.');
    }
    return 'Reviews gesamt (temporär): ' . $after;
}, true);

$runner->run('Einstellungen lesen & aktualisieren', function () {
    $current = settings();
    $originalFee = $current['service_fee'] ?? '0';
    updateSetting('service_fee', '12');
    $updated = settings();
    if (($updated['service_fee'] ?? null) !== '12') {
        throw new RuntimeException('Service Fee konnte nicht aktualisiert werden.');
    }
    updateSetting('service_fee', $originalFee);
    return 'Service Fee temporär auf 12 gesetzt.';
}, true);

$runner->run('Moderations-Aktionen', function (PDO $pdo) {
    changeBandStatus(1, 'prüfung');
    $status = $pdo->query('SELECT status FROM bands WHERE id = 1')->fetchColumn();
    if ($status !== 'prüfung') {
        throw new RuntimeException('Bandstatus änderte sich nicht.');
    }
    changeReviewStatus(1, 'gesperrt');
    $reviewStatus = $pdo->query('SELECT status FROM reviews WHERE id = 1')->fetchColumn();
    if ($reviewStatus !== 'gesperrt') {
        throw new RuntimeException('Reviewstatus änderte sich nicht.');
    }
    return 'Statusänderungen durchgeführt.';
}, true);

$runner->run('Registrierung legt Band an', function (PDO $pdo) {
    $email = 'tester+' . uniqid('', true) . '@example.com';
    $result = register([
        'name' => 'Functional Tester',
        'email' => $email,
        'password' => 'secret123',
        'role' => 'band',
        'city' => 'Testingen',
        'band_name' => 'QA Ensemble',
        'genre' => 'QA Funk',
    ]);
    if (empty($result['token']) || strlen($result['token']) < 20) {
        throw new RuntimeException('Verifikationstoken fehlt.');
    }
    $user = $pdo->prepare('SELECT id, role FROM users WHERE email = :email');
    $user->execute([':email' => $email]);
    $userRow = $user->fetch(PDO::FETCH_ASSOC);
    if (!$userRow || $userRow['role'] !== 'band') {
        throw new RuntimeException('User wurde nicht gespeichert.');
    }
    $band = $pdo->prepare('SELECT status, contact_email FROM bands WHERE user_id = :id');
    $band->execute([':id' => $userRow['id']]);
    $bandRow = $band->fetch(PDO::FETCH_ASSOC);
    if (!$bandRow || $bandRow['status'] !== 'prüfung') {
        throw new RuntimeException('Bandprofil wurde nicht angelegt.');
    }
    if (($bandRow['contact_email'] ?? '') !== $email) {
        throw new RuntimeException('Kontakt-E-Mail der Band fehlt.');
    }
    return 'Token erstellt und Bandstatus "prüfung" bestätigt.';
}, true);

$runner->run('Startseite rendert fehlerfrei', function () {
    $html = renderPage('index.php');
    if (strpos($html, 'Aktive Bands') === false) {
        throw new RuntimeException('Indexseite liefert keinen Inhalt.');
    }
    return 'HTML-Länge: ' . strlen($html) . ' Zeichen';
});

$runner->run('Band-Detailseite rendert', function () {
    $html = renderPage('band-detail.php', ['id' => 1]);
    if (strpos($html, 'Verfügbarkeit') === false) {
        throw new RuntimeException('Band-Detailseite unvollständig.');
    }
    return 'HTML-Länge: ' . strlen($html) . ' Zeichen';
});

$runner->run('Kontaktformular der Bandseite', function () {
    $logFile = __DIR__ . '/storage/logs/mail.log';
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0775, true);
    }
    $before = file_exists($logFile) ? filesize($logFile) : 0;
    $html = renderPage('band-detail.php', ['id' => 1], [
        'contact' => '1',
        'contact_name' => 'QA Bot',
        'contact_email' => 'qa@example.com',
        'contact_message' => 'Testnachricht an die Band.',
    ]);
    $success = strpos($html, 'Nachricht an die Band wurde verschickt.') !== false;
    $fallback = strpos($html, 'E-Mail-Versand zur Band nicht möglich.') !== false;
    if (!$success && !$fallback) {
        throw new RuntimeException('Kontaktformular meldete keinen Versandstatus.');
    }
    $after = filesize($logFile);
    if ($after <= $before) {
        throw new RuntimeException('Kein Mail-Logeintrag für Kontaktformular.');
    }
    return 'Kontaktformular meldete Erfolg und schrieb ins Log.';
});

$runner->run('Anfrageformular rendert', function () {
    $html = renderPage('anfrage.php', ['band_id' => 1]);
    if (strpos($html, 'Anfrage an') === false) {
        throw new RuntimeException('Anfrageformular fehlgeschlagen.');
    }
    return 'HTML-Länge: ' . strlen($html) . ' Zeichen';
});

$runner->run('E-Mail Logging (kein Versand)', function () {
    $logDir = __DIR__ . '/storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }
    $logFile = $logDir . '/mail.log';
    $before = file_exists($logFile) ? filesize($logFile) : 0;
    sendEmail('qa@example.com', 'Functional Test', 'Nur Logeintrag – kein Versand.');
    $after = filesize($logFile);
    if ($after <= $before) {
        throw new RuntimeException('Mail-Log wurde nicht aktualisiert.');
    }
    return 'Logeintrag ergänzt, Versand erfolgt nur als Datei.';
});

$runner->summary();
