# Neue Features: PayPal-Integration & Bild-Upload

Dieses Dokument beschreibt die neu hinzugefügten Features für die GetYourBand-Plattform.

## 🖼️ Bild-Upload für Bands

### Features
- **Upload-Funktionalität**: Bands können eigene Bilder hochladen
- **Galerie-Verwaltung**: Anzeige und Verwaltung aller hochgeladenen Bilder
- **Löschen**: Bilder können jederzeit gelöscht werden
- **Validierung**:
  - Erlaubte Formate: JPG, PNG, GIF, WEBP
  - Maximale Dateigröße: 5MB
  - Automatische Dateinamens-Generierung

### Technische Details
- **Upload-Verzeichnis**: `/storage/uploads/bands/`
- **Handler**: `upload-handler.php`
- **Frontend**: AJAX-basierter Upload mit Fetch API
- **Dateinamensschema**: `band_{band_id}_{unique_id}.{extension}`

### Verwendung
1. Als Band-User einloggen
2. Zum Profil navigieren (`profil.php`)
3. Sektion "Band-Galerie" finden
4. Auf "+ Bild hochladen" klicken
5. Bild auswählen (wird automatisch hochgeladen)

### Sicherheit
- Nur authentifizierte Band-User können uploaden
- Strenge Dateitypprüfung (MIME-Type + Extension)
- Größenlimit verhindert DoS
- Sichere Dateinamen ohne User-Input

---

## 💳 PayPal-Integration

### Features
- **Zahlungsabwicklung**: Kunden können Buchungen direkt mit PayPal bezahlen
- **Service Fee**: Konfigurierbare Servicegebühr (in Admin-Settings)
- **Zahlungs-Tracking**: Alle Zahlungen werden in der Datenbank gespeichert
- **Status-Updates**: Anfragen werden automatisch auf "bestätigt" gesetzt
- **Email-Benachrichtigungen**: Kunde und Band erhalten Bestätigungen

### Komponenten

#### 1. Datenbank
Neue Tabelle `payments`:
```sql
CREATE TABLE payments (
    id INTEGER PRIMARY KEY,
    request_id INTEGER NOT NULL,
    amount REAL NOT NULL,
    service_fee REAL NOT NULL,
    total_amount REAL NOT NULL,
    paypal_order_id TEXT,
    paypal_payer_id TEXT,
    status TEXT DEFAULT 'pending',
    created_at TEXT,
    completed_at TEXT
);
```

#### 2. Checkout-Seite
**Datei**: `paypal-checkout.php`
- Zeigt Buchungsdetails und Zahlungsübersicht
- Integriert PayPal JavaScript SDK
- Berechnet Gesamtbetrag (Band-Gage + Service Fee)

#### 3. Payment Processing
**Datei**: `paypal-process.php`
- Speichert erfolgreiche Zahlungen
- Aktualisiert Request-Status
- Sendet Bestätigungs-Emails

#### 4. Integration in Buchungsflow
**Änderungen in `anfrage.php`**:
- Nach erfolgreicher Anfrage wird PayPal-Button angezeigt (wenn aktiviert)
- Direkter Link zum Checkout

**Änderungen in `profil.php`**:
- Zahlungsstatus für jede Anfrage angezeigt
- "Jetzt bezahlen"-Button für ausstehende Zahlungen

### PayPal-Konfiguration

#### Admin-Einstellungen
Im Admin-Panel (`admin/settings.php`):
- `paypal_enabled`: 0/1 (aktiviert/deaktiviert)
- `service_fee`: Prozentsatz (z.B. 8 für 8%)

#### PayPal API Credentials
In `paypal-checkout.php` Zeile 80:
```javascript
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&currency=CHF"></script>
```

**Wichtig**: `YOUR_PAYPAL_CLIENT_ID` durch echte Client-ID ersetzen!

#### PayPal Developer Setup
1. Gehen Sie zu https://developer.paypal.com
2. Erstellen Sie eine App in "My Apps & Credentials"
3. Kopieren Sie die Client-ID
4. Für Produktion: Aktivieren Sie Live-Modus und verwenden Sie Live-Credentials

### Zahlungsablauf

1. **Kunde erstellt Anfrage** → Request wird in DB gespeichert
2. **PayPal-Link erscheint** → Kunde klickt auf "Mit PayPal bezahlen"
3. **Checkout-Seite** → Übersicht und PayPal-Button
4. **PayPal-Zahlung** → Kunde loggt sich in PayPal ein und zahlt
5. **Payment Processing** → Zahlung wird in DB gespeichert
6. **Status-Update** → Request → "bestätigt", Emails versandt
7. **Rückkehr zum Profil** → Erfolgsmeldung

### Testmodus

Die aktuelle Implementation läuft im **Sandbox-Modus**:
- Verwenden Sie PayPal Sandbox-Accounts zum Testen
- Keine echten Transaktionen werden durchgeführt
- Für Produktion: Client-ID auf Live-Credentials umstellen

### Sicherheit
- Zahlung nur für eigene Requests möglich
- Doppelzahlungen werden verhindert
- Transaktions-IDs werden gespeichert
- Server-seitige Validierung aller Zahlungsdaten

---

## 📂 Neue Dateien

| Datei | Beschreibung |
|-------|--------------|
| `upload-handler.php` | REST-API für Bild-Uploads (POST/DELETE) |
| `paypal-checkout.php` | PayPal Checkout-Seite |
| `paypal-process.php` | PayPal Payment Processing Backend |
| `storage/uploads/bands/` | Upload-Verzeichnis für Band-Bilder |
| `PAYPAL_UPLOAD_FEATURES.md` | Diese Dokumentation |

## 🔄 Geänderte Dateien

| Datei | Änderungen |
|-------|------------|
| `database.sql` | + `payments` Tabelle |
| `profil.php` | + Galerie-Sektion, + Zahlungsstatus in Anfragen |
| `anfrage.php` | + PayPal-Button nach erfolgreicher Anfrage |

## 🚀 Deployment-Checklist

- [ ] `storage/uploads/` Verzeichnis erstellen mit Schreibrechten
- [ ] PayPal Developer Account erstellen
- [ ] Client-ID in `paypal-checkout.php` eintragen
- [ ] Admin-Panel: PayPal aktivieren und Service Fee setzen
- [ ] Für Produktion: Auf Live-Credentials umstellen
- [ ] SSL-Zertifikat für HTTPS (PayPal requirement)

## 🐛 Bekannte Einschränkungen

1. **PayPal Client-ID**: Muss manuell konfiguriert werden
2. **Keine Rückerstattungen**: Keine Admin-UI für Refunds
3. **Email-System**: Aktuell nur Logging, kein echtes SMTP
4. **Sandbox-Modus**: Standardmäßig aktiviert

## 📝 Nächste Schritte (Optional)

- Webhook-Integration für PayPal IPN (Instant Payment Notification)
- Admin-Dashboard für Zahlungsübersicht
- Automatische Rechnungserstellung (PDF)
- Stripe als alternative Zahlungsmethode
- Bulk-Upload für mehrere Bilder
- Bildkompression/Optimierung
- Thumbnail-Generierung

---

**Entwickelt für**: GetYourBand Platform
**Datum**: 2025-12-02
**Version**: 1.0
