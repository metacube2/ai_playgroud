#!/usr/bin/env python3
"""
Bitcoin Trading Signal System
Hauptprogramm für Bitcoin Trading-Empfehlungen basierend auf MACD und News-Sentiment
"""

import sys
import argparse
from datetime import datetime
from typing import Optional

from data_fetcher import BitcoinDataFetcher
from signal_generator import SignalGenerator


class BitcoinTrader:
    """
    Haupt-Klasse für das Bitcoin Trading Signal System
    """

    def __init__(self, newsapi_key: Optional[str] = None, verbose: bool = False):
        """
        Args:
            newsapi_key: Optional NewsAPI-Schlüssel
            verbose: Ausführliche Ausgabe
        """
        self.data_fetcher = BitcoinDataFetcher()
        self.signal_generator = SignalGenerator(newsapi_key=newsapi_key)
        self.verbose = verbose

    def run_analysis(self, days: int = 30) -> None:
        """
        Führt komplette Trading-Analyse durch

        Args:
            days: Anzahl Tage für historische Daten
        """
        print("╔══════════════════════════════════════════════════════════════════╗")
        print("║         BITCOIN TRADING SIGNAL SYSTEM v1.0                      ║")
        print("╚══════════════════════════════════════════════════════════════════╝")
        print()

        # 1. Lade aktuelle Preisdaten
        if self.verbose:
            print("📊 Lade aktuelle Bitcoin-Preisdaten...")

        current_price = self.data_fetcher.get_current_price()

        if not current_price:
            print("❌ Fehler: Konnte aktuellen Bitcoin-Preis nicht abrufen!")
            sys.exit(1)

        if self.verbose:
            print(f"✓ Aktueller BTC-Preis: ${current_price:,.2f}")

        # 2. Lade historische Daten
        if self.verbose:
            print(f"📈 Lade historische Daten ({days} Tage)...")

        price_df = self.data_fetcher.get_historical_data(days=days)

        if price_df.empty:
            print("❌ Fehler: Konnte historische Daten nicht abrufen!")
            sys.exit(1)

        if self.verbose:
            print(f"✓ {len(price_df)} Datenpunkte geladen")

        # 3. Lade Marktdaten (optional)
        if self.verbose:
            print("💹 Lade erweiterte Marktdaten...")

        market_data = self.data_fetcher.get_market_data()

        if market_data and self.verbose:
            print(f"✓ Marktdaten geladen")
            if market_data.get('price_change_24h'):
                change_24h = market_data['price_change_24h']
                emoji = "📈" if change_24h > 0 else "📉"
                print(f"  {emoji} 24h Veränderung: {change_24h:+.2f}%")

        # 4. Generiere Trading-Signal
        if self.verbose:
            print("\n🔍 Analysiere MACD-Indikatoren...")
            print("📰 Analysiere News-Sentiment...")
            print("🎯 Generiere Trading-Signal...\n")

        signal = self.signal_generator.generate_signal(price_df, current_price)

        # 5. Zeige Empfehlung
        recommendation = self.signal_generator.get_recommendation_text(signal)
        print(recommendation)

        # 6. Zusätzliche Marktinformationen
        if market_data and self.verbose:
            print("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━")
            print("📊 ZUSÄTZLICHE MARKTINFORMATIONEN")
            print("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n")

            if market_data.get('market_cap'):
                print(f"  Marktkapitalisierung: ${market_data['market_cap']:,.0f}")

            if market_data.get('total_volume'):
                print(f"  24h Handelsvolumen: ${market_data['total_volume']:,.0f}")

            if market_data.get('high_24h') and market_data.get('low_24h'):
                print(f"  24h Hoch: ${market_data['high_24h']:,.2f}")
                print(f"  24h Tief: ${market_data['low_24h']:,.2f}")

            if market_data.get('price_change_7d'):
                print(f"  7-Tage Veränderung: {market_data['price_change_7d']:+.2f}%")

            if market_data.get('price_change_30d'):
                print(f"  30-Tage Veränderung: {market_data['price_change_30d']:+.2f}%")

            print()

    def get_quick_signal(self) -> str:
        """
        Gibt schnelles Trading-Signal zurück (nur Empfehlung)

        Returns:
            Signal-String
        """
        current_price = self.data_fetcher.get_current_price()
        if not current_price:
            return "❌ Fehler beim Abrufen der Daten"

        price_df = self.data_fetcher.get_historical_data(days=30)
        if price_df.empty:
            return "❌ Fehler beim Abrufen der Daten"

        signal = self.signal_generator.generate_signal(price_df, current_price)

        return f"{signal.action.value} (Konfidenz: {signal.confidence}%) @ ${signal.price:,.2f}"


def main():
    """Hauptfunktion"""
    parser = argparse.ArgumentParser(
        description='Bitcoin Trading Signal System - MACD + News Sentiment Analyse',
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Beispiele:
  %(prog)s                          # Standard-Analyse
  %(prog)s --verbose                # Ausführliche Ausgabe
  %(prog)s --days 60                # 60 Tage historische Daten
  %(prog)s --quick                  # Schnelles Signal
  %(prog)s --newsapi-key YOUR_KEY   # Mit NewsAPI-Schlüssel

Hinweis:
  - NewsAPI-Schlüssel optional (erhöht News-Quellen)
  - Kostenlos bei https://newsapi.org
        """
    )

    parser.add_argument(
        '-v', '--verbose',
        action='store_true',
        help='Ausführliche Ausgabe mit Ladestatus'
    )

    parser.add_argument(
        '-d', '--days',
        type=int,
        default=30,
        help='Anzahl Tage für historische Daten (Standard: 30)'
    )

    parser.add_argument(
        '-q', '--quick',
        action='store_true',
        help='Schnelles Signal ohne Details'
    )

    parser.add_argument(
        '--newsapi-key',
        type=str,
        default=None,
        help='NewsAPI-Schlüssel für erweiterte News-Analyse'
    )

    args = parser.parse_args()

    # Initialisiere Trader
    trader = BitcoinTrader(
        newsapi_key=args.newsapi_key,
        verbose=args.verbose
    )

    try:
        if args.quick:
            # Schnelles Signal
            signal = trader.get_quick_signal()
            print(signal)
        else:
            # Vollständige Analyse
            trader.run_analysis(days=args.days)

    except KeyboardInterrupt:
        print("\n\n⚠️ Analyse abgebrochen durch Benutzer")
        sys.exit(0)
    except Exception as e:
        print(f"\n❌ Fehler: {e}")
        if args.verbose:
            import traceback
            traceback.print_exc()
        sys.exit(1)


if __name__ == "__main__":
    main()
