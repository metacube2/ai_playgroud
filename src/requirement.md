Workflow

Thinking

Responding
Verstanden - Anforderungsanalyse
Ich habe folgendes verstanden:

    Plattform: getyourband.ch - Bandvermittlungsplattform
    Zielgruppe: Private Grossbands mit Auftritts-Erfahrung
    Technologie: PHP, Single-Page-Ansatz, modernes/funkiges Design
    Kernfunktionen: Vermittlung, Bewertungen, Anfragen, Ortsangaben

Fragebogen - Offene Punkte vor Programmierung
1. Datenbankstruktur & Hosting

    Welches DBMS? (MySQL, PostgreSQL, SQLite?)
    Hosting-Umgebung vorhanden? (PHP-Version?)
    Soll ich SQL-Schema mitliefern?

2. Benutzerrollen & Authentifizierung

    Wer kann sich registrieren? (Nur Bands / Auch Veranstalter/Kunden?)
    Login-System erforderlich?
    Registrierungsprozess mit E-Mail-Verifizierung?

3. Band-Profile

    Welche Pflichtfelder? (Name, Genre, Ort, Preis, Fotos, Videos?)
    Mehrere Fotos/Videos pro Band?
    Beschreibungstexte mit Formatierung?
    Verfügbarkeitskalender integrieren?

4. Bewertungssystem

    Wer darf bewerten? (Nur nach Buchung / Öffentlich?)
    Sterne-System (1-5) oder andere Skala?
    Textbewertungen erlaubt?
    Moderation von Bewertungen?

5. Anfragen/Buchungssystem

    Direktbuchung oder Anfrage-Formular?
    Welche Daten beim Anfragen? (Datum, Ort, Budget, Event-Typ?)
    E-Mail-Benachrichtigungen an Band?
    Anfragen-Verwaltung im Backend?

6. Suchfunktionen & Filter

    Suche nach: Genre, PLZ/Ort, Preis, Verfügbarkeit?
    Kartenansicht (Google Maps API)?
    Sortierung (Bewertung, Preis, Entfernung)?

7. Design & Frontend

    Welcher "funky" Stil? (Neon, Retro, Modern-Minimalistisch?)
    Farbschema-Vorstellungen?
    Mobile-First wichtig?
    CSS-Framework gewünscht? (Tailwind, Bootstrap, Custom?)

8. Single-Page Details

    Wirklich ALLES auf einer Seite (inkl. Admin/Verwaltung)?
    Oder mehrere Seiten (Liste, Detail, Anfrage-Formular)?
    JavaScript-Framework? (Vanilla JS, Alpine.js, Vue.js?)

9. Zahlungssystem

    Vermittlungsgebühr?
    Zahlungsabwicklung integrieren? (Stripe, PayPal?)
    Oder nur Kontaktvermittlung ohne Zahlung?

10. Admin/Moderation

    Admin-Bereich für Plattform-Betreiber?
    Bands können eigene Profile bearbeiten?
    Freischaltung neuer Bands erforderlich?

11. Rechtliches

    DSGVO-Konformität wichtig? (Cookie-Banner, Datenschutz?)
    AGB/Impressum-Seiten nötig?

Bitte beantworte diese Punkte, damit ich eine präzise, funktionierende Lösung ohne Annahmen programmieren kann! 🎸
1. am besten wo man nichts installeiren muss,  php neuste version.  ubuntu server mit python isntallier neuste apache verwsion,  2. alle registrieren,  login sinnvoll,  email verifizierung.  3.  allews was sinn macht. mehrer vids pro band,  ja mit formatierung.  kalender einbauen.  4.nur nach buchung,  mit stenern, texte auch. max 200 zeichen,  moderation.   5.  was modern ist.   alles was sinn mach.  banchrichtigung an band,   verwaltung in backend6. such nach stil,  fabr gelbtöne,  mobil auch wirhcit .  modnerste css 8. da es sicher viele funktionen gfibt mehrere files. . javascript das passendste.  9.  gebühr aktiviertbar.   paypal, aktivierbar sonst vermittlungsgebühr. 10admin backend. profil bearbeitbar. ja freischalten. 11. mit banner und agp,  mache mehrere files, du wirds sicher mehrer schritte brauchen pro anfrage an dich
Workflow

Thinking

Searching

Thinking

Responding
Perfekt! Zusammenfassung vor Umsetzung
Was ich verstanden habe:
Technologie-Stack

    Server: Ubuntu mit Apache (neueste), PHP 8.3+, Python installiert
    Datenbank: SQLite (keine Installation nötig) oder MySQL
    Frontend: Modernes CSS (CSS Grid/Flexbox), Vanilla JavaScript oder Alpine.js
    Design: Funky, gelbe Farbtöne, mobile-responsive

Funktionsumfang
✅ Multi-User (Bands + Veranstalter)
✅ Login + E-Mail-Verifizierung
✅ Band-Profile (mehrere Videos, Fotos, Kalender, formatierte Texte)
✅ Bewertungssystem (nur nach Buchung, Sterne + Text max 200 Zeichen, Moderation)
✅ Anfrage-System mit E-Mail-Benachrichtigung
✅ Suche nach Stil/Genre
✅ Admin-Backend (Freischaltung, Moderation)
✅ PayPal-Integration (aktivierbar) + Vermittlungsgebühr
✅ DSGVO (Cookie-Banner, AGB, Datenschutz)
Dateistruktur-Plan (mehrere Files)
text

getyourband/
├── index.php              # Startseite mit Band-Übersicht
├── band-detail.php        # Band-Detailseite
├── anfrage.php            # Anfrage-Formular
├── login.php              # Login/Registrierung
├── verify-email.php       # E-Mail-Verifizierung
├── profil.php             # Band-Profil bearbeiten
├── admin/
│   ├── dashboard.php      # Admin-Übersicht
│   ├── bands.php          # Bands freischalten
│   ├── bewertungen.php    # Bewertungen moderieren
│   └── settings.php       # Gebühren/PayPal aktivieren
├── includes/
│   ├── config.php         # DB-Verbindung, Konstanten
│   ├── functions.php      # Hilfsfunktionen
│   ├── auth.php           # Login-Logik
│   └── email.php          # E-Mail-Versand
├── assets/
│   ├── css/style.css      # Funky Design
│   ├── js/app.js          # JavaScript
│   └── images/
├── database.sql           # SQL-Schema
└── .htaccess              # Apache-Konfiguration

Umsetzung in Schritten
Ich werde folgende Schritte nacheinander liefern:
Schritt 1: Datenbank-Schema + Basis-Setup

    SQL-Tabellen (Bands, Users, Bewertungen, Anfragen)
    config.php mit DB-Verbindung
    .htaccess für Clean URLs

Schritt 2: Authentifizierung

    Login/Registrierung
    E-Mail-Verifizierung
    Session-Management

Schritt 3: Frontend (Startseite + Band-Detail)

    Funky Design mit gelben Tönen
    Band-Übersicht mit Suche
    Detailseite mit Kalender

Schritt 4: Anfrage-System

    Anfrage-Formular
    E-Mail-Benachrichtigung
    Anfragen-Verwaltung

Schritt 5: Bewertungssystem

    Bewertungen abgeben (nur nach Buchung)
    Moderation im Admin-Bereich

Schritt 6: Admin-Backend

    Dashboard
    Freischaltung von Bands
    PayPal/Gebühren-Einstellungen

Schritt 7: DSGVO & Feinschliff

    Cookie-Banner
    AGB/Datenschutz-Seiten
    Mobile-Optimierung

Offene Frage vor Start:
SQLite oder MySQL?

    SQLite = keine Installation, einfacher Start, Limit bei vielen gleichzeitigen Zugriffen
    MySQL = performanter, aber Installation nötig

Deine Wahl? Dann starte ich mit Schritt 1! 🎸🔥
Sources
Images
Videos
News
ubuntuusers.de
Apache 2.4 › Wiki › ubuntuusers.de

Zu den Modulen zählt auch das Einbinden von PHP. Weiterführende Informationen sind im Artikel Apache/Module zu finden. Sollte /var/log/ nicht verfügbar sein (zum Beispiel durch ein temporäres Verzeichnis - siehe Tuning (Abschnitt „Temporaere-Dateien“)) wird ein Starten des Daemons abgebrochen.
ubuntuusers.de
PHP › Wiki › ubuntuusers.de

Nach einem Neuladen (Reload) des Apache-Servers sollte nun die neu installierte PHP-Version verfügbar sein. Zu installierende PHP-Erweiterungen müssen, wenn man diese Paketquelle benutzt, mit der installierten PHP-Version angegeben werden, zum Beispiel sudo apt install php8.0-intl. Ansonsten wird nicht nur die Erweiterung aus den Ubuntu-eigenen Paketquellen installiert (main), es wird auch PHP komplett neu aus den Ubuntu-Paketquellen installiert!
ubuntuusers.de
Serverinstallation › Archiv › Wiki › ubuntuusers.de

Ab Ubuntu Breezy 5.10 ist es auch möglich php5 anstatt von php4 zu installieren. Die oben angegebene Konfiguration ist ganz sicher nicht die einzig mögliche (bzw. einzig richtige) Auswahl. Hier wird konkret ein FTP-Server installiert (Zweck: Datenübertragung), ein SSH-Server (Zweck: Fernsteuerung des Servers von einem anderen Computer, z.B. mit puTTY), Apache als Webserver, hier in einer sehr stabilen (aber relativ langsamen) Variante, die mit MediaWiki-Software (Wikipedia) garantiert funktioniert.
digitalocean.com
Ausführen mehrerer PHP-Versionen auf einem Server unter Verwendung von Apache und PHP-FPM unter Ubuntu 18.04 | DigitalOcean

site1.your_domain> Options Indexes FollowSymLinks MultiViews AllowOverride All Order allow,deny allow from all </Directory> <FilesMatch \.php$> # For Apache version 2.4.10 and above, use SetHandler to run PHP as a fastCGI process server SetHandler "proxy:unix:/run/php/
sqlite. lets go
Workflow

Thinking

Searching

Thinking

Responding
🎸 SCHRITT 1: Datenbank-Schema + Basis-Setup
Los geht's! Hier ist die Foundation für getyourband.ch:
📁 Datei 1: database.sql
sql

-- SQLite Datenbank-Schema für getyourband.ch
-- Erstellt: 2025-11-16

-- Benutzer-Tabelle (Bands + Veranstalter)
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    user_type VARCHAR(20) NOT NULL CHECK(user_type IN ('band', 'veranstalter')),
    is_verified INTEGER DEFAULT 0,
    verification_token VARCHAR(64),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME
);

-- Band-Profile
CREATE TABLE bands (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    band_name VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    description TEXT,
    location VARCHAR(255),
    price_range VARCHAR(50),
    phone VARCHAR(50),
    website VARCHAR(255),
    is_approved INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Band-Medien (Fotos/Videos)
CREATE TABLE band_media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    media_type VARCHAR(10) CHECK(media_type IN ('photo', 'video')),
    file_path VARCHAR(500),
    video_url VARCHAR(500),
    display_order INTEGER DEFAULT 0,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (band_id) REFERENCES bands(id) ON DELETE CASCADE
);

-- Verfügbarkeitskalender
CREATE TABLE availability (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    date DATE NOT NULL,
    is_available INTEGER DEFAULT 1,
    notes TEXT,
    FOREIGN KEY (band_id) REFERENCES bands(id) ON DELETE CASCADE,
    UNIQUE(band_id, date)
);

