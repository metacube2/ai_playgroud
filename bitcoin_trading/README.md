# 🪙 Bitcoin Trading Signal System

Ein intelligentes Trading-Signal-System für Bitcoin, das **technische Analyse (MACD)** mit **News-Sentiment-Analyse** kombiniert, um fundierte Kauf- und Verkaufsempfehlungen zu generieren.

## 📋 Features

### 🔍 Technische Analyse
- **MACD-Indikator** (Moving Average Convergence Divergence)
- Erkennung von Bullish/Bearish Crossovers
- Histogramm-Analyse für Momentum-Erkennung
- Trend-Analyse über multiple Zeitperioden

### 📰 Sentiment-Analyse
- Echtzeit-Analyse von Bitcoin-News
- Keyword-basierte Sentiment-Bewertung
- Multiple News-Quellen (CryptoCompare, optional NewsAPI)
- Aggregierung von positiven/negativen Marktsignalen

### 🎯 Kombinierte Signale
- Gewichtete Kombination aus MACD + Sentiment
- 5 Signal-Stufen: Starker Kauf, Kauf, Halten, Verkauf, Starker Verkauf
- Konfidenz-Bewertung für jedes Signal
- Detaillierte Begründungen für Empfehlungen

## 🚀 Installation

### Voraussetzungen
- Python 3.8 oder höher
- pip (Python Package Manager)

### 1. Dependencies installieren

```bash
cd bitcoin_trading
pip install -r requirements.txt
```

### 2. Optional: NewsAPI-Schlüssel

Für erweiterte News-Analyse kannst du einen kostenlosen NewsAPI-Schlüssel erhalten:

