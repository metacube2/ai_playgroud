# Email & Buchungssystem Updates

## Änderungen vom 2. Dezember 2025

### ✅ Implementierte Features:

#### 1. **Echte Email-Funktionalität** (`includes/email.php`)
   - ✨ PHP `mail()` Funktion implementiert statt nur Logging
   - ✅ HTML-Email-Support mit professionellen Templates
   - 📧 Automatische Headers (From, Reply-To, Content-Type)
   - 📝 Logging bleibt erhalten für Debugging

#### 2. **Email-Template-System**
   - 🎨 Professionelle HTML-Email-Templates mit Styling
   - 🎸 "booking_request" - Email an die Band
   - ✅ "booking_confirmation" - Bestätigung an den Kunden
   - 🎨 Gelbes Branding (#f4b807) passend zur Plattform

#### 3. **Verbesserte Buchungsanfragen** (`anfrage.php`)
   - 📧 Automatische Email an Band bei neuer Anfrage
   - ✅ Bestätigungs-Email an Kunden
   - 👥 **Gäste-Buchungen** ohne Login möglich
   - ✔️ Bessere Formular-Validierung
   - 📅 Datum-Mindestauswahl (nur zukünftige Daten)

#### 4. **Band-Email-Verwaltung** (`profil.php`)
   - 📧 Bands können eigene Email-Adresse hinterlegen
   - 📝 Klare Beschriftung: "Email für Buchungsanfragen"
   - 💾 Email wird in der Datenbank gespeichert

#### 5. **Datenbank-Updates** (`database.sql`)
   - 🗄️ Neue Spalte `email` in `bands` Tabelle
   - 📜 Migration-Script: `migrate_add_band_email.php`

---

## 📋 Installations-Anleitung

### Schritt 1: Migration ausführen
```bash
php migrate_add_band_email.php
```

### Schritt 2: Mail-Server konfigurieren
Stelle sicher, dass PHP's `mail()` Funktion auf dem Server konfiguriert ist:
- Ubuntu/Debian: `sudo apt-get install sendmail`
- Oder verwende einen SMTP-Relay wie Postfix

### Schritt 3: Testen
1. Als Band einloggen
2. Profil bearbeiten und Email-Adresse hinzufügen
3. Als Gast oder Kunde eine Buchungsanfrage senden
4. Prüfe die Emails (und `storage/logs/mail.log`)

---

## 🎯 Neue Funktionen im Detail

### Email an Band (booking_request)
```
Enthält:
- Event-Datum, Ort, Typ
- Budget
- Nachricht des Kunden
- Kontaktdaten (Name, Email)
- Professionelles Layout
```

### Email an Kunde (booking_confirmation)
```
Enthält:
- Bestätigung der Anfrage
- Event-Details
- Hinweis auf Rückmeldung der Band
- Support-Kontakt
```

### Gäste-Buchungen
```
- Keine Registrierung nötig
- Name + Email Pflichtfelder
- Email-Validierung
- Gleiche Funktionalität wie eingeloggte User
```

---

## 🔧 Konfiguration

### Email-Absender
In `includes/config.php`:
```php
const SITE_NAME = 'GetYourBand';
const SUPPORT_EMAIL = 'support@getyourband.ch';
```

### Band-Email Fallback
Falls Band keine Email hinterlegt hat:
```php
info@[bandname].ch
```
(Leerzeichen werden entfernt, lowercase)

---

## 📝 Nächste Schritte (Optional)

### Empfohlene Erweiterungen:
1. **PHPMailer Integration** für SMTP-Support
2. **Email-Queue** für große Mengen
3. **Email-Templates per Datenbank** konfigurierbar
4. **Email-Benachrichtigungen** für:
   - Status-Änderungen von Anfragen
   - Neue Bewertungen
   - Profil-Freigaben

### SMTP mit PHPMailer (Beispiel):
```bash
composer require phpmailer/phpmailer
```

Dann in `includes/email.php` ersetzen:
```php
use PHPMailer\PHPMailer\PHPMailer;
// ... SMTP Konfiguration
```

---

## 🐛 Debugging

### Email kommt nicht an?
1. Prüfe `storage/logs/mail.log` - werden Emails geloggt?
2. Prüfe Server Mail-Logs: `tail -f /var/log/mail.log`
3. Teste PHP mail(): `php -r "mail('test@example.com', 'Test', 'Test');"`
4. Prüfe Spam-Ordner

### Häufige Probleme:
- **sendmail nicht installiert**: `sudo apt-get install sendmail`
- **Port 25 blockiert**: Verwende SMTP-Relay
- **SPF/DKIM fehlt**: Emails landen im Spam

---

## ✨ Zusammenfassung

**Vorher:**
- ❌ Emails wurden nur geloggt
- ❌ Keine echten Email-Benachrichtigungen
- ❌ Gäste konnten nicht buchen
- ❌ Bands hatten keine Email-Verwaltung

**Nachher:**
- ✅ Echte Email-Versand mit HTML-Templates
- ✅ Automatische Benachrichtigungen an Band & Kunde
- ✅ Gäste-Buchungen möglich
- ✅ Bands verwalten ihre Email-Adresse
- ✅ Professionelles Design
- ✅ Bessere Validierung

---

**Viel Erfolg! 🎸🎵**