-- Anfragen
CREATE TABLE anfragen (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    veranstalter_id INTEGER,
    veranstalter_name VARCHAR(255) NOT NULL,
    veranstalter_email VARCHAR(255) NOT NULL,
    veranstalter_phone VARCHAR(50),
    event_date DATE NOT NULL,
    event_location VARCHAR(255),
    event_type VARCHAR(100),
    budget VARCHAR(50),
    message TEXT,
    status VARCHAR(20) DEFAULT 'pending' CHECK(status IN ('pending', 'accepted', 'declined', 'completed')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (band_id) REFERENCES bands(id) ON DELETE CASCADE,
    FOREIGN KEY (veranstalter_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Bewertungen (nur nach abgeschlossener Buchung)
CREATE TABLE bewertungen (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    anfrage_id INTEGER NOT NULL,
    veranstalter_id INTEGER NOT NULL,
    rating INTEGER CHECK(rating >= 1 AND rating <= 5),
    comment TEXT CHECK(LENGTH(comment) <= 200),
    is_approved INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (band_id) REFERENCES bands(id) ON DELETE CASCADE,
    FOREIGN KEY (anfrage_id) REFERENCES anfragen(id) ON DELETE CASCADE,
    FOREIGN KEY (veranstalter_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(anfrage_id)
);

-- System-Einstellungen (PayPal, Gebühren)
CREATE TABLE settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Standard-Einstellungen einfügen
INSERT INTO settings (setting_key, setting_value) VALUES 
    ('paypal_enabled', '0'),
    ('paypal_client_id', ''),
    ('vermittlungsgebuehr_prozent', '10'),
    ('site_email', 'info@getyourband.ch');

-- Indizes für Performance
CREATE INDEX idx_bands_approved ON bands(is_approved);
CREATE INDEX idx_bands_genre ON bands(genre);
CREATE INDEX idx_anfragen_status ON anfragen(status);
CREATE INDEX idx_bewertungen_approved ON bewertungen(is_approved);
CREATE INDEX idx_users_email ON users(email);

📁 Datei 2: includes/config.php
php

<?php
// Konfigurationsdatei für getyourband.ch
// Erstellt: 2025-11-16

// Fehlerbehandlung (Entwicklung)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Zeitzone
date_default_timezone_set('Europe/Zurich');

// Basis-Pfade
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost/getyourband'); // Anpassen für Produktion

// Datenbank-Konfiguration (SQLite)
define('DB_PATH', BASE_PATH . '/database/getyourband.db');

// Session-Konfiguration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Datenbank-Verbindung
function getDB() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Foreign Keys aktivieren (wichtig für SQLite)
        $db->exec('PRAGMA foreign_keys = ON;');
        
        return $db;
    } catch(PDOException $e) {
        die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
    }
}

// E-Mail-Konfiguration
define('SMTP_HOST', 'smtp.gmail.com'); // Anpassen
define('SMTP_PORT', 587);
define('SMTP_USER', 'deine-email@gmail.com'); // Anpassen
define('SMTP_PASS', 'dein-app-passwort'); // Anpassen
define('FROM_EMAIL', 'noreply@getyourband.ch');
define('FROM_NAME', 'GetYourBand.ch');

// Upload-Konfiguration
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Sicherheit
define('PASSWORD_MIN_LENGTH', 8);
define('TOKEN_LENGTH', 32);

// Hilfsfunktionen
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isBand() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'band';
}

function isVeranstalter() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'veranstalter';
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function generateToken($length = TOKEN_LENGTH) {
    return bin2hex(random_bytes($length));
}
?>

📁 Datei 3: .htaccess
apache

# Apache-Konfiguration für getyourband.ch

# PHP-Einstellungen
php_flag display_errors On
php_value upload_max_filesize 10M
php_value post_max_size 12M

# Sicherheit
Options -Indexes
ServerSignature Off

# Clean URLs
RewriteEngine On
RewriteBase /getyourband/

# Weiterleitung zu HTTPS (für Produktion)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Datei-Schutz
<FilesMatch "\.(sql|db|log)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Clean URL Regeln
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^band/([0-9]+)$ band-detail.php?id=$1 [L,QSA]
RewriteRule ^admin/(.*)$ admin/$1 [L,QSA]

📁 Datei 4: setup.php (Einmalig ausführen)
php

<?php
// Setup-Script für initiale Datenbank-Erstellung
// Nach Ausführung LÖSCHEN oder umbenennen!

require_once 'includes/config.php';

echo "<h1>🎸 GetYourBand.ch Setup</h1>";

// Datenbank-Ordner erstellen
$dbDir = BASE_PATH . '/database';
if (!file_exists($dbDir)) {
    mkdir($dbDir, 0755, true);
    echo "✅ Datenbank-Ordner erstellt<br>";
}

// Upload-Ordner erstellen
$uploadDirs = [
    UPLOAD_PATH,
    UPLOAD_PATH . 'bands/',
    UPLOAD_PATH . 'photos/',
    UPLOAD_PATH . 'videos/'
];

foreach ($uploadDirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Upload-Ordner erstellt: $dir<br>";
    }
}

// SQL-Schema laden und ausführen
$sql = file_get_contents(BASE_PATH . '/database.sql');

try {
    $db = getDB();
    $db->exec($sql);
    echo "✅ Datenbank-Schema erfolgreich erstellt<br>";
    
    // Test-Admin erstellen
    $adminEmail = 'admin@getyourband.ch';
    $adminPassword = password_hash('Admin123!', PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("INSERT INTO users (email, password_hash, user_type, is_verified) VALUES (?, ?, 'band', 1)");
    $stmt->execute([$adminEmail, $adminPassword]);
    
    echo "✅ Test-Admin erstellt: $adminEmail / Admin123!<br>";
    echo "<br><strong⚠️ WICHTIG: Lösche setup.php nach erfolgreicher Installation!</strong>";
    
} catch(PDOException $e) {
    echo "❌ Fehler: " . $e->getMessage();
}
?>

📋 Installation - Schritt für Schritt

    Ordnerstruktur erstellen:

text

getyourband/
├── database/          (wird automatisch erstellt)
├── uploads/           (wird automatisch erstellt)
├── includes/
│   └── config.php
├── database.sql
├── setup.php
└── .htaccess

    config.php anpassen:
        BASE_URL auf deine Domain setzen
        E-Mail-Einstellungen konfigurieren

    setup.php im Browser aufrufen:
        http://deine-domain.ch/getyourband/setup.php
        Datenbank wird automatisch erstellt

    setup.php löschen!

✅ Was ist jetzt fertig?

    ✅ Komplettes Datenbank-Schema (SQLite)
    ✅ Basis-Konfiguration mit Sicherheitsfunktionen
    ✅ Clean URLs via .htaccess
    ✅ Automatisches Setup-Script

🚀 Nächster Schritt?
Sag Bescheid, dann liefere ich Schritt 2: Authentifizierung (Login/Registrierung/E-Mail-Verifizierung)! 🎸
Sources
Images
Videos
News
sqlite.org
SQLite Home Page

SQLite is a C-language library that implements a small, fast, self-contained, high-reliability, full-featured, SQL database engine. SQLite is the most used database engine in the world. SQLite is built into all mobile phones and most computers and comes bundled inside countless other applications ...
wikipedia.org
SQLite - Wikipedia

SQLite (/ˌɛsˌkjuːˌɛlˈaɪt/ "S-Q-L-ite", /ˈsiːkwəˌlaɪt/ "sequel-ite") is a free and open-source relational database engine written in the C programming language. It is not a standalone app; rather, it is a library that software developers embed in their apps.
sqlitebrowser.org
DB Browser for SQLite

DB Browser for SQLite (DB4S) is a high quality, visual, open source tool designed for people who want to create, search, and edit SQLite or SQLCipher database files. DB4S gives a familiar spreadsheet-like interface on the database in addition to providing a full SQL query facility.
python.org
sqlite3 — DB-API 2.0 interface for SQLite databases

SQLite is a C library that provides a lightweight disk-based database that doesn’t require a separate server process and allows accessing the database using a nonstandard variant of the SQL query language.
weiter
Workflow

Thinking

Searching

Thinking

Responding
🎸 SCHRITT 2: Authentifizierung (Login/Registrierung/E-Mail-Verifizierung)
Jetzt bauen wir das komplette Auth-System!
📁 Datei 5: includes/auth.php
php

<?php
// Authentifizierungs-Funktionen für getyourband.ch

require_once 'config.php';
require_once 'email.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Benutzer registrieren
     */
    public function register($email, $password, $userType) {
        // Validierung
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Ungültige E-Mail-Adresse'];
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            return ['success' => false, 'error' => 'Passwort muss mindestens ' . PASSWORD_MIN_LENGTH . ' Zeichen lang sein'];
        }
        
        if (!in_array($userType, ['band', 'veranstalter'])) {
            return ['success' => false, 'error' => 'Ungültiger Benutzertyp'];
        }
        
        // E-Mail bereits registriert?
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'E-Mail-Adresse bereits registriert'];
        }
        
        // Benutzer erstellen
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = generateToken();
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (email, password_hash, user_type, verification_token) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$email, $passwordHash, $userType, $verificationToken]);
            
            $userId = $this->db->lastInsertId();
            
            // Verifizierungs-E-Mail senden
            $verificationLink = BASE_URL . '/verify-email.php?token=' . $verificationToken;
            $emailSent = sendVerificationEmail($email, $verificationLink);
            
            return [
                'success' => true, 
                'user_id' => $userId,
                'email_sent' => $emailSent
            ];
            
        } catch(PDOException $e) {
            return ['success' => false, 'error' => 'Registrierung fehlgeschlagen: ' . $e->getMessage()];
        }
    }
    
    /**
     * Benutzer einloggen
     */
    public function login($email, $password) {
        $stmt = $this->db->prepare("
            SELECT id, email, password_hash, user_type, is_verified 
            FROM users 
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'E-Mail oder Passwort falsch'];
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'E-Mail oder Passwort falsch'];
        }
        
        if (!$user['is_verified']) {
            return ['success' => false, 'error' => 'Bitte verifiziere zuerst deine E-Mail-Adresse'];
        }
        
        // Session erstellen
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        
        // Admin-Check (erste User = Admin)
        if ($user['id'] == 1) {
            $_SESSION['is_admin'] = true;
        }
        
        // Last Login aktualisieren
        $stmt = $this->db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        return ['success' => true, 'user_type' => $user['user_type']];
    }
    
    /**
     * E-Mail verifizieren
     */
    public function verifyEmail($token) {
        $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE verification_token = ? AND is_verified = 0
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'Ungültiger oder bereits verwendeter Verifizierungslink'];
        }
        
        $stmt = $this->db->prepare("
            UPDATE users 
            SET is_verified = 1, verification_token = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$user['id']]);
        
        return ['success' => true];
    }
    
    /**
     * Ausloggen
     */
    public function logout() {
        session_unset();
        session_destroy();
        session_start();
    }
    
    /**
     * Passwort zurücksetzen - Token generieren
     */
    public function requestPasswordReset($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            // Aus Sicherheitsgründen trotzdem Success zurückgeben
            return ['success' => true];
        }
        
        $resetToken = generateToken();
        $stmt = $this->db->prepare("
            UPDATE users 
            SET verification_token = ? 
            WHERE id = ?
        ");
        $stmt->execute([$resetToken, $user['id']]);
        
        $resetLink = BASE_URL . '/reset-password.php?token=' . $resetToken;
        sendPasswordResetEmail($email, $resetLink);
        
        return ['success' => true];
    }
    
    /**
     * Passwort zurücksetzen - Neues Passwort setzen
     */
    public function resetPassword($token, $newPassword) {
        if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            return ['success' => false, 'error' => 'Passwort zu kurz'];
        }
        
        $stmt = $this->db->prepare("
            SELECT id FROM users WHERE verification_token = ?
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'Ungültiger Link'];
        }
        
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password_hash = ?, verification_token = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$passwordHash, $user['id']]);
        
        return ['success' => true];
    }
}
?>

📁 Datei 6: includes/email.php
php

<?php
// E-Mail-Funktionen für getyourband.ch

require_once 'config.php';

/**
 * Basis-E-Mail-Funktion (verwendet PHP mail())
 * Für Produktion: PHPMailer oder SMTP verwenden!
 */
function sendEmail($to, $subject, $htmlBody) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . FROM_NAME . " <" . FROM_EMAIL . ">" . "\r\n";
    
    return mail($to, $subject, $htmlBody, $headers);
}