1. Registriere dich auf [NewsAPI.org](https://newsapi.org)
2. Hole dir deinen API-Schlüssel
3. Verwende ihn mit `--newsapi-key` Parameter

## 💻 Verwendung

### Basis-Analyse (empfohlen)

```bash
python bitcoin_trader.py
```

Dies führt eine vollständige Analyse durch mit:
- Aktuellen Bitcoin-Preisdaten
- 30 Tage historische MACD-Daten
- Aktuelle News-Sentiment-Analyse
- Kombinierter Trading-Empfehlung

### Erweiterte Optionen

**Ausführliche Ausgabe mit Ladestatus:**
```bash
python bitcoin_trader.py --verbose
```

**Mehr historische Daten (z.B. 60 Tage):**
```bash
python bitcoin_trader.py --days 60
```

**Schnelles Signal (nur Empfehlung):**
```bash
python bitcoin_trader.py --quick
```

**Mit NewsAPI-Schlüssel:**
```bash
python bitcoin_trader.py --newsapi-key YOUR_API_KEY
```

**Alle Optionen kombiniert:**
```bash
python bitcoin_trader.py --verbose --days 90 --newsapi-key YOUR_KEY
```

### Als Python-Modul verwenden

```python
from bitcoin_trading import BitcoinTrader

# Initialisiere Trader
trader = BitcoinTrader(verbose=True)

# Führe Analyse durch
trader.run_analysis(days=30)

# Oder hole schnelles Signal
signal = trader.get_quick_signal()
print(signal)
```

## 📊 Output-Beispiel

```
╔══════════════════════════════════════════════════════════════════╗
║           BITCOIN TRADING SIGNAL - 2024-12-02 15:30             ║
╚══════════════════════════════════════════════════════════════════╝

📊 EMPFEHLUNG: 🟢 KAUF
💯 KONFIDENZ: 72%
💰 AKTUELLER PREIS: $42,583.50

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📈 TECHNISCHE ANALYSE (MACD)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Signal: KAUF
MACD: 125.34
Signal Line: 98.21
Histogram: 27.13
Preis-Änderung (10 Tage): +5.67%

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📰 SENTIMENT-ANALYSE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Markt-Sentiment: POSITIV
Sentiment-Score: 0.425
Analysierte Artikel: 28
  ├─ Positiv: 16
  ├─ Neutral: 8
  └─ Negativ: 4

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📋 BEGRÜNDUNG
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  1. MACD-Signal: KAUF (Konfidenz: 70%)
  2. Bullish Crossover: MACD kreuzt Signal-Linie von unten
  3. Positives Momentum: Histogramm steigt
  4. Markt-Sentiment: POSITIV (Konfidenz: 75%, Score: 0.425)
  5. ✅ MACD und Sentiment stimmen überein → Starkes Signal

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💡 HANDLUNGSEMPFEHLUNG
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  🟢 KAUFGELEGENHEIT
  → Erwäge einen Einstieg mit kleiner Position
  → Warte ggf. auf Bestätigung durch weitere Signale

  ⚠️ Risiko-Hinweis: Diese Analyse hat eine Konfidenz von 72%
  ⚠️ Keine Anlageberatung - Trading auf eigenes Risiko!

╚══════════════════════════════════════════════════════════════════╝
```

## 🔧 Technische Details

### MACD-Parameter
- **Fast EMA**: 12 Perioden
- **Slow EMA**: 26 Perioden
- **Signal Line**: 9 Perioden

### Signal-Logik
- **Starker Kauf**: Bullish Crossover im negativen Bereich
- **Kauf**: Bullish Crossover oder positives Momentum
- **Halten**: Keine klare Richtung
- **Verkauf**: Bearish Crossover oder negatives Momentum
- **Starker Verkauf**: Bearish Crossover im positiven Bereich

### Gewichtung
- MACD (Technische Analyse): **60%**
- News-Sentiment: **40%**
- Bonus bei übereinstimmenden Signalen: **+10%**

## 📁 Projektstruktur

```
bitcoin_trading/
├── __init__.py              # Package Initialisierung
├── bitcoin_trader.py        # Hauptprogramm (CLI)
├── data_fetcher.py          # Bitcoin-Preisdaten-Abruf
├── macd_indicator.py        # MACD-Indikator-Berechnung
├── news_sentiment.py        # News-Sentiment-Analyse
├── signal_generator.py      # Signal-Kombination & Empfehlung
├── requirements.txt         # Python-Dependencies
└── README.md               # Diese Datei
```

## 🔌 API-Quellen

### Preisdaten
- **Binance API** (primär) - Schnelle, zuverlässige Preisdaten
- **CoinGecko API** (fallback) - Backup-Datenquelle

### News
- **CryptoCompare News API** (kostenlos) - Crypto-spezifische News
- **NewsAPI** (optional) - Erweiterte News-Abdeckung

## ⚠️ Wichtige Hinweise

### Disclaimer
- **Dies ist KEINE Anlageberatung**
- Trading mit Kryptowährungen ist hochriskant
- Vergangene Performance garantiert keine zukünftigen Ergebnisse
- Investiere nur Geld, das du dir leisten kannst zu verlieren
- Führe deine eigene Due Diligence durch

### Risiken
- Marktvolatilität kann Signale schnell ungültig machen
- Technische Indikatoren sind nicht 100% zuverlässig
- News-Sentiment kann manipuliert sein
- API-Ausfälle können Daten beeinträchtigen

### Best Practices
- Verwende Signale als einen von mehreren Faktoren
- Setze immer Stop-Loss-Orders
- Diversifiziere dein Portfolio
- Handel nur mit klarem Kopf
- Dokumentiere deine Trades

## 🐛 Troubleshooting

### Fehler: "Konnte Bitcoin-Preis nicht abrufen"
- Überprüfe Internetverbindung
- APIs könnten temporär down sein
- Warte kurz und versuche es erneut

### Fehler: "Nicht genug Daten für MACD"
- Erhöhe `--days` Parameter (mindestens 30 Tage empfohlen)
- Stelle sicher, dass historische Daten geladen werden

### Sentiment zeigt immer "NEUTRAL"
- Möglicherweise keine aktuellen News verfügbar
- Verwende `--newsapi-key` für mehr News-Quellen
- News-APIs könnten Rate-Limits haben

## 🔄 Updates & Erweiterungen

### Geplante Features
- [ ] RSI (Relative Strength Index) Integration
- [ ] Bollinger Bands Analyse
- [ ] Machine Learning Modelle
- [ ] Email/Telegram Benachrichtigungen
- [ ] Backtesting-Funktionalität
- [ ] WebSocket Real-time Updates
- [ ] Multi-Coin Support (ETH, etc.)

### Erweiterungsmöglichkeiten
- Integration weiterer technischer Indikatoren
- Social Media Sentiment (Twitter/Reddit)
- On-Chain-Metriken (Wallet-Bewegungen)
- Advanced ML/AI Modelle
- Portfolio-Management-Features

## 📝 Lizenz

Dieses Projekt ist Teil des GetYourBand-Projekts.

## 🤝 Beitragen

Contributions sind willkommen! Bitte öffne ein Issue oder Pull Request.

## 📧 Support

Bei Fragen oder Problemen erstelle ein Issue im Repository.

---

**Made with 📊 and ₿ for informed trading decisions**

⚠️ **Remember: Don't invest more than you can afford to lose!**
