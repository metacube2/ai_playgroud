"""
MACD (Moving Average Convergence Divergence) Indicator
Berechnet MACD-Signale für Bitcoin Trading
"""

import pandas as pd
import numpy as np
from typing import Dict, Tuple, Optional
from enum import Enum


class MACDSignal(Enum):
    """MACD Signal-Typen"""
    STRONG_BUY = "STARKER KAUF"
    BUY = "KAUF"
    NEUTRAL = "NEUTRAL"
    SELL = "VERKAUF"
    STRONG_SELL = "STARKER VERKAUF"


class MACDIndicator:
    """
    MACD-Indikator für technische Analyse

    Standard-Parameter:
    - Fast EMA: 12 Perioden
    - Slow EMA: 26 Perioden
    - Signal: 9 Perioden
    """

    def __init__(self, fast_period: int = 12, slow_period: int = 26, signal_period: int = 9):
        """
        Args:
            fast_period: Schnelle EMA-Periode (Standard: 12)
            slow_period: Langsame EMA-Periode (Standard: 26)
            signal_period: Signal-Linie-Periode (Standard: 9)
        """
        self.fast_period = fast_period
        self.slow_period = slow_period
        self.signal_period = signal_period

    def calculate_ema(self, data: pd.Series, period: int) -> pd.Series:
        """
        Berechnet Exponential Moving Average (EMA)

        Args:
            data: Preisdaten
            period: EMA-Periode

        Returns:
            EMA-Serie
        """
        return data.ewm(span=period, adjust=False).mean()

    def calculate_macd(self, prices: pd.Series) -> pd.DataFrame:
        """
        Berechnet MACD-Indikator

        Args:
            prices: Preis-Serie (normalerweise Close-Preise)

        Returns:
            DataFrame mit MACD, Signal und Histogram
        """
        # Berechne EMAs
        ema_fast = self.calculate_ema(prices, self.fast_period)
        ema_slow = self.calculate_ema(prices, self.slow_period)

        # MACD-Linie
        macd_line = ema_fast - ema_slow

        # Signal-Linie (9-Tage EMA der MACD-Linie)
        signal_line = self.calculate_ema(macd_line, self.signal_period)

        # MACD-Histogramm (Differenz zwischen MACD und Signal)
        histogram = macd_line - signal_line

        # Erstelle DataFrame
        df = pd.DataFrame({
            'macd': macd_line,
            'signal': signal_line,
            'histogram': histogram
        })

        return df

    def get_signal(self, df: pd.DataFrame) -> Tuple[MACDSignal, Dict]:
        """
        Generiert Trading-Signal basierend auf MACD

        Args:
            df: DataFrame mit price-Spalte

        Returns:
            Tuple aus (Signal, Details-Dict)
        """
        if df.empty or len(df) < self.slow_period + self.signal_period:
            return MACDSignal.NEUTRAL, {
                'reason': 'Nicht genug Daten für MACD-Berechnung',
                'confidence': 0
            }

        # Berechne MACD
        macd_df = self.calculate_macd(df['price'])

        # Hole aktuelle und vorherige Werte
        current_macd = macd_df['macd'].iloc[-1]
        current_signal = macd_df['signal'].iloc[-1]
        current_histogram = macd_df['histogram'].iloc[-1]

        prev_macd = macd_df['macd'].iloc[-2]
        prev_signal = macd_df['signal'].iloc[-2]
        prev_histogram = macd_df['histogram'].iloc[-2]

        # Bestimme Signal-Typ
        signal = MACDSignal.NEUTRAL
        confidence = 0
        reasons = []

        # 1. Bullish Crossover (MACD kreuzt Signal von unten)
        if prev_macd <= prev_signal and current_macd > current_signal:
            signal = MACDSignal.BUY
            confidence = 70
            reasons.append("Bullish Crossover: MACD kreuzt Signal-Linie von unten")

            # Starkes Kaufsignal, wenn zusätzlich im negativen Bereich
            if current_macd < 0:
                signal = MACDSignal.STRONG_BUY
                confidence = 85
                reasons.append("MACD im negativen Bereich → Überkauft")

        # 2. Bearish Crossover (MACD kreuzt Signal von oben)
        elif prev_macd >= prev_signal and current_macd < current_signal:
            signal = MACDSignal.SELL
            confidence = 70
            reasons.append("Bearish Crossover: MACD kreuzt Signal-Linie von oben")

            # Starkes Verkaufssignal, wenn zusätzlich im positiven Bereich
            if current_macd > 0:
                signal = MACDSignal.STRONG_SELL
                confidence = 85
                reasons.append("MACD im positiven Bereich → Überverkauft")

        # 3. Divergenz-Analyse (Histogramm)
        else:
            histogram_trend = current_histogram - prev_histogram

            if histogram_trend > 0 and current_histogram > 0:
                signal = MACDSignal.BUY
                confidence = 55
                reasons.append("Positives Momentum: Histogramm steigt")

            elif histogram_trend < 0 and current_histogram < 0:
                signal = MACDSignal.SELL
                confidence = 55
                reasons.append("Negatives Momentum: Histogramm fällt")

            else:
                signal = MACDSignal.NEUTRAL
                confidence = 30
                reasons.append("Kein klarer Trend erkennbar")

        # Zusätzliche Analyse: Preis-Trend
        recent_prices = df['price'].tail(10)
        price_change = ((recent_prices.iloc[-1] - recent_prices.iloc[0]) / recent_prices.iloc[0]) * 100

        if abs(price_change) > 5:
            if price_change > 0:
                reasons.append(f"Preis-Trend: +{price_change:.2f}% (aufwärts)")
            else:
                reasons.append(f"Preis-Trend: {price_change:.2f}% (abwärts)")

        details = {
            'macd': float(current_macd),
            'signal': float(current_signal),
            'histogram': float(current_histogram),
            'prev_histogram': float(prev_histogram),
            'crossover': current_macd > current_signal,
            'confidence': confidence,
            'reasons': reasons,
            'price_change_10d': float(price_change)
        }

        return signal, details

    def analyze_trend(self, df: pd.DataFrame, periods: int = 20) -> str:
        """
        Analysiert den Trend der letzten Perioden

        Args:
            df: DataFrame mit MACD-Daten
            periods: Anzahl Perioden zur Analyse

        Returns:
            Trend-Beschreibung
        """
        macd_df = self.calculate_macd(df['price'])

        if len(macd_df) < periods:
            return "Nicht genug Daten"

        recent_histogram = macd_df['histogram'].tail(periods)

        # Zähle positive/negative Balken
        positive_bars = (recent_histogram > 0).sum()
        negative_bars = (recent_histogram < 0).sum()

        if positive_bars > negative_bars * 1.5:
            return "Starker Aufwärtstrend"
        elif positive_bars > negative_bars:
            return "Leichter Aufwärtstrend"
        elif negative_bars > positive_bars * 1.5:
            return "Starker Abwärtstrend"
        elif negative_bars > positive_bars:
            return "Leichter Abwärtstrend"
        else:
            return "Seitwärtstrend"


if __name__ == "__main__":
    # Test mit simulierten Daten
    print("=== MACD Indicator Test ===\n")

    # Erstelle Test-Daten
    dates = pd.date_range(start='2024-01-01', periods=100, freq='D')
    prices = 40000 + np.cumsum(np.random.randn(100) * 500)  # Random Walk

    df = pd.DataFrame({
        'timestamp': dates,
        'price': prices
    })

    # Initialisiere MACD
    macd = MACDIndicator()

    # Berechne MACD
    macd_df = macd.calculate_macd(df['price'])

    print("Letzte 5 MACD-Werte:")
    print(macd_df.tail())

    print("\n=== Trading Signal ===")
    signal, details = macd.get_signal(df)

    print(f"Signal: {signal.value}")
    print(f"Konfidenz: {details['confidence']}%")
    print(f"\nMACD: {details['macd']:.2f}")
    print(f"Signal: {details['signal']:.2f}")
    print(f"Histogram: {details['histogram']:.2f}")
    print(f"\nGründe:")
    for reason in details['reasons']:
        print(f"  - {reason}")

    print(f"\nTrend-Analyse: {macd.analyze_trend(df)}")