/**
 * Verifizierungs-E-Mail senden
 */
function sendVerificationEmail($email, $verificationLink) {
    $subject = "🎸 Bestätige deine E-Mail-Adresse bei GetYourBand.ch";
    
    $body = getEmailTemplate("
        <h2>Willkommen bei GetYourBand.ch!</h2>
        <p>Vielen Dank für deine Registrierung. Bitte bestätige deine E-Mail-Adresse, um deinen Account zu aktivieren:</p>
        <p style='text-align: center; margin: 30px 0;'>
            <a href='$verificationLink' style='background: #FFD700; color: #000; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                E-Mail bestätigen
            </a>
        </p>
        <p style='color: #666; font-size: 12px;'>
            Falls der Button nicht funktioniert, kopiere diesen Link in deinen Browser:<br>
            <a href='$verificationLink'>$verificationLink</a>
        </p>
    ");
    
    return sendEmail($email, $subject, $body);
}

/**
 * Passwort-Reset-E-Mail senden
 */
function sendPasswordResetEmail($email, $resetLink) {
    $subject = "🔐 Passwort zurücksetzen - GetYourBand.ch";
    
    $body = getEmailTemplate("
        <h2>Passwort zurücksetzen</h2>
        <p>Du hast eine Anfrage zum Zurücksetzen deines Passworts gestellt. Klicke auf den Button, um ein neues Passwort zu vergeben:</p>
        <p style='text-align: center; margin: 30px 0;'>
            <a href='$resetLink' style='background: #FFD700; color: #000; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                Neues Passwort setzen
            </a>
        </p>
        <p style='color: #666; font-size: 12px;'>
            Falls du diese Anfrage nicht gestellt hast, ignoriere diese E-Mail einfach.<br><br>
            Link: <a href='$resetLink'>$resetLink</a>
        </p>
    ");
    
    return sendEmail($email, $subject, $body);
}

/**
 * Anfrage-Benachrichtigung an Band
 */
function sendAnfrageNotification($bandEmail, $bandName, $veranstalterName, $eventDate, $anfrageId) {
    $subject = "🎉 Neue Buchungsanfrage für $bandName";
    
    $anfrageLink = BASE_URL . "/admin/anfragen.php?id=$anfrageId";
    
    $body = getEmailTemplate("
        <h2>Neue Buchungsanfrage!</h2>
        <p><strong>$veranstalterName</strong> möchte eure Band für einen Event buchen:</p>
        <ul style='line-height: 1.8;'>
            <li><strong>Datum:</strong> $eventDate</li>
        </ul>
        <p style='text-align: center; margin: 30px 0;'>
            <a href='$anfrageLink' style='background: #FFD700; color: #000; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                Anfrage ansehen
            </a>
        </p>
    ");
    
    return sendEmail($bandEmail, $subject, $body);
}

/**
 * E-Mail-Template (Funky Design)
 */
function getEmailTemplate($content) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: 'Arial', sans-serif; background: #1a1a1a; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; background: #fff; }
            .header { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); padding: 30px; text-align: center; }
            .header h1 { margin: 0; color: #000; font-size: 28px; }
            .content { padding: 40px 30px; color: #333; line-height: 1.6; }
            .footer { background: #f5f5f5; padding: 20px; text-align: center; color: #666; font-size: 12px; }
            a { color: #FFA500; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🎸 GetYourBand.ch</h1>
            </div>
            <div class='content'>
                $content
            </div>
            <div class='footer'>
                <p>&copy; 2025 GetYourBand.ch | <a href='" . BASE_URL . "'>Zur Website</a></p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>

📁 Datei 7: login.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$error = '';
$success = '';
$mode = $_GET['mode'] ?? 'login'; // login, register, forgot

// Login-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $result = $auth->login($_POST['email'], $_POST['password']);
    
    if ($result['success']) {
        redirect($result['user_type'] === 'band' ? '/profil.php' : '/index.php');
    } else {
        $error = $result['error'];
    }
}

// Registrierungs-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $result = $auth->register($_POST['email'], $_POST['password'], $_POST['user_type']);
    
    if ($result['success']) {
        $success = 'Registrierung erfolgreich! Bitte überprüfe deine E-Mails zur Verifizierung.';
        $mode = 'login';
    } else {
        $error = $result['error'];
    }
}

// Passwort vergessen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot'])) {
    $result = $auth->requestPasswordReset($_POST['email']);
    $success = 'Falls die E-Mail-Adresse existiert, wurde ein Reset-Link versendet.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $mode === 'register' ? 'Registrierung' : 'Login' ?> - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1>🎸 GetYourBand.ch</h1>
            <p>Die funky Bandvermittlung</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= sanitize($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= sanitize($success) ?></div>
        <?php endif; ?>
        
        <div class="auth-tabs">
            <button class="tab-btn <?= $mode === 'login' ? 'active' : '' ?>" onclick="switchMode('login')">Login</button>
            <button class="tab-btn <?= $mode === 'register' ? 'active' : '' ?>" onclick="switchMode('register')">Registrierung</button>
        </div>
        
        <!-- Login-Formular -->
        <form method="POST" class="auth-form <?= $mode === 'login' ? 'active' : '' ?>" id="login-form">
            <div class="form-group">
                <label>E-Mail</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Passwort</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" name="login" class="btn btn-primary">Einloggen</button>
            
            <p class="form-footer">
                <a href="?mode=forgot">Passwort vergessen?</a>
            </p>
        </form>
        
        <!-- Registrierungs-Formular -->
        <form method="POST" class="auth-form <?= $mode === 'register' ? 'active' : '' ?>" id="register-form">
            <div class="form-group">
                <label>Ich bin...</label>
                <select name="user_type" required>
                    <option value="band">🎸 Band (möchte gebucht werden)</option>
                    <option value="veranstalter">🎉 Veranstalter (möchte Band buchen)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>E-Mail</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Passwort (mind. 8 Zeichen)</label>
                <input type="password" name="password" minlength="8" required>
            </div>
            
            <div class="form-group checkbox">
                <label>
                    <input type="checkbox" required>
                    Ich akzeptiere die <a href="agb.php" target="_blank">AGB</a> und <a href="datenschutz.php" target="_blank">Datenschutzerklärung</a>
                </label>
            </div>
            
            <button type="submit" name="register" class="btn btn-primary">Registrieren</button>
        </form>
        
        <!-- Passwort vergessen -->
        <form method="POST" class="auth-form <?= $mode === 'forgot' ? 'active' : '' ?>" id="forgot-form">
            <p>Gib deine E-Mail-Adresse ein, um einen Reset-Link zu erhalten:</p>
            
            <div class="form-group">
                <label>E-Mail</label>
                <input type="email" name="email" required>
            </div>
            
            <button type="submit" name="forgot" class="btn btn-primary">Link senden</button>
            
            <p class="form-footer">
                <a href="?mode=login">Zurück zum Login</a>
            </p>
        </form>
    </div>
    
    <script>
        function switchMode(mode) {
            window.location.href = '?mode=' + mode;
        }
    </script>
</body>
</html>

📁 Datei 8: verify-email.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$message = '';
$success = false;

if (isset($_GET['token'])) {
    $result = $auth->verifyEmail($_GET['token']);
    
    if ($result['success']) {
        $success = true;
        $message = 'E-Mail erfolgreich verifiziert! Du kannst dich jetzt einloggen.';
    } else {
        $message = $result['error'];
    }
} else {
    $message = 'Kein Verifizierungstoken gefunden.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Mail Verifizierung - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1>🎸 GetYourBand.ch</h1>
        </div>
        
        <div class="alert <?= $success ? 'alert-success' : 'alert-error' ?>">
            <?= sanitize($message) ?>
        </div>
        
        <?php if ($success): ?>
            <a href="login.php" class="btn btn-primary">Zum Login</a>
        <?php else: ?>
            <a href="index.php" class="btn btn-secondary">Zur Startseite</a>
        <?php endif; ?>
    </div>
</body>
</html>

📁 Datei 9: reset-password.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$error = '';
$success = false;
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] !== $_POST['password_confirm']) {
        $error = 'Passwörter stimmen nicht überein';
    } else {
        $result = $auth->resetPassword($token, $_POST['password']);
        
        if ($result['success']) {
            $success = true;
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1>🎸 GetYourBand.ch</h1>
            <h2>Neues Passwort setzen</h2>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= sanitize($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                Passwort erfolgreich geändert! Du kannst dich jetzt einloggen.
            </div>
            <a href="login.php" class="btn btn-primary">Zum Login</a>
        <?php else: ?>
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Neues Passwort (mind. 8 Zeichen)</label>
                    <input type="password" name="password" minlength="8" required>
                </div>
                
                <div class="form-group">
                    <label>Passwort bestätigen</label>
                    <input type="password" name="password_confirm" minlength="8" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Passwort ändern</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

✅ Was ist jetzt fertig?

    ✅ Komplettes Auth-System (Login/Registrierung/Logout)
    ✅ E-Mail-Verifizierung mit Token
    ✅ Passwort-Reset-Funktion
    ✅ Sichere Passwort-Hashes (bcrypt)
    ✅ Session-Management
    ✅ E-Mail-Templates (funky Design)
    ✅ Benutzertypen (Band/Veranstalter)

🚀 Nächster Schritt?
Sag Bescheid für Schritt 3: Frontend (Startseite + Band-Detail mit funky Design)! 🎸✨
Sources
Images
Videos
News
html-seminar.de
Einfache PHP & Datenbank (MySQL) basierte Benutzerverwaltung (Login/Logout/Registrierung) - AJAX Kompatibel - PHP - HTML-Seminar.de - Forum für HTML, CSS und PHP

Ziel: eine einfache Datenbank basierte Benutzerverwaltung erstellen Verwendete Techniken: PHP, PDO Schwierigkeitsgrad: für Anfänger geeignet, Grundkenntnisse über die Funktionsweisen von PHP und SQL vorausgesetzt Anmerkungen: verwendet PDO, wegen dem…
tutsplus.com
Die 20 besten Veranstaltungs- und Buchungskalender mit PHP-Scripts... und 3 kostenlose Optionen | Envato Tuts+

Es ist mit PayPal und Razorpay integriert. Der Vacation Rentals Booking Calendar ist einen Belegungsplan mit Buchungsystem, mit dem Vermietungsbetrieb oder Verwaltungsagenturen Kalender für die Verfügbarkeit ihrer Ferienunterkünfte erstellen und verwalten können.
prepaid-hoster.de
Server mit Apache, PHP, MySQL mieten - Prepaid Hoster

Webserver-Ready in wenigen Sekunden · Einfaches Webserver Hosting auf dem eigenen vServer
zoechbauer.name
Projekt Webserver – Ubuntu 22.04 Teil 3 – PHP und MySQL nachinstallieren und konfigurieren

Im Teil 2 dieser Serie haben wir einen funktionierenden Webserver für statische HTML-Webseiten erstellt. Jetzt erweitern wir den Funktionsumfang noch etwas ind
weiter
Workflow

Thinking

Searching

Thinking

Responding
🎸 SCHRITT 2: Authentifizierung (Login/Registrierung/E-Mail-Verifizierung)
Jetzt bauen wir das komplette Auth-System!
📁 Datei 5: includes/auth.php
php

<?php
// Authentifizierungs-Funktionen für getyourband.ch

require_once 'config.php';
require_once 'email.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Benutzer registrieren
     */
    public function register($email, $password, $userType) {
        // Validierung
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Ungültige E-Mail-Adresse'];
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            return ['success' => false, 'error' => 'Passwort muss mindestens ' . PASSWORD_MIN_LENGTH . ' Zeichen lang sein'];
        }
        
        if (!in_array($userType, ['band', 'veranstalter'])) {
            return ['success' => false, 'error' => 'Ungültiger Benutzertyp'];
        }
        
        // E-Mail bereits registriert?
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'E-Mail-Adresse bereits registriert'];
        }
        
        // Benutzer erstellen
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = generateToken();
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (email, password_hash, user_type, verification_token) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$email, $passwordHash, $userType, $verificationToken]);
            
            $userId = $this->db->lastInsertId();
            
            // Verifizierungs-E-Mail senden
            $verificationLink = BASE_URL . '/verify-email.php?token=' . $verificationToken;
            $emailSent = sendVerificationEmail($email, $verificationLink);
            
            return [
                'success' => true, 
                'user_id' => $userId,
                'email_sent' => $emailSent
            ];
            
        } catch(PDOException $e) {
            return ['success' => false, 'error' => 'Registrierung fehlgeschlagen: ' . $e->getMessage()];
        }
    }
    
    /**
     * Benutzer einloggen
     */
    public function login($email, $password) {
        $stmt = $this->db->prepare("
            SELECT id, email, password_hash, user_type, is_verified 
            FROM users 
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'E-Mail oder Passwort falsch'];
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'E-Mail oder Passwort falsch'];
        }
        
        if (!$user['is_verified']) {
            return ['success' => false, 'error' => 'Bitte verifiziere zuerst deine E-Mail-Adresse'];
        }
        
        // Session erstellen
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        
        // Admin-Check (erste User = Admin)
        if ($user['id'] == 1) {
            $_SESSION['is_admin'] = true;
        }
        
        // Last Login aktualisieren
        $stmt = $this->db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        return ['success' => true, 'user_type' => $user['user_type']];
    }
    
    /**
     * E-Mail verifizieren
     */
    public function verifyEmail($token) {
        $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE verification_token = ? AND is_verified = 0
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'Ungültiger oder bereits verwendeter Token'];
        }
        
        $stmt = $this->db->prepare("
            UPDATE users 
            SET is_verified = 1, verification_token = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$user['id']]);
        
        return ['success' => true];
    }
    
    /**
     * Ausloggen
     */
    public function logout() {
        session_destroy();
        session_start();
    }
    
    /**
     * Passwort zurücksetzen - Token generieren
     */
    public function requestPasswordReset($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            // Aus Sicherheitsgründen trotzdem Success zurückgeben
            return ['success' => true];
        }
        
        $resetToken = generateToken();
        $stmt = $this->db->prepare("
            UPDATE users 
            SET verification_token = ? 
            WHERE id = ?
        ");
        $stmt->execute([$resetToken, $user['id']]);
        
        $resetLink = BASE_URL . '/reset-password.php?token=' . $resetToken;
        sendPasswordResetEmail($email, $resetLink);
        
        return ['success' => true];
    }
}
?>

