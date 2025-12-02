# 🎸 GetYourBand - Bandvermittlungsplattform

Eine moderne, professionelle Plattform für die Vermittlung von Live-Bands in der Schweiz.

## 🚀 Features

- ✨ **Moderne MVC-Architektur** - Saubere Trennung von Logik, Daten und Präsentation
- 🎨 **Tailwind CSS** - Modernes, responsives Design mit gelben Farbtönen
- ⚡ **Alpine.js** - Leichtgewichtige JavaScript-Interaktivität
- 🔐 **Authentifizierung** - Login, Registrierung, E-Mail-Verifizierung
- 👥 **Mehrere Rollen** - Admin, Band, Kunde
- 🔍 **Erweiterte Suche** - Nach Genre, Ort, Preis filtern
- ⭐ **Bewertungssystem** - Nur nach Buchung möglich
- 📅 **Verfügbarkeitskalender** - Bands können Verfügbarkeit verwalten
- 💳 **PayPal-Integration** - Optional aktivierbare Zahlungen
- 📧 **E-Mail-Benachrichtigungen** - Automatische Updates
- 🛡️ **DSGVO-konform** - Cookie-Banner, Datenschutz
- 📱 **Mobile-First** - Optimiert für alle Geräte

## 📋 Voraussetzungen

- PHP 8.3 oder höher
- MySQL 5.7+ oder MariaDB 10.3+
- Apache mit mod_rewrite
- Composer
- Node.js & npm (für Frontend-Build)

## 🔧 Installation

### 1. Repository klonen

```bash
git clone <repository-url>
cd ai_playgroud
```

### 2. PHP-Abhängigkeiten installieren

```bash
composer install
```

### 3. Frontend-Abhängigkeiten installieren

```bash
npm install
```

### 4. Umgebungskonfiguration

```bash
cp .env.example .env
```

Passe die `.env`-Datei an:

```env
# Datenbank
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=getyourband
DB_USERNAME=root
DB_PASSWORD=dein_passwort

# Mail (SMTP)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=deine@email.ch
MAIL_PASSWORD=dein_passwort

# Optional: PayPal
PAYPAL_CLIENT_ID=deine_client_id
PAYPAL_SECRET=dein_secret
PAYMENT_ENABLED=true
```

### 5. Datenbank erstellen

