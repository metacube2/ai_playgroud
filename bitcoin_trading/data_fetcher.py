"""
Bitcoin Price Data Fetcher
Ruft aktuelle und historische Bitcoin-Preisdaten von verschiedenen APIs ab
"""

import requests
from datetime import datetime, timedelta
import pandas as pd
from typing import Dict, List, Optional
import time


class BitcoinDataFetcher:
    """Fetches Bitcoin price data from various sources"""

    def __init__(self):
        self.base_url_coingecko = "https://api.coingecko.com/api/v3"
        self.base_url_binance = "https://api.binance.com/api/v3"

    def get_current_price(self) -> Optional[float]:
        """
        Holt den aktuellen Bitcoin-Preis in USD

        Returns:
            float: Aktueller BTC/USD Preis oder None bei Fehler
        """
        try:
            # Versuche zuerst Binance (schneller und zuverlässiger)
            url = f"{self.base_url_binance}/ticker/price"
            params = {"symbol": "BTCUSDT"}
            response = requests.get(url, params=params, timeout=10)

            if response.status_code == 200:
                data = response.json()
                return float(data['price'])

        except Exception as e:
            print(f"Fehler beim Abrufen von Binance: {e}")

        try:
            # Fallback zu CoinGecko
            url = f"{self.base_url_coingecko}/simple/price"
            params = {
                "ids": "bitcoin",
                "vs_currencies": "usd"
            }
            response = requests.get(url, params=params, timeout=10)

            if response.status_code == 200:
                data = response.json()
                return float(data['bitcoin']['usd'])

        except Exception as e:
            print(f"Fehler beim Abrufen von CoinGecko: {e}")

        return None

    def get_historical_data(self, days: int = 30) -> pd.DataFrame:
        """
        Holt historische Bitcoin-Preisdaten

        Args:
            days: Anzahl der Tage zurück

        Returns:
            DataFrame mit Spalten: timestamp, price, volume
        """
        try:
            # Binance Klines (Candlestick-Daten)
            url = f"{self.base_url_binance}/klines"

            # Berechne Zeitstempel
            end_time = int(datetime.now().timestamp() * 1000)
            start_time = int((datetime.now() - timedelta(days=days)).timestamp() * 1000)

            params = {
                "symbol": "BTCUSDT",
                "interval": "1h",  # Stündliche Daten
                "startTime": start_time,
                "endTime": end_time,
                "limit": 1000
            }

            response = requests.get(url, params=params, timeout=30)

            if response.status_code == 200:
                data = response.json()

                # Konvertiere zu DataFrame
                df = pd.DataFrame(data, columns=[
                    'timestamp', 'open', 'high', 'low', 'close',
                    'volume', 'close_time', 'quote_volume', 'trades',
                    'taker_buy_base', 'taker_buy_quote', 'ignore'
                ])

                # Behalte nur relevante Spalten
                df = df[['timestamp', 'close', 'volume']]
                df.columns = ['timestamp', 'price', 'volume']

                # Konvertiere Datentypen
                df['timestamp'] = pd.to_datetime(df['timestamp'], unit='ms')
                df['price'] = df['price'].astype(float)
                df['volume'] = df['volume'].astype(float)

                return df

        except Exception as e:
            print(f"Fehler beim Abrufen historischer Daten: {e}")

        # Fallback: Leeres DataFrame
        return pd.DataFrame(columns=['timestamp', 'price', 'volume'])

    def get_market_data(self) -> Dict:
        """
        Holt erweiterte Marktdaten für Bitcoin

        Returns:
            Dict mit Marktdaten (Volumen, Marktkapitalisierung, etc.)
        """
        try:
            url = f"{self.base_url_coingecko}/coins/bitcoin"
            params = {
                "localization": "false",
                "tickers": "false",
                "market_data": "true",
                "community_data": "false",
                "developer_data": "false"
            }

            response = requests.get(url, params=params, timeout=15)

            if response.status_code == 200:
                data = response.json()
                market_data = data.get('market_data', {})

                return {
                    'current_price': market_data.get('current_price', {}).get('usd'),
                    'market_cap': market_data.get('market_cap', {}).get('usd'),
                    'total_volume': market_data.get('total_volume', {}).get('usd'),
                    'price_change_24h': market_data.get('price_change_percentage_24h'),
                    'price_change_7d': market_data.get('price_change_percentage_7d'),
                    'price_change_30d': market_data.get('price_change_percentage_30d'),
                    'high_24h': market_data.get('high_24h', {}).get('usd'),
                    'low_24h': market_data.get('low_24h', {}).get('usd'),
                }

        except Exception as e:
            print(f"Fehler beim Abrufen von Marktdaten: {e}")

        return {}


if __name__ == "__main__":
    # Test
    fetcher = BitcoinDataFetcher()

    print("=== Bitcoin Preis ===")
    price = fetcher.get_current_price()
    if price:
        print(f"Aktueller BTC/USD Preis: ${price:,.2f}")

    print("\n=== Marktdaten ===")
    market = fetcher.get_market_data()
    for key, value in market.items():
        if value is not None:
            if 'price' in key or 'cap' in key or 'volume' in key or 'high' in key or 'low' in key:
                print(f"{key}: ${value:,.2f}")
            else:
                print(f"{key}: {value:.2f}%")

    print("\n=== Historische Daten (letzte 7 Tage) ===")
    df = fetcher.get_historical_data(days=7)
    if not df.empty:
        print(f"Anzahl Datenpunkte: {len(df)}")
        print(df.tail())