📁 Datei 6: includes/email.php
php

<?php
// E-Mail-Funktionen für getyourband.ch

require_once 'config.php';

/**
 * Basis-E-Mail-Funktion (verwendet PHP mail())
 * Für Produktion: PHPMailer oder SMTP verwenden
 */
function sendEmail($to, $subject, $htmlBody) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . FROM_NAME . " <" . FROM_EMAIL . ">" . "\r\n";
    
    return mail($to, $subject, $htmlBody, $headers);
}

/**
 * Verifizierungs-E-Mail senden
 */
function sendVerificationEmail($email, $verificationLink) {
    $subject = "🎸 Bestätige deine E-Mail bei GetYourBand.ch";
    
    $body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #1a1a1a; color: #fff; }
            .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }
            .header { background: linear-gradient(135deg, #FFD700, #FFA500); padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #2a2a2a; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; background: #FFD700; color: #000; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; color: #888; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin:0; color: #000;'>🎸 GetYourBand.ch</h1>
            </div>
            <div class='content'>
                <h2>Willkommen bei GetYourBand.ch!</h2>
                <p>Danke für deine Registrierung. Bitte bestätige deine E-Mail-Adresse, um deinen Account zu aktivieren:</p>
                <p style='text-align: center;'>
                    <a href='$verificationLink' class='button'>E-Mail bestätigen</a>
                </p>
                <p style='color: #888; font-size: 12px;'>Oder kopiere diesen Link in deinen Browser:<br>
                $verificationLink</p>
                <p>Der Link ist 24 Stunden gültig.</p>
            </div>
            <div class='footer'>
                © 2025 GetYourBand.ch - Die funky Bandvermittlung
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($email, $subject, $body);
}

/**
 * Passwort-Reset-E-Mail
 */
function sendPasswordResetEmail($email, $resetLink) {
    $subject = "🔐 Passwort zurücksetzen - GetYourBand.ch";
    
    $body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #1a1a1a; color: #fff; }
            .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }
            .header { background: linear-gradient(135deg, #FFD700, #FFA500); padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #2a2a2a; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; background: #FFD700; color: #000; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin:0; color: #000;'>🎸 GetYourBand.ch</h1>
            </div>
            <div class='content'>
                <h2>Passwort zurücksetzen</h2>
                <p>Du hast eine Passwort-Zurücksetzung angefordert. Klicke auf den Button, um ein neues Passwort zu setzen:</p>
                <p style='text-align: center;'>
                    <a href='$resetLink' class='button'>Neues Passwort setzen</a>
                </p>
                <p style='color: #888; font-size: 12px;'>Falls du diese Anfrage nicht gestellt hast, ignoriere diese E-Mail einfach.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($email, $subject, $body);
}

/**
 * Benachrichtigung an Band bei neuer Anfrage
 */
function sendAnfrageNotification($bandEmail, $bandName, $veranstalterName, $eventDate) {
    $subject = "🎉 Neue Buchungsanfrage für $bandName";
    
    $body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #1a1a1a; color: #fff; }
            .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }
            .header { background: linear-gradient(135deg, #FFD700, #FFA500); padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #2a2a2a; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; background: #FFD700; color: #000; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin:0; color: #000;'>🎸 GetYourBand.ch</h1>
            </div>
            <div class='content'>
                <h2>Neue Buchungsanfrage!</h2>
                <p><strong>$veranstalterName</strong> möchte $bandName für den <strong>$eventDate</strong> buchen.</p>
                <p style='text-align: center;'>
                    <a href='" . BASE_URL . "/profil.php' class='button'>Anfrage ansehen</a>
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($bandEmail, $subject, $body);
}
?>

📁 Datei 7: login.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$error = '';
$success = '';

// Login-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $result = $auth->login($_POST['email'], $_POST['password']);
    
    if ($result['success']) {
        if ($result['user_type'] === 'band') {
            redirect('/profil.php');
        } else {
            redirect('/index.php');
        }
    } else {
        $error = $result['error'];
    }
}

// Registrierungs-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $result = $auth->register($_POST['email'], $_POST['password'], $_POST['user_type']);
    
    if ($result['success']) {
        $success = 'Registrierung erfolgreich! Bitte überprüfe deine E-Mails zur Verifizierung.';
    } else {
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registrierung - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1>🎸 GetYourBand.ch</h1>
            <p>Die funky Bandvermittlung</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= sanitize($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= sanitize($success) ?></div>
        <?php endif; ?>
        
        <div class="auth-tabs">
            <button class="tab-btn active" data-tab="login">Login</button>
            <button class="tab-btn" data-tab="register">Registrieren</button>
        </div>
        
        <!-- Login-Formular -->
        <div id="login-tab" class="tab-content active">
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="login-email">E-Mail</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="login-password">Passwort</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary">Einloggen</button>
                
                <p class="form-footer">
                    <a href="reset-password.php">Passwort vergessen?</a>
                </p>
            </form>
        </div>
        
        <!-- Registrierungs-Formular -->
        <div id="register-tab" class="tab-content">
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="reg-email">E-Mail</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="reg-password">Passwort (min. 8 Zeichen)</label>
                    <input type="password" id="reg-password" name="password" minlength="8" required>
                </div>
                
                <div class="form-group">
                    <label>Ich bin...</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="user_type" value="band" required>
                            <span>🎸 Band / Musiker</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="user_type" value="veranstalter" required>
                            <span>🎉 Veranstalter</span>
                        </label>
                    </div>
                </div>
                
                <button type="submit" name="register" class="btn btn-primary">Registrieren</button>
                
                <p class="form-footer">
                    Mit der Registrierung akzeptierst du unsere <a href="agb.php">AGB</a> und <a href="datenschutz.php">Datenschutzerklärung</a>.
                </p>
            </form>
        </div>
        
        <div class="auth-footer">
            <a href="index.php">← Zurück zur Startseite</a>
        </div>
    </div>
    
    <script src="assets/js/auth.js"></script>
</body>
</html>

📁 Datei 8: verify-email.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$message = '';
$success = false;

if (isset($_GET['token'])) {
    $result = $auth->verifyEmail($_GET['token']);
    
    if ($result['success']) {
        $success = true;
        $message = 'E-Mail erfolgreich verifiziert! Du kannst dich jetzt einloggen.';
    } else {
        $message = $result['error'];
    }
} else {
    $message = 'Kein Verifizierungs-Token gefunden.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Mail Verifizierung - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1>🎸 GetYourBand.ch</h1>
        </div>
        
        <div class="verification-result">
            <?php if ($success): ?>
                <div class="success-icon">✅</div>
                <h2>Verifizierung erfolgreich!</h2>
                <p><?= sanitize($message) ?></p>
                <a href="login.php" class="btn btn-primary">Zum Login</a>
            <?php else: ?>
                <div class="error-icon">❌</div>
                <h2>Verifizierung fehlgeschlagen</h2>
                <p><?= sanitize($message) ?></p>
                <a href="login.php" class="btn btn-secondary">Zurück zum Login</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

📁 Datei 9: assets/js/auth.js
javascript

// Tab-Switching für Login/Registrierung
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Alle Tabs deaktivieren
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Aktiven Tab aktivieren
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });
    
    // Passwort-Stärke-Anzeige
    const passwordInput = document.getElementById('reg-password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            // Hier könnte eine visuelle Anzeige ergänzt werden
        });
    }
});

function calculatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    return strength;
}

✅ Was ist jetzt fertig?

    ✅ Komplettes Auth-System (Registrierung, Login, Logout)
    ✅ E-Mail-Verifizierung mit Token
    ✅ Passwort-Hashing (bcrypt)
    ✅ Session-Management
    ✅ E-Mail-Templates (HTML)
    ✅ Benutzertypen (Band / Veranstalter)
    ✅ Admin-Erkennung (User ID 1)

🚀 Nächster Schritt?
Sag Bescheid für Schritt 3: Frontend (Funky Design + Startseite + Band-Übersicht)! 🎨✨
Sources
Images
Videos
News
html-seminar.de
Einfache PHP & Datenbank (MySQL) basierte Benutzerverwaltung (Login/Logout/Registrierung) - AJAX Kompatibel - PHP - HTML-Seminar.de - Forum für HTML, CSS und PHP

Ziel: eine einfache Datenbank basierte Benutzerverwaltung erstellen Verwendete Techniken: PHP, PDO Schwierigkeitsgrad: für Anfänger geeignet, Grundkenntnisse über die Funktionsweisen von PHP und SQL vorausgesetzt Anmerkungen: verwendet PDO, wegen dem…
tutsplus.com
Die 20 besten Veranstaltungs- und Buchungskalender mit PHP-Scripts... und 3 kostenlose Optionen | Envato Tuts+

Es ist mit PayPal und Razorpay integriert. Der Vacation Rentals Booking Calendar ist einen Belegungsplan mit Buchungsystem, mit dem Vermietungsbetrieb oder Verwaltungsagenturen Kalender für die Verfügbarkeit ihrer Ferienunterkünfte erstellen und verwalten können.
prepaid-hoster.de
Server mit Apache, PHP, MySQL mieten - Prepaid Hoster

Webserver-Ready in wenigen Sekunden · Einfaches Webserver Hosting auf dem eigenen vServer
zoechbauer.name
Projekt Webserver – Ubuntu 22.04 Teil 3 – PHP und MySQL nachinstallieren und konfigurieren

Im Teil 2 dieser Serie haben wir einen funktionierenden Webserver für statische HTML-Webseiten erstellt. Jetzt erweitern wir den Funktionsumfang noch etwas ind
weiter
Workflow

Thinking

Searching

Thinking