```bash
mysql -u root -p -e "CREATE DATABASE getyourband CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Migrationen ausführen

```bash
php migrate.php
```

### 7. Frontend-Assets kompilieren

**Entwicklung:**
```bash
npm run dev
```

**Produktion:**
```bash
npm run build
```

### 8. Berechtigungen setzen

```bash
chmod -R 755 storage
chmod -R 755 public/uploads
```

## 🌐 Entwicklungsserver

### Option 1: PHP Built-in Server

```bash
cd public
php -S localhost:8000
```

Öffne: http://localhost:8000

### Option 2: Apache/XAMPP

1. Erstelle einen Virtual Host oder nutze htdocs
2. Stelle sicher, dass `mod_rewrite` aktiviert ist
3. DocumentRoot sollte auf das Hauptverzeichnis zeigen (nicht /public!)

## 📁 Projektstruktur

```
.
├── app/
│   ├── Controllers/        # Controller-Klassen
│   ├── Models/            # Datenmodelle
│   ├── Views/             # View-Templates
│   ├── Middleware/        # Middleware (Auth, etc.)
│   ├── Core/              # Kern-Framework (Router, Controller, Model)
│   └── helpers.php        # Helper-Funktionen
├── config/                # Konfigurationsdateien
├── database/
│   ├── migrations/        # SQL-Migrationen
│   └── Database.php       # Datenbankverbindung
├── public/                # Öffentliches Verzeichnis (DocumentRoot)
│   ├── index.php          # Entry Point
│   ├── .htaccess          # Apache-Konfiguration
│   ├── css/               # Kompilierte CSS
│   ├── js/                # Kompilierte JS
│   └── uploads/           # User-Uploads
├── resources/
│   ├── css/               # Quell-CSS (Tailwind)
│   └── js/                # Quell-JavaScript
├── routes/
│   └── web.php            # Route-Definitionen
├── storage/               # Temporäre Dateien, Logs, Cache
├── .env                   # Umgebungsvariablen (nicht committen!)
├── composer.json          # PHP-Abhängigkeiten
├── package.json           # Frontend-Abhängigkeiten
├── tailwind.config.js     # Tailwind-Konfiguration
└── vite.config.js         # Vite-Build-Konfiguration
```

## 🎨 Design & Farben

Das Projekt nutzt ein modernes gelbes Farbschema:

- **Primary**: Gelb-Orange-Töne (#fbbf24 - #f59e0b)
- **Accent**: Helles Gelb (#eab308 - #facc15)
- **Schrift**: Inter (Body), Poppins (Headlines)

## 🔐 Standard-Admin erstellen

Nach der Migration kannst du einen Admin-Account manuell in der Datenbank erstellen:

```sql
INSERT INTO users (email, password, name, role, email_verified_at, is_active)
VALUES (
    'admin@getyourband.ch',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- "password"
    'Admin',
    'admin',
    NOW(),
    1
);
```

**Login:** admin@getyourband.ch
**Passwort:** password

⚠️ **Wichtig:** Ändere das Passwort nach dem ersten Login!

## 📝 Routen-Übersicht

### Öffentlich
- `GET /` - Homepage
- `GET /bands` - Band-Liste
- `GET /bands/{slug}` - Band-Detail
- `GET /login` - Login-Formular
- `POST /login` - Login-Verarbeitung
- `GET /register` - Registrierungs-Formular
- `POST /register` - Registrierung

### Geschützt (Authentifiziert)
- `GET /profile` - User-Profil
- `POST /profile/update` - Profil aktualisieren
- `POST /bookings/create` - Buchung erstellen
- `GET /my-bookings` - Meine Buchungen

### Band-Bereich
- `GET /band/manage` - Band-Verwaltung
- `POST /band/update` - Band aktualisieren
- `GET /band/bookings` - Eingehende Buchungsanfragen

### Admin-Bereich
- `GET /admin` - Admin-Dashboard
- `GET /admin/bands` - Band-Verwaltung
- `POST /admin/bands/{id}/approve` - Band freischalten
- `GET /admin/reviews` - Bewertungen moderieren

## 🧪 Entwicklung

### Tailwind-Klassen neu kompilieren

```bash
npm run watch
```

Dies startet einen Watch-Modus, der bei Änderungen automatisch neu kompiliert.

### Neue Migration erstellen

Erstelle eine neue SQL-Datei in `database/migrations/`:

```bash
touch database/migrations/007_create_new_table.sql
```

Führe sie aus:

```bash
php migrate.php
```

### Neuen Controller erstellen

```php
<?php

namespace App\Controllers;

use App\Core\Controller;

class MyController extends Controller
{
    public function index(): void
    {
        $this->view('my-view', [
            'data' => 'value'
        ]);
    }
}
```

### Neues Model erstellen

```php
<?php

namespace App\Models;

use App\Core\Model;

class MyModel extends Model
{
    protected string $table = 'my_table';

    protected array $fillable = [
        'column1',
        'column2',
    ];
}
```

## 🐛 Debugging

Debug-Modus aktivieren in `.env`:

```env
APP_DEBUG=true
```

Im Debug-Modus werden ausführliche Fehler angezeigt.

### Nützliche Helper-Funktionen

```php
dd($variable);              // Dump & Die
config('app.name');         // Konfiguration abrufen
env('DB_HOST');            // Umgebungsvariable
old('field_name');         // Vorheriger Formular-Wert
error('field_name');       // Validierungsfehler
```

## 📦 Deployment

### Produktion vorbereiten

1. **Assets kompilieren:**
   ```bash
   npm run build
   ```

2. **Composer optimieren:**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Environment:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

4. **Berechtigungen:**
   ```bash
   chmod -R 755 storage
   chmod -R 755 public/uploads
   ```

5. **Apache-Konfiguration:**
   - DocumentRoot auf Hauptverzeichnis setzen (nicht /public!)
   - `mod_rewrite` aktivieren
   - `.htaccess` ermöglichen

## 🤝 Contributing

1. Fork das Projekt
2. Feature-Branch erstellen (`git checkout -b feature/AmazingFeature`)
3. Änderungen committen (`git commit -m 'Add some AmazingFeature'`)
4. Branch pushen (`git push origin feature/AmazingFeature`)
5. Pull Request öffnen

## 📄 Lizenz

Proprietary - Alle Rechte vorbehalten

## 👤 Kontakt

GetYourBand - info@getyourband.ch

## 🙏 Credits

- **Tailwind CSS** - https://tailwindcss.com
- **Alpine.js** - https://alpinejs.dev
- **Vite** - https://vitejs.dev
- **PHP** - https://php.net

---

Made with ❤️ and 🎸 in Switzerland