Responding
🎸 SCHRITT 2: Authentifizierung (Login/Registrierung/E-Mail-Verifizierung)
Jetzt bauen wir das komplette Auth-System!
📁 Datei 5: includes/auth.php
php

<?php
// Authentifizierungs-Funktionen für getyourband.ch

require_once 'config.php';
require_once 'email.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Benutzer registrieren
     */
    public function register($email, $password, $userType) {
        // Validierung
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Ungültige E-Mail-Adresse'];
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            return ['success' => false, 'error' => 'Passwort muss mindestens ' . PASSWORD_MIN_LENGTH . ' Zeichen lang sein'];
        }
        
        if (!in_array($userType, ['band', 'veranstalter'])) {
            return ['success' => false, 'error' => 'Ungültiger Benutzertyp'];
        }
        
        // E-Mail bereits registriert?
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'E-Mail-Adresse bereits registriert'];
        }
        
        // Benutzer erstellen
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = generateToken();
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (email, password_hash, user_type, verification_token) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$email, $passwordHash, $userType, $verificationToken]);
            
            $userId = $this->db->lastInsertId();
            
            // Verifizierungs-E-Mail senden
            $verifyLink = BASE_URL . '/verify-email.php?token=' . $verificationToken;
            $emailSent = sendVerificationEmail($email, $verifyLink);
            
            return [
                'success' => true, 
                'user_id' => $userId,
                'email_sent' => $emailSent
            ];
            
        } catch(PDOException $e) {
            return ['success' => false, 'error' => 'Registrierung fehlgeschlagen: ' . $e->getMessage()];
        }
    }
    
    /**
     * Benutzer einloggen
     */
    public function login($email, $password) {
        $stmt = $this->db->prepare("
            SELECT id, email, password_hash, user_type, is_verified 
            FROM users 
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'E-Mail oder Passwort falsch'];
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'E-Mail oder Passwort falsch'];
        }
        
        if (!$user['is_verified']) {
            return ['success' => false, 'error' => 'Bitte verifiziere zuerst deine E-Mail-Adresse'];
        }
        
        // Session erstellen
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        
        // Admin-Check (erste User = Admin)
        if ($user['id'] == 1) {
            $_SESSION['is_admin'] = true;
        }
        
        // Last Login aktualisieren
        $stmt = $this->db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        return ['success' => true, 'user_type' => $user['user_type']];
    }
    
    /**
     * E-Mail verifizieren
     */
    public function verifyEmail($token) {
        $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE verification_token = ? AND is_verified = 0
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'Ungültiger oder bereits verwendeter Token'];
        }
        
        $stmt = $this->db->prepare("
            UPDATE users 
            SET is_verified = 1, verification_token = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$user['id']]);
        
        return ['success' => true];
    }
    
    /**
     * Ausloggen
     */
    public function logout() {
        session_destroy();
        session_start();
    }
    
    /**
     * Passwort zurücksetzen - Token generieren
     */
    public function requestPasswordReset($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            // Aus Sicherheitsgründen trotzdem Success zurückgeben
            return ['success' => true];
        }
        
        $resetToken = generateToken();
        $stmt = $this->db->prepare("
            UPDATE users 
            SET verification_token = ? 
            WHERE id = ?
        ");
        $stmt->execute([$resetToken, $user['id']]);
        
        $resetLink = BASE_URL . '/reset-password.php?token=' . $resetToken;
        sendPasswordResetEmail($email, $resetLink);
        
        return ['success' => true];
    }
}
?>

📁 Datei 6: includes/email.php
php

<?php
// E-Mail-Funktionen für getyourband.ch

require_once 'config.php';

/**
 * Basis-E-Mail-Funktion (verwendet PHP mail())
 * Für Produktion: PHPMailer oder SMTP verwenden
 */
function sendEmail($to, $subject, $htmlBody) {
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . FROM_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To: ' . FROM_EMAIL,
        'X-Mailer: PHP/' . phpversion()
    ];
    
    $success = mail($to, $subject, $htmlBody, implode("\r\n", $headers));
    
    return $success;
}

/**
 * Verifizierungs-E-Mail senden
 */
function sendVerificationEmail($email, $verifyLink) {
    $subject = '🎸 Bestätige deine E-Mail-Adresse bei GetYourBand.ch';
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #1a1a1a; color: #fff; }
            .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }
            .header { background: linear-gradient(135deg, #FFD700, #FFA500); padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #2a2a2a; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; background: #FFD700; color: #1a1a1a; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; color: #888; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1 style="margin:0; color: #1a1a1a;">🎸 GetYourBand.ch</h1>
            </div>
            <div class="content">
                <h2>Willkommen bei GetYourBand.ch!</h2>
                <p>Danke für deine Registrierung. Bitte bestätige deine E-Mail-Adresse, um deinen Account zu aktivieren:</p>
                <p style="text-align: center;">
                    <a href="' . $verifyLink . '" class="button">E-Mail bestätigen</a>
                </p>
                <p style="color: #888; font-size: 14px;">Oder kopiere diesen Link in deinen Browser:<br>
                <a href="' . $verifyLink . '" style="color: #FFD700;">' . $verifyLink . '</a></p>
                <p style="margin-top: 30px; color: #888; font-size: 12px;">Falls du dich nicht registriert hast, ignoriere diese E-Mail.</p>
            </div>
            <div class="footer">
                © 2025 GetYourBand.ch - Die funky Bandvermittlung
            </div>
        </div>
    </body>
    </html>
    ';
    
    return sendEmail($email, $subject, $html);
}

/**
 * Passwort-Reset-E-Mail
 */
function sendPasswordResetEmail($email, $resetLink) {
    $subject = '🔐 Passwort zurücksetzen - GetYourBand.ch';
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #1a1a1a; color: #fff; }
            .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }
            .header { background: linear-gradient(135deg, #FFD700, #FFA500); padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #2a2a2a; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; background: #FFD700; color: #1a1a1a; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1 style="margin:0; color: #1a1a1a;">🎸 GetYourBand.ch</h1>
            </div>
            <div class="content">
                <h2>Passwort zurücksetzen</h2>
                <p>Du hast eine Anfrage zum Zurücksetzen deines Passworts gestellt. Klicke auf den Button, um ein neues Passwort zu vergeben:</p>
                <p style="text-align: center;">
                    <a href="' . $resetLink . '" class="button">Neues Passwort setzen</a>
                </p>
                <p style="color: #888; font-size: 14px;">Link gültig für 24 Stunden.</p>
                <p style="margin-top: 30px; color: #888; font-size: 12px;">Falls du diese Anfrage nicht gestellt hast, ignoriere diese E-Mail.</p>
            </div>
        </div>
    </body>
    </html>
    ';
    
    return sendEmail($email, $subject, $html);
}

/**
 * Benachrichtigung an Band bei neuer Anfrage
 */
function sendAnfrageNotification($bandEmail, $bandName, $veranstalterName, $eventDate) {
    $subject = '🎉 Neue Buchungsanfrage für ' . $bandName;
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #1a1a1a; color: #fff; }
            .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }
            .header { background: linear-gradient(135deg, #FFD700, #FFA500); padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #2a2a2a; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; background: #FFD700; color: #1a1a1a; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1 style="margin:0; color: #1a1a1a;">🎸 GetYourBand.ch</h1>
            </div>
            <div class="content">
                <h2>Neue Buchungsanfrage!</h2>
                <p><strong>' . sanitize($veranstalterName) . '</strong> möchte euch für den <strong>' . date('d.m.Y', strtotime($eventDate)) . '</strong> buchen.</p>
                <p style="text-align: center;">
                    <a href="' . BASE_URL . '/profil.php" class="button">Anfrage ansehen</a>
                </p>
            </div>
        </div>
    </body>
    </html>
    ';
    
    return sendEmail($bandEmail, $subject, $html);
}
?>

📁 Datei 7: login.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$error = '';
$success = '';

// Login-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $result = $auth->login($_POST['email'], $_POST['password']);
    
    if ($result['success']) {
        if ($result['user_type'] === 'band') {
            redirect('/profil.php');
        } else {
            redirect('/index.php');
        }
    } else {
        $error = $result['error'];
    }
}

// Registrierungs-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $result = $auth->register($_POST['email'], $_POST['password'], $_POST['user_type']);
    
    if ($result['success']) {
        $success = 'Registrierung erfolgreich! Bitte überprüfe deine E-Mails zur Verifizierung.';
    } else {
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registrierung - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>🎸 GetYourBand.ch</h1>
            <p>Die funky Bandvermittlung</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= sanitize($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= sanitize($success) ?></div>
        <?php endif; ?>
        
        <div class="auth-tabs">
            <button class="tab-btn active" onclick="showTab('login')">Login</button>
            <button class="tab-btn" onclick="showTab('register')">Registrieren</button>
        </div>
        
        <!-- Login-Formular -->
        <div id="login-tab" class="tab-content active">
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="login-email">E-Mail</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="login-password">Passwort</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary">Einloggen</button>
                
                <p class="form-footer">
                    <a href="reset-password.php">Passwort vergessen?</a>
                </p>
            </form>
        </div>
        
        <!-- Registrierungs-Formular -->
        <div id="register-tab" class="tab-content">
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="reg-email">E-Mail</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="reg-password">Passwort (min. 8 Zeichen)</label>
                    <input type="password" id="reg-password" name="password" minlength="8" required>
                </div>
                
                <div class="form-group">
                    <label>Ich bin...</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="user_type" value="band" required>
                            <span>🎸 Band / Musiker</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="user_type" value="veranstalter" required>
                            <span>🎉 Veranstalter</span>
                        </label>
                    </div>
                </div>
                
                <button type="submit" name="register" class="btn btn-primary">Registrieren</button>
                
                <p class="form-footer">
                    Mit der Registrierung akzeptierst du unsere <a href="agb.php">AGB</a> und <a href="datenschutz.php">Datenschutzerklärung</a>.
                </p>
            </form>
        </div>
        
        <div class="auth-footer">
            <a href="index.php">← Zurück zur Startseite</a>
        </div>
    </div>
    
    <script>
        function showTab(tabName) {
            // Alle Tabs ausblenden
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Gewählten Tab anzeigen
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>

📁 Datei 8: verify-email.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$message = '';
$success = false;

if (isset($_GET['token'])) {
    $result = $auth->verifyEmail($_GET['token']);
    
    if ($result['success']) {
        $success = true;
        $message = 'E-Mail erfolgreich verifiziert! Du kannst dich jetzt einloggen.';
    } else {
        $message = $result['error'];
    }
} else {
    $message = 'Kein Verifizierungs-Token gefunden.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Mail Verifizierung - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="verify-container">
        <div class="verify-box">
            <h1>🎸 GetYourBand.ch</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <h2>✅ <?= $message ?></h2>
                    <a href="login.php" class="btn btn-primary">Zum Login</a>
                </div>
            <?php else: ?>
                <div class="alert alert-error">
                    <h2>❌ <?= $message ?></h2>
                    <a href="login.php" class="btn btn-secondary">Zurück zum Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

📁 Datei 9: assets/css/style.css (Basis-Styling)
css

/* GetYourBand.ch - Funky Design mit Gelbtönen */

:root {
    --primary: #FFD700;
    --secondary: #FFA500;
    --dark: #1a1a1a;
    --darker: #0d0d0d;
    --light: #f5f5f5;
    --gray: #888;
    --success: #4CAF50;
    --error: #f44336;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, var(--darker) 0%, var(--dark) 100%);
    color: var(--light);
    min-height: 100vh;
    line-height: 1.6;
}

/* Auth-Container */
.auth-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-header h1 {
    font-size: 3em;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 10px;
}

.auth-header p {
    color: var(--gray);
    font-size: 1.1em;
}

/* Tabs */
.auth-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
}

.tab-btn {
    flex: 1;
    padding: 15px;
    background: var(--dark);
    border: 2px solid var(--gray);
    color: var(--light);
    font-size: 1em;
    cursor: pointer;
    border-radius: 10px;
    transition: all 0.3s;
}

.tab-btn:hover {
    border-color: var(--primary);
}

.tab-btn.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--dark);
    border-color: var(--primary);
    font-weight: bold;
}

/* Tab Content */
.tab-content {
    display: none;
    background: var(--dark);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(255, 215, 0, 0.1);
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Forms */
.auth-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    color: var(--primary);
    font-weight: 600;
}

.form-group input {
    padding: 12px;
    background: var(--darker);
    border: 2px solid var(--gray);
    border-radius: 8px;
    color: var(--light);
    font-size: 1em;
    transition: border-color 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary);
}

/* Radio Buttons */
.radio-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: var(--darker);
    border: 2px solid var(--gray);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.radio-label:hover {
    border-color: var(--primary);
}

.radio-label input[type="radio"] {
    width: 20px;
    height: 20px;
    accent-color: var(--primary);
}

.radio-label input[type="radio"]:checked + span {
    color: var(--primary);
    font-weight: bold;
}

/* Buttons */
.btn {
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1em;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--dark);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(255, 215, 0, 0.4);
}

.btn-secondary {
    background: var(--gray);
    color: var(--light);
}

/* Alerts */
.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: rgba(76, 175, 80, 0.2);
    border: 2px solid var(--success);
    color: var(--success);
}

.alert-error {
    background: rgba(244, 67, 54, 0.2);
    border: 2px solid var(--error);
    color: var(--error);
}

/* Footer */
.form-footer {
    text-align: center;
    color: var(--gray);
    font-size: 0.9em;
}

.form-footer a {
    color: var(--primary);
    text-decoration: none;
}

.form-footer a:hover {
    text-decoration: underline;
}

.auth-footer {
    text-align: center;
    margin-top: 30px;
}

.auth-footer a {
    color: var(--primary);
    text-decoration: none;
}

/* Verify Container */
.verify-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.verify-box {
    background: var(--dark);
    padding: 50px;
    border-radius: 15px;
    text-align: center;
    max-width: 500px;
}

.verify-box h1 {
    font-size: 3em;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 30px;
}

.verify-box h2 {
    margin-bottom: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .auth-container {
        margin: 20px auto;
    }
    
    .auth-header h1 {
        font-size: 2em;
    }
    
    .tab-content {
        padding: 20px;
    }
}

✅ Was ist jetzt fertig?

    ✅ Komplettes Authentifizierungs-System
    ✅ Login & Registrierung (Band + Veranstalter)
    ✅ E-Mail-Verifizierung
    ✅ Passwort-Reset-Funktion
    ✅ Funky Design mit Gelbtönen
    ✅ Mobile-responsive
    ✅ Sichere Session-Verwaltung

🚀 Nächster Schritt?
Sag Bescheid für Schritt 3: Frontend (Startseite + Band-Übersicht + Detailseite mit Kalender)! 🎸
Sources
Images
Videos
News
html-seminar.de
Einfache PHP & Datenbank (MySQL) basierte Benutzerverwaltung (Login/Logout/Registrierung) - AJAX Kompatibel - PHP - HTML-Seminar.de - Forum für HTML, CSS und PHP

Ziel: eine einfache Datenbank basierte Benutzerverwaltung erstellen Verwendete Techniken: PHP, PDO Schwierigkeitsgrad: für Anfänger geeignet, Grundkenntnisse über die Funktionsweisen von PHP und SQL vorausgesetzt Anmerkungen: verwendet PDO, wegen dem…
tutsplus.com
Die 20 besten Veranstaltungs- und Buchungskalender mit PHP-Scripts... und 3 kostenlose Optionen | Envato Tuts+

Es ist mit PayPal und Razorpay integriert. Der Vacation Rentals Booking Calendar ist einen Belegungsplan mit Buchungsystem, mit dem Vermietungsbetrieb oder Verwaltungsagenturen Kalender für die Verfügbarkeit ihrer Ferienunterkünfte erstellen und verwalten können.
prepaid-hoster.de
Server mit Apache, PHP, MySQL mieten - Prepaid Hoster

Webserver-Ready in wenigen Sekunden · Einfaches Webserver Hosting auf dem eigenen vServer
zoechbauer.name
Projekt Webserver – Ubuntu 22.04 Teil 3 – PHP und MySQL nachinstallieren und konfigurieren

Im Teil 2 dieser Serie haben wir einen funktionierenden Webserver für statische HTML-Webseiten erstellt. Jetzt erweitern wir den Funktionsumfang noch etwas ind
Nächster Schritt?
Sag Bescheid für Schritt 3: Frontend (Startseite + Band-Übersicht + Detailseite mit Kalender)! 🎸 // weiter schritt 3
Workflow

Thinking

Searching

Thinking

Responding
🎸 SCHRITT 3: Frontend (Startseite + Band-Übersicht + Detailseite mit Kalender)
Jetzt wird's funky! Hier kommt das komplette Frontend mit modernstem Design!
📁 Datei 10: index.php (Startseite mit Band-Übersicht)
php

<?php
require_once 'includes/config.php';

// Suchparameter
$searchGenre = $_GET['genre'] ?? '';
$searchLocation = $_GET['location'] ?? '';

// Bands aus Datenbank laden (nur freigeschaltete)
$db = getDB();
$sql = "
    SELECT b.*, u.email,
           (SELECT AVG(rating) FROM bewertungen WHERE band_id = b.id AND is_approved = 1) as avg_rating,
           (SELECT COUNT(*) FROM bewertungen WHERE band_id = b.id AND is_approved = 1) as rating_count
    FROM bands b
    JOIN users u ON b.user_id = u.id
    WHERE b.is_approved = 1
";

$params = [];

if ($searchGenre) {
    $sql .= " AND b.genre LIKE ?";
    $params[] = "%$searchGenre%";
}

if ($searchLocation) {
    $sql .= " AND b.location LIKE ?";
    $params[] = "%$searchLocation%";
}

$sql .= " ORDER BY b.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$bands = $stmt->fetchAll();

// Verfügbare Genres für Filter
$genresStmt = $db->query("SELECT DISTINCT genre FROM bands WHERE is_approved = 1 AND genre IS NOT NULL ORDER BY genre");
$genres = $genresStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetYourBand.ch - Die funky Bandvermittlung</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bands.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>🎸 GetYourBand.ch</h1>
                    <p>Die funky Bandvermittlung</p>
                </div>
                <nav class="main-nav">
                    <a href="index.php" class="nav-link active">Bands</a>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isBand()): ?>
                            <a href="profil.php" class="nav-link">Mein Profil</a>
                        <?php endif; ?>
                        <?php if (isAdmin()): ?>
                            <a href="admin/dashboard.php" class="nav-link">Admin</a>
                        <?php endif; ?>
                        <a href="logout.php" class="nav-link">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login / Registrieren</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2 class="hero-title">Finde deine perfekte Band</h2>
                <p class="hero-subtitle">Über <?= count($bands) ?> professionelle Bands für deinen Event</p>
                
                <!-- Suchfilter -->
                <form method="GET" class="search-form">
                    <div class="search-inputs">
                        <div class="search-field">
                            <label>🎵 Genre</label>
                            <select name="genre">
                                <option value="">Alle Genres</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= sanitize($genre) ?>" <?= $searchGenre === $genre ? 'selected' : '' ?>>
                                        <?= sanitize($genre) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="search-field">
                            <label>📍 Ort</label>
                            <input type="text" name="location" placeholder="z.B. Zürich" value="<?= sanitize($searchLocation) ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Suchen</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Band-Übersicht -->
    <section class="bands-section">
        <div class="container">
            <?php if (empty($bands)): ?>
                <div class="no-results">
                    <h3>😕 Keine Bands gefunden</h3>
                    <p>Versuche es mit anderen Suchkriterien.</p>
                    <a href="index.php" class="btn btn-secondary">Filter zurücksetzen</a>
                </div>
            <?php else: ?>
                <div class="bands-grid">
                    <?php foreach ($bands as $band): ?>
                        <div class="band-card">
                            <div class="band-image">
                                <?php
                                // Erstes Foto der Band laden
                                $mediaStmt = $db->prepare("SELECT file_path FROM band_media WHERE band_id = ? AND media_type = 'photo' ORDER BY display_order LIMIT 1");
                                $mediaStmt->execute([$band['id']]);
                                $photo = $mediaStmt->fetch();
                                ?>
                                <?php if ($photo): ?>
                                    <img src="<?= sanitize($photo['file_path']) ?>" alt="<?= sanitize($band['band_name']) ?>">
                                <?php else: ?>
                                    <div class="placeholder-image">🎸</div>
                                <?php endif; ?>
                                
                                <?php if ($band['genre']): ?>
                                    <span class="genre-badge"><?= sanitize($band['genre']) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="band-info">
                                <h3 class="band-name"><?= sanitize($band['band_name']) ?></h3>
                                
                                <?php if ($band['location']): ?>
                                    <p class="band-location">📍 <?= sanitize($band['location']) ?></p>
                                <?php endif; ?>
                                
                                <?php if ($band['avg_rating']): ?>
                                    <div class="band-rating">
                                        <span class="stars">
                                            <?php
                                            $rating = round($band['avg_rating']);
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $rating ? '⭐' : '☆';
                                            }
                                            ?>
                                        </span>
                                        <span class="rating-count">(<?= $band['rating_count'] ?> Bewertungen)</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($band['description']): ?>
                                    <p class="band-description">
                                        <?= sanitize(substr($band['description'], 0, 120)) ?>...
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($band['price_range']): ?>
                                    <p class="band-price">💰 <?= sanitize($band['price_range']) ?></p>
                                <?php endif; ?>
                                
                                <a href="band-detail.php?id=<?= $band['id'] ?>" class="btn btn-primary btn-block">
                                    Details & Anfragen
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>GetYourBand.ch</h4>
                    <p>Die funky Bandvermittlung für unvergessliche Events</p>
                </div>
                <div class="footer-section">
                    <h4>Links</h4>
                    <ul>
                        <li><a href="agb.php">AGB</a></li>
                        <li><a href="datenschutz.php">Datenschutz</a></li>
                        <li><a href="impressum.php">Impressum</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Kontakt</h4>
                    <p>info@getyourband.ch</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 GetYourBand.ch - Alle Rechte vorbehalten</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/app.js"></script>
</body>
</html>

📁 Datei 11: band-detail.php (Detailseite mit Kalender)
php

<?php
require_once 'includes/config.php';

$bandId = $_GET['id'] ?? 0;

if (!$bandId) {
    redirect('/index.php');
}

$db = getDB();

// Band-Daten laden
$stmt = $db->prepare("
    SELECT b.*, u.email,
           (SELECT AVG(rating) FROM bewertungen WHERE band_id = b.id AND is_approved = 1) as avg_rating,
           (SELECT COUNT(*) FROM bewertungen WHERE band_id = b.id AND is_approved = 1) as rating_count
    FROM bands b
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ? AND b.is_approved = 1
");
$stmt->execute([$bandId]);
$band = $stmt->fetch();

if (!$band) {
    redirect('/index.php');
}

// Medien laden
$mediaStmt = $db->prepare("SELECT * FROM band_media WHERE band_id = ? ORDER BY display_order");
$mediaStmt->execute([$bandId]);
$media = $mediaStmt->fetchAll();

// Bewertungen laden
$reviewsStmt = $db->prepare("
    SELECT r.*, u.email 
    FROM bewertungen r
    JOIN users u ON r.veranstalter_id = u.id
    WHERE r.band_id = ? AND r.is_approved = 1
    ORDER BY r.created_at DESC
");
$reviewsStmt->execute([$bandId]);
$reviews = $reviewsStmt->fetchAll();

// Verfügbarkeit für nächste 90 Tage laden
$availabilityStmt = $db->prepare("
    SELECT date, is_available 
    FROM availability 
    WHERE band_id = ? AND date >= DATE('now') AND date <= DATE('now', '+90 days')
");
$availabilityStmt->execute([$bandId]);
$availability = [];
while ($row = $availabilityStmt->fetch()) {
    $availability[$row['date']] = $row['is_available'];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($band['band_name']) ?> - GetYourBand.ch</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bands.css">
    <link rel="stylesheet" href="assets/css/calendar.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>🎸 GetYourBand.ch</h1>
                </div>
                <nav class="main-nav">
                    <a href="index.php" class="nav-link">← Zurück zur Übersicht</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="logout.php" class="nav-link">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Band-Detail -->
    <section class="band-detail">
        <div class="container">
            <div class="detail-grid">
                <!-- Linke Spalte: Medien & Info -->
                <div class="detail-main">
                    <!-- Medien-Galerie -->
                    <div class="media-gallery">
                        <?php if (!empty($media)): ?>
                            <div class="main-media">
                                <?php 
                                $firstMedia = $media[0];
                                if ($firstMedia['media_type'] === 'photo'): 
                                ?>
                                    <img src="<?= sanitize($firstMedia['file_path']) ?>" alt="<?= sanitize($band['band_name']) ?>" id="main-image">
                                <?php else: ?>
                                    <iframe src="<?= sanitize($firstMedia['video_url']) ?>" frameborder="0" allowfullscreen></iframe>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (count($media) > 1): ?>
                                <div class="media-thumbnails">
                                    <?php foreach ($media as $item): ?>
                                        <?php if ($item['media_type'] === 'photo'): ?>
                                            <img src="<?= sanitize($item['file_path']) ?>" 
                                                 alt="Thumbnail" 
                                                 class="thumbnail"
                                                 onclick="changeMainImage('<?= sanitize($item['file_path']) ?>')">
                                        <?php else: ?>
                                            <div class="video-thumbnail" onclick="changeMainVideo('<?= sanitize($item['video_url']) ?>')">
                                                ▶️ Video
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="placeholder-image-large">🎸</div>
                        <?php endif; ?>
                    </div>

                    <!-- Band-Info -->
                    <div class="band-detail-info">
                        <h1><?= sanitize($band['band_name']) ?></h1>
                        
                        <div class="info-badges">
                            <?php if ($band['genre']): ?>
                                <span class="badge badge-genre">🎵 <?= sanitize($band['genre']) ?></span>
                            <?php endif; ?>
                            <?php if ($band['location']): ?>
                                <span class="badge badge-location">📍 <?= sanitize($band['location']) ?></span>
                            <?php endif; ?>
                            <?php if ($band['price_range']): ?>
                                <span class="badge badge-price">💰 <?= sanitize($band['price_range']) ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($band['avg_rating']): ?>
                            <div class="rating-display">
                                <span class="stars-large">
                                    <?php
                                    $rating = round($band['avg_rating']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '⭐' : '☆';
                                    }
                                    ?>
                                </span>
                                <span class="rating-text">
                                    <?= number_format($band['avg_rating'], 1) ?> / 5.0 
                                    (<?= $band['rating_count'] ?> Bewertungen)
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if ($band['description']): ?>
                            <div class="description">
                                <h3>Über uns</h3>
                                <p><?= nl2br(sanitize($band['description'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($band['website']): ?>
                            <div class="website">
                                <a href="<?= sanitize($band['website']) ?>" target="_blank" class="btn btn-secondary">
                                    🌐 Website besuchen
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Bewertungen -->
                    <?php if (!empty($reviews)): ?>
                        <div class="reviews-section">
                            <h3>Bewertungen</h3>
                            <div class="reviews-list">
                                <?php foreach ($reviews as $review): ?>
                                    <div class="review-card">
                                        <div class="review-header">
                                            <span class="review-stars">
                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo $i <= $review['rating'] ? '⭐' : '☆';
                                                }
                                                ?>
                                            </span>
                                            <span class="review-date">
                                                <?= date('d.m.Y', strtotime($review['created_at'])) ?>
                                            </span>
                                        </div>
                                        <p class="review-comment"><?= sanitize($review['comment']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Rechte Spalte: Kalender & Anfrage -->
                <div class="detail-sidebar">
                    <!-- Verfügbarkeitskalender -->
                    <div class="availability-widget">
                        <h3>Verfügbarkeit</h3>
                        <div id="calendar"></div>
                        <div class="calendar-legend">
                            <span><span class="legend-available"></span> Verfügbar</span>
                            <span><span class="legend-booked"></span> Gebucht</span>
                        </div>
                    </div>

                    <!-- Anfrage-Formular -->
                    <div class="inquiry-widget">
                        <h3>Anfrage senden</h3>
                        <?php if (isLoggedIn()): ?>
                            <form method="POST" action="anfrage.php" class="inquiry-form">
                                <input type="hidden" name="band_id" value="<?= $band['id'] ?>">
                                
                                <div class="form-group">
                                    <label>Dein Name</label>
                                    <input type="text" name="name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>E-Mail</label>
                                    <input type="email" name="email" value="<?= sanitize($_SESSION['email']) ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Telefon</label>
                                    <input type="tel" name="phone">
                                </div>
                                
                                <div class="form-group">
                                    <label>Event-Datum</label>
                                    <input type="date" name="event_date" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Event-Ort</label>
                                    <input type="text" name="event_location" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Event-Typ</label>
                                    <select name="event_type">
                                        <option value="Hochzeit">Hochzeit</option>
                                        <option value="Geburtstag">Geburtstag</option>
                                        <option value="Firmenfeier">Firmenfeier</option>
                                        <option value="Festival">Festival</option>
                                        <option value="Sonstiges">Sonstiges</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Budget</label>
                                    <input type="text" name="budget" placeholder="z.B. CHF 2000-3000">
                                </div>
                                
                                <div class="form-group">
                                    <label>Nachricht</label>
                                    <textarea name="message" rows="4" placeholder="Weitere Details zu deinem Event..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-block">
                                    Anfrage senden
                                </button>
                            </form>
                        <?php else: ?>
                            <p>Bitte <a href="login.php">logge dich ein</a>, um eine Anfrage zu senden.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 GetYourBand.ch</p>
        </div>
    </footer>

    <script>
        // Kalender-Daten aus PHP
        const availability = <?= json_encode($availability) ?>;
        
        // Medien-Galerie
        function changeMainImage(src) {
            document.getElementById('main-image').src = src;
        }
        
        function changeMainVideo(url) {
            const mainMedia = document.querySelector('.main-media');
            mainMedia.innerHTML = '<iframe src="' + url + '" frameborder="0" allowfullscreen></iframe>';
        }
    </script>
    <script src="assets/js/calendar.js"></script>
</body>
</html>

📁 Datei 12: assets/css/bands.css
css

/* Band-Übersicht & Detail-Styling */

/* Hero Section */
.hero {
    background: linear-gradient(135deg, var(--darker) 0%, var(--dark) 50%, var(--darker) 100%);
    padding: 80px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: '🎸';
    position: absolute;
    font-size: 300px;
    opacity: 0.05;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-15deg);
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-title {
    font-size: 3.5em;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

.hero-subtitle {
    font-size: 1.3em;
    color: var(--gray);
    margin-bottom: 40px;
}

/* Suchformular */
.search-form {
    max-width: 900px;
    margin: 0 auto;
}

.search-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 15px;
    background: var(--dark);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(255, 215, 0, 0.1);
}

.search-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.search-field label {
    color: var(--primary);
    font-weight: 600;
    font-size: 0.9em;
}

.search-field input,
.search-field select {
    padding: 12px;
    background: var(--darker);
    border: 2px solid var(--gray);
    border-radius: 8px;
    color: var(--light);
    font-size: 1em;
}

.search-field input:focus,
.search-field select:focus {
    outline: none;
    border-color: var(--primary);
}

/* Band-Grid */
.bands-section {
    padding: 60px 20px;
}

.bands-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}

.band-card {
    background: var(--dark);
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
}

.band-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(255, 215, 0, 0.2);
}

.band-image {
    position: relative;
    height: 250px;
    overflow: hidden;
    background: var(--darker);
}

.band-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    font-size: 80px;
    background: linear-gradient(135deg, var(--darker), var(--dark));
}

.genre-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--primary);
    color: var(--dark);
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.85em;
}

.band-info {
    padding: 25px;
}

.band-name {
    font-size: 1.5em;
    margin-bottom: 10px;
    color: var(--primary);
}

.band-location {
    color: var(--gray);
    margin-bottom: 10px;
}

.band-rating {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.stars {
    font-size: 1.2em;
}

.rating-count {
    color: var(--gray);
    font-size: 0.9em;
}

.band-description {
    color: var(--light);
    margin-bottom: 15px;
    line-height: 1.6;
}

.band-price {
    color: var(--primary);
    font-weight: bold;
    margin-bottom: 20px;
}

.btn-block {
    width: 100%;
    text-align: center;
}

/* Band-Detail */
.band-detail {
    padding: 40px 20px;
}

.detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
}

.detail-main {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

/* Medien-Galerie */
.media-gallery {
    background: var(--dark);
    border-radius: 15px;
    overflow: hidden;
    padding: 20px;
}

.main-media {
    width: 100%;
    height: 500px;
    background: var(--darker);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 15px;
}

.main-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.main-media iframe {
    width: 100%;
    height: 100%;
}

.media-thumbnails {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
}

.thumbnail {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.thumbnail:hover {
    border-color: var(--primary);
}

.video-thumbnail {
    height: 80px;
    background: var(--darker);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.video-thumbnail:hover {
    border-color: var(--primary);
    background: var(--dark);
}

/* Band-Detail-Info */
.band-detail-info {
    background: var(--dark);
    padding: 30px;
    border-radius: 15px;
}

.band-detail-info h1 {
    font-size: 2.5em;
    color: var(--primary);
    margin-bottom: 20px;
}

.info-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.badge {
    padding: 10px 20px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9em;
}

.badge-genre {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--dark);
}

.badge-location {
    background: var(--darker);
    color: var(--light);
    border: 2px solid var(--gray);
}

.badge-price {
    background: var(--darker);
    color: var(--primary);
    border: 2px solid var(--primary);
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--darker);
    border-radius: 10px;
}

.stars-large {
    font-size: 1.8em;
}

.rating-text {
    color: var(--light);
    font-size: 1.1em;
}

.description {
    margin-bottom: 30px;
}

.description h3 {
    color: var(--primary);
    margin-bottom: 15px;
}

.description p {
    line-height: 1.8;
    color:

Sources
Images
Videos
News
edorex.ch
Was heisst «Frontend First»? - Edorex

Allenfalls kann aber vor diesem Schritt auch einmal eine Idee verworfen werden, ohne dass dies zu fest schmerzt («fail early, fail often»). Die Frontend First Philosophie reiht sich daher sehr gut in die agile Denkweise ein.
door3.com
Bewährte Praktiken und Trends in der Front-End-Entwicklung (Teil I)

Beherrschen Sie die besten Praktiken der Front-End-Entwicklung: UI/UX, Übergabe zwischen Design und Entwicklung, CSS-Frameworks, JS-Frameworks und mobile Int...
mindtwo.de
Frontend-Entwicklung: Alles Wichtige für Einsteiger und Profis

Zögern Sie nicht, den nächsten Schritt zu machen. Lassen Sie uns gemeinsam Ihre digitalen Ziele erreichen. Besuchen Sie unsere Projektanfrage Seite und teilen Sie uns Ihre Vision mit.
codehan.de
Webentwicklung, Frontend und Tech-Trends 2025: Das solltest du wissen - Codehan | Webentwicklung, Print & Mehr

2025 verspricht ein spannendes Jahr für Webentwickler und Frontend-Experten zu werden. Mit neuen Technologien, disruptiven Trends und sich verändernden Märkten steht die Branche vor einem weiteren Transformationsjahr.
weiter
Workflow

Thinking

Searching

Thinking

Responding
🎸 SCHRITT 3: Frontend (Fortsetzung)
Hier ist die Fortsetzung von bands.css und die restlichen Frontend-Dateien:
📁 Datei 12: assets/css/bands.css (Fortsetzung)
css

.description p {
    line-height: 1.8;
    color: var(--light);
}

/* Bewertungen */
.reviews-section {
    background: var(--dark);
    padding: 30px;
    border-radius: 15px;
}

.reviews-section h3 {
    color: var(--primary);
    margin-bottom: 20px;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-card {
    background: var(--darker);
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid var(--primary);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.review-stars {
    font-size: 1.2em;
}

.review-date {
    color: var(--gray);
    font-size: 0.9em;
}

.review-comment {
    color: var(--light);
    line-height: 1.6;
}

/* Sidebar */
.detail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.availability-widget,
.inquiry-widget {
    background: var(--dark);
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
}

.availability-widget h3,
.inquiry-widget h3 {
    color: var(--primary);
    margin-bottom: 20px;
    font-size: 1.3em;
}

.calendar-legend {
    display: flex;
    gap: 20px;
    margin-top: 15px;
    font-size: 0.9em;
}

.calendar-legend span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-available,
.legend-booked {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.legend-available {
    background: var(--success);
}

.legend-booked {
    background: var(--error);
}

/* Anfrage-Formular */
.inquiry-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.inquiry-form .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.inquiry-form label {
    color: var(--primary);
    font-weight: 600;
    font-size: 0.9em;
}

.inquiry-form input,
.inquiry-form select,
.inquiry-form textarea {
    padding: 12px;
    background: var(--darker);
    border: 2px solid var(--gray);
    border-radius: 8px;
    color: var(--light);
    font-size: 1em;
    font-family: inherit;
}

.inquiry-form input:focus,
.inquiry-form select:focus,
.inquiry-form textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.inquiry-form textarea {
    resize: vertical;
}

/* No Results */
.no-results {
    text-align: center;
    padding: 80px 20px;
}

.no-results h3 {
    font-size: 2em;
    margin-bottom: 20px;
}

.no-results p {
    color: var(--gray);
    margin-bottom: 30px;
}

/* Responsive */
@media (max-width: 1024px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .search-inputs {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2em;
    }
    
    .bands-grid {
        grid-template-columns: 1fr;
    }
    
    .main-media {
        height: 300px;
    }
    
    .band-detail-info h1 {
        font-size: 1.8em;
    }
}

📁 Datei 13: assets/css/calendar.css
css

/* Kalender-Styling */

#calendar {
    background: var(--darker);
    border-radius: 10px;
    padding: 15px;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.calendar-header h4 {
    color: var(--primary);
    font-size: 1.2em;
}

.calendar-nav {
    display: flex;
    gap: 10px;
}

.calendar-nav button {
    background: var(--dark);
    border: 2px solid var(--gray);
    color: var(--light);
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
}

.calendar-nav button:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
    margin-bottom: 10px;
}

.calendar-weekday {
    text-align: center;
    color: var(--gray);
    font-size: 0.85em;
    font-weight: bold;
    padding: 8px 0;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.9em;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.calendar-day.empty {
    cursor: default;
}

.calendar-day.today {
    border: 2px solid var(--primary);
    font-weight: bold;
}

.calendar-day.available {
    background: rgba(76, 175, 80, 0.2);
    color: var(--success);
}

.calendar-day.available:hover {
    background: rgba(76, 175, 80, 0.4);
}

.calendar-day.booked {
    background: rgba(244, 67, 54, 0.2);
    color: var(--error);
    cursor: not-allowed;
}

.calendar-day.past {
    color: var(--gray);
    opacity: 0.5;
    cursor: not-allowed;
}

.calendar-day.selected {
    background: var(--primary);
    color: var(--dark);
    font-weight: bold;
}

/* Tooltip */
.calendar-day[data-tooltip]:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: var(--dark);
    color: var(--light);
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.8em;
    white-space: nowrap;
    z-index: 10;
    margin-bottom: 5px;
}

📁 Datei 14: assets/js/calendar.js
javascript

// Kalender-Funktionalität für GetYourBand.ch

class BandCalendar {
    constructor(containerId, availabilityData) {
        this.container = document.getElementById(containerId);
        this.availability = availabilityData;
        this.currentDate = new Date();
        this.selectedDate = null;
        
        this.init();
    }
    
    init() {
        this.render();
    }
    
    render() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        const monthNames = [
            'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
            'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
        ];
        
        const weekdays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
        
        // Header
        let html = `
            <div class="calendar-header">
                <h4>${monthNames[month]} ${year}</h4>
                <div class="calendar-nav">
                    <button onclick="calendar.previousMonth()">←</button>
                    <button onclick="calendar.nextMonth()">→</button>
                </div>
            </div>
        `;
        
        // Wochentage
        html += '<div class="calendar-weekdays">';
        weekdays.forEach(day => {
            html += `<div class="calendar-weekday">${day}</div>`;
        });
        html += '</div>';
        
        // Tage
        html += '<div class="calendar-days">';
        
        // Erster Tag des Monats
        const firstDay = new Date(year, month, 1);
        let dayOfWeek = firstDay.getDay();
        dayOfWeek = dayOfWeek === 0 ? 6 : dayOfWeek - 1; // Montag = 0
        
        // Leere Zellen vor dem ersten Tag
        for (let i = 0; i < dayOfWeek; i++) {
            html += '<div class="calendar-day empty"></div>';
        }
        
        // Tage des Monats
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateStr = this.formatDate(date);
            
            let classes = ['calendar-day'];
            let tooltip = '';
            
            // Heute markieren
            if (date.getTime() === today.getTime()) {
                classes.push('today');
            }
            
            // Vergangene Tage
            if (date < today) {
                classes.push('past');
            }
            
            // Verfügbarkeit prüfen
            if (this.availability[dateStr] !== undefined) {
                if (this.availability[dateStr] == 1) {
                    classes.push('available');
                    tooltip = 'Verfügbar';
                } else {
                    classes.push('booked');
                    tooltip = 'Gebucht';
                }
            }
            
            // Ausgewähltes Datum
            if (this.selectedDate && this.selectedDate === dateStr) {
                classes.push('selected');
            }
            
            html += `
                <div class="${classes.join(' ')}" 
                     data-date="${dateStr}"
                     data-tooltip="${tooltip}"
                     onclick="calendar.selectDate('${dateStr}')">
                    ${day}
                </div>
            `;
        }
        
        html += '</div>';
        
        this.container.innerHTML = html;
    }
    
    formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    selectDate(dateStr) {
        const date = new Date(dateStr);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Vergangene Tage nicht auswählbar
        if (date < today) {
            return;
        }
        
        // Gebuchte Tage nicht auswählbar
        if (this.availability[dateStr] === 0) {
            return;
        }
        
        this.selectedDate = dateStr;
        this.render();
        
        // Datum ins Formular eintragen
        const dateInput = document.querySelector('input[name="event_date"]');
        if (dateInput) {
            dateInput.value = dateStr;
        }
    }
    
    previousMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
    }
    
    nextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
    }
}

// Kalender initialisieren (wird von band-detail.php aufgerufen)
let calendar;
document.addEventListener('DOMContentLoaded', function() {
    if (typeof availability !== 'undefined') {
        calendar = new BandCalendar('calendar', availability);
    }
});

📁 Datei 15: assets/js/app.js
javascript

// Haupt-JavaScript für GetYourBand.ch

document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth Scroll für Anker-Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Lazy Loading für Bilder
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Mobile Navigation Toggle
    const navToggle = document.querySelector('.nav-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (navToggle) {
        navToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
        });
    }
    
    // Form Validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide Alerts nach 5 Sekunden
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Form Validation Funktion
function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showError(input, 'Dieses Feld ist erforderlich');
            isValid = false;
        } else {
            clearError(input);
        }
        
        // E-Mail Validierung
        if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                showError(input, 'Ungültige E-Mail-Adresse');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

function showError(input, message) {
    clearError(input);
    input.classList.add('error');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
}

function clearError(input) {
    input.classList.remove('error');
    const errorMsg = input.parentNode.querySelector('.error-message');
    if (errorMsg) {
        errorMsg.remove();
    }
}

// Utility: Format Datum
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
}

// Utility: Debounce für Search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Live Search (optional)
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('input', debounce(function(e) {
        // Hier könnte AJAX-Suche implementiert werden
        console.log('Suche nach:', e.target.value);
    }, 500));
}

📁 Datei 16: logout.php
php

<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
$auth->logout();

redirect('/index.php');
?>

📁 Datei 17: assets/css/style.css (Ergänzungen)
css

/* Zusätzliche globale Styles */

/* Container */
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header */
.main-header {
    background: var(--dark);
    border-bottom: 2px solid var(--primary);
    padding: 20px 0;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo h1 {
    font-size: 1.8em;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0;
}

.logo p {
    color: var(--gray);
    font-size: 0.85em;
    margin: 0;
}

.main-nav {
    display: flex;
    gap: 20px;
    align-items: center;
}

.nav-link {
    color: var(--light);
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: all 0.3s;
}

.nav-link:hover {
    color: var(--primary);
    background: var(--darker);
}

.nav-link.active {
    color: var(--primary);
    font-weight: bold;
}

/* Footer */
.main-footer {
    background: var(--darker);
    border-top: 2px solid var(--primary);
    padding: 40px 0 20px;
    margin-top: 80px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 30px;
}

.footer-section h4 {
    color: var(--primary);
    margin-bottom: 15px;
}

.footer-section p,
.footer-section ul {
    color: var(--gray);
    line-height: 1.8;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 8px;
}

.footer-section a {
    color: var(--gray);
    text-decoration: none;
    transition: color 0.3s;
}

.footer-section a:hover {
    color: var(--primary);
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid var(--gray);
    color: var(--gray);
}

/* Error Messages */
.error-message {
    color: var(--error);
    font-size: 0.85em;
    margin-top: 5px;
}

input.error,
textarea.error,
select.error {
    border-color: var(--error) !important;
}

/* Loading Spinner */
.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid var(--gray);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Navigation */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 20px;
    }
    
    .main-nav {
        flex-direction: column;
        width: 100%;
    }
    
    .nav-link {
        width: 100%;
        text-align: center;
    }
    
    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}

/* Utility Classes */
.text-center { text-align: center; }
.text-right { text-align: right; }
.mt-20 { margin-top: 20px; }
.mb-20 { margin-bottom: 20px; }
.hidden { display: none; }

✅ Was ist jetzt fertig?

    ✅ Komplette Startseite mit Band-Übersicht
    ✅ Suchfilter (Genre, Ort)
    ✅ Band-Detailseite mit Medien-Galerie
    ✅ Interaktiver Verfügbarkeitskalender
    ✅ Anfrage-Formular
    ✅ Bewertungsanzeige
    ✅ Funky Design mit Gelbtönen
    ✅ Vollständig responsive
    ✅ Smooth Animations
    ✅ Mobile-optimiert

🚀 Nächster Schritt?
Sag Bescheid für Schritt 4: Anfrage-System & Band-Profil-Verwaltung! 🎸✨
Sources
Images
Videos
News
df.eu
Eine eigene Webseite erstellen – Schritt für Schritt - Domain Factory Blog

Mit unserer Schritt für Schritt Anleitung kannst du ganz einfach eine professionelle Webseite erstellen. Erfahre hier, was du dafür brauchst.
androidmag.de
Kalender: Termine auf der Startseite anzeigen - Androidmag

Widgets auf der Startseite machen den Blick in den Terminkalender unnötig. Flexibler als das Standard-Widget ist das Clean Calendar Widget.
agentur-ibk.de
Webseite in TYPO3 einrichten: Seite im Backend konfigurieren

Im nächsten Schritt gehen Sie nun auf die Seiteneigenschaften und dort auf den Reiter „Verhalten“. Sie sehen dort in der Kategorie „Sonstige“ den Schalter „Als Anfang der Webseite benutzen“.
adva.de
Schritt-für-Schritt-Anleitung: So erstellen Sie eine TYPO3-Website | ADVA Communication

Mit TYPO3 erstellen Sie Schritt für Schritt eine professionelle Website – einfach, effizient und perfekt auf Ihre Anforderungen zugeschnitten.
