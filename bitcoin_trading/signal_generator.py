"""
Trading Signal Generator
Kombiniert MACD-Indikatoren und News-Sentiment für Trading-Empfehlungen
"""

from typing import Dict, Tuple
from enum import Enum
from dataclasses import dataclass
from datetime import datetime

from macd_indicator import MACDIndicator, MACDSignal
from news_sentiment import NewsSentimentAnalyzer, SentimentScore


class TradingAction(Enum):
    """Trading-Empfehlungs-Typen"""
    STRONG_BUY = "🟢 STARKER KAUF"
    BUY = "🟢 KAUF"
    HOLD = "🟡 HALTEN"
    SELL = "🔴 VERKAUF"
    STRONG_SELL = "🔴 STARKER VERKAUF"


@dataclass
class TradingSignal:
    """Datenklasse für Trading-Signale"""
    action: TradingAction
    confidence: int  # 0-100
    price: float
    timestamp: datetime
    macd_signal: MACDSignal
    sentiment: SentimentScore
    reasons: list
    technical_details: dict
    sentiment_details: dict

    def __str__(self):
        return f"""
╔══════════════════════════════════════════════════════════════════╗
║           BITCOIN TRADING SIGNAL - {self.timestamp.strftime('%Y-%m-%d %H:%M')}          ║
╚══════════════════════════════════════════════════════════════════╝

📊 EMPFEHLUNG: {self.action.value}
💯 KONFIDENZ: {self.confidence}%
💰 AKTUELLER PREIS: ${self.price:,.2f}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📈 TECHNISCHE ANALYSE (MACD)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Signal: {self.macd_signal.value}
MACD: {self.technical_details.get('macd', 0):.2f}
Signal Line: {self.technical_details.get('signal', 0):.2f}
Histogram: {self.technical_details.get('histogram', 0):.2f}
Preis-Änderung (10 Tage): {self.technical_details.get('price_change_10d', 0):.2f}%

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📰 SENTIMENT-ANALYSE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Markt-Sentiment: {self.sentiment.value}
Sentiment-Score: {self.sentiment_details.get('average_sentiment', 0):.3f}
Analysierte Artikel: {self.sentiment_details.get('articles_analyzed', 0)}
  ├─ Positiv: {self.sentiment_details.get('positive_articles', 0)}
  ├─ Neutral: {self.sentiment_details.get('neutral_articles', 0)}
  └─ Negativ: {self.sentiment_details.get('negative_articles', 0)}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📋 BEGRÜNDUNG
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
"""

    def format_reasons(self):
        """Formatiert die Gründe"""
        output = ""
        for i, reason in enumerate(self.reasons, 1):
            output += f"  {i}. {reason}\n"
        return output


class SignalGenerator:
    """
    Generiert Trading-Signale durch Kombination von MACD und Sentiment
    """

    def __init__(self, newsapi_key: str = None):
        """
        Args:
            newsapi_key: Optional NewsAPI-Schlüssel für erweiterte News
        """
        self.macd_indicator = MACDIndicator()
        self.sentiment_analyzer = NewsSentimentAnalyzer(api_key=newsapi_key)

        # Gewichtungen für Signal-Berechnung
        self.macd_weight = 0.6  # 60% Gewichtung für technische Analyse
        self.sentiment_weight = 0.4  # 40% Gewichtung für Sentiment

    def calculate_combined_score(self, macd_signal: MACDSignal,
                                   sentiment: SentimentScore,
                                   macd_confidence: int,
                                   sentiment_confidence: int) -> Tuple[float, int]:
        """
        Berechnet kombinierten Score aus MACD und Sentiment

        Returns:
            Tuple aus (Score -1 bis +1, Gesamt-Konfidenz)
        """
        # Konvertiere Signale zu Scores (-1 bis +1)
        macd_score_map = {
            MACDSignal.STRONG_BUY: 1.0,
            MACDSignal.BUY: 0.5,
            MACDSignal.NEUTRAL: 0.0,
            MACDSignal.SELL: -0.5,
            MACDSignal.STRONG_SELL: -1.0
        }

        sentiment_score_map = {
            SentimentScore.VERY_POSITIVE: 1.0,
            SentimentScore.POSITIVE: 0.5,
            SentimentScore.NEUTRAL: 0.0,
            SentimentScore.NEGATIVE: -0.5,
            SentimentScore.VERY_NEGATIVE: -1.0
        }

        macd_score = macd_score_map.get(macd_signal, 0)
        sentiment_score = sentiment_score_map.get(sentiment, 0)

        # Gewichteter kombinierter Score
        combined_score = (macd_score * self.macd_weight +
                         sentiment_score * self.sentiment_weight)

        # Gewichtete kombinierte Konfidenz
        combined_confidence = int(
            macd_confidence * self.macd_weight +
            sentiment_confidence * self.sentiment_weight
        )

        # Bonus für übereinstimmende Signale
        if (macd_score > 0 and sentiment_score > 0) or \
           (macd_score < 0 and sentiment_score < 0):
            combined_confidence = min(100, combined_confidence + 10)

        return combined_score, combined_confidence

    def generate_signal(self, price_df, current_price: float) -> TradingSignal:
        """
        Generiert Trading-Signal

        Args:
            price_df: DataFrame mit historischen Preisen
            current_price: Aktueller Bitcoin-Preis

        Returns:
            TradingSignal mit Empfehlung
        """
        # Hole MACD-Signal
        macd_signal, macd_details = self.macd_indicator.get_signal(price_df)

        # Hole Sentiment
        sentiment, sentiment_details = self.sentiment_analyzer.analyze_news_sentiment(
            days=1, limit=30
        )

        # Berechne kombinierten Score
        combined_score, confidence = self.calculate_combined_score(
            macd_signal,
            sentiment,
            macd_details['confidence'],
            sentiment_details['confidence']
        )

        # Bestimme Trading-Action
        if combined_score >= 0.6:
            action = TradingAction.STRONG_BUY
        elif combined_score >= 0.2:
            action = TradingAction.BUY
        elif combined_score <= -0.6:
            action = TradingAction.STRONG_SELL
        elif combined_score <= -0.2:
            action = TradingAction.SELL
        else:
            action = TradingAction.HOLD

        # Sammle Gründe
        reasons = []

        # MACD-Gründe
        reasons.append(f"MACD-Signal: {macd_signal.value} (Konfidenz: {macd_details['confidence']}%)")
        reasons.extend(macd_details.get('reasons', []))

        # Sentiment-Gründe
        reasons.append(
            f"Markt-Sentiment: {sentiment.value} "
            f"(Konfidenz: {sentiment_details['confidence']}%, "
            f"Score: {sentiment_details['average_sentiment']:.3f})"
        )

        # Zusätzliche Hinweise
        if macd_signal in [MACDSignal.STRONG_BUY, MACDSignal.BUY] and \
           sentiment in [SentimentScore.VERY_POSITIVE, SentimentScore.POSITIVE]:
            reasons.append("✅ MACD und Sentiment stimmen überein → Starkes Signal")
        elif macd_signal in [MACDSignal.STRONG_SELL, MACDSignal.SELL] and \
             sentiment in [SentimentScore.VERY_NEGATIVE, SentimentScore.NEGATIVE]:
            reasons.append("✅ MACD und Sentiment stimmen überein → Starkes Signal")
        elif (macd_signal in [MACDSignal.STRONG_BUY, MACDSignal.BUY] and
              sentiment in [SentimentScore.NEGATIVE, SentimentScore.VERY_NEGATIVE]) or \
             (macd_signal in [MACDSignal.STRONG_SELL, MACDSignal.SELL] and
              sentiment in [SentimentScore.POSITIVE, SentimentScore.VERY_POSITIVE]):
            reasons.append("⚠️ MACD und Sentiment widersprechen sich → Vorsicht geboten")
            confidence = max(30, confidence - 20)  # Reduziere Konfidenz

        # Erstelle Trading-Signal
        signal = TradingSignal(
            action=action,
            confidence=confidence,
            price=current_price,
            timestamp=datetime.now(),
            macd_signal=macd_signal,
            sentiment=sentiment,
            reasons=reasons,
            technical_details=macd_details,
            sentiment_details=sentiment_details
        )

        return signal

    def get_recommendation_text(self, signal: TradingSignal) -> str:
        """
        Generiert Empfehlungstext

        Args:
            signal: Trading-Signal

        Returns:
            Formatierter Empfehlungstext
        """
        recommendation = str(signal)
        recommendation += signal.format_reasons()

        # Füge Handlungsempfehlung hinzu
        recommendation += "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n"
        recommendation += "💡 HANDLUNGSEMPFEHLUNG\n"
        recommendation += "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n"

        if signal.action == TradingAction.STRONG_BUY:
            recommendation += "  🟢 STARKE KAUFGELEGENHEIT\n"
            recommendation += "  → Erwäge einen Einstieg oder Aufstockung der Position\n"
            recommendation += "  → Setze Stop-Loss ca. 5-7% unter Einstiegspreis\n"
        elif signal.action == TradingAction.BUY:
            recommendation += "  🟢 KAUFGELEGENHEIT\n"
            recommendation += "  → Erwäge einen Einstieg mit kleiner Position\n"
            recommendation += "  → Warte ggf. auf Bestätigung durch weitere Signale\n"
        elif signal.action == TradingAction.HOLD:
            recommendation += "  🟡 ABWARTEN\n"
            recommendation += "  → Keine klare Richtung erkennbar\n"
            recommendation += "  → Behalte den Markt im Auge für klarere Signale\n"
        elif signal.action == TradingAction.SELL:
            recommendation += "  🔴 VERKAUFSSIGNAL\n"
            recommendation += "  → Erwäge Teilverkauf oder Gewinnmitnahme\n"
            recommendation += "  → Ziehe Stop-Loss nach, um Gewinne zu sichern\n"
        elif signal.action == TradingAction.STRONG_SELL:
            recommendation += "  🔴 STARKES VERKAUFSSIGNAL\n"
            recommendation += "  → Erwäge Ausstieg aus Position\n"
            recommendation += "  → Sichere Gewinne oder begrenze Verluste\n"

        recommendation += f"\n  ⚠️ Risiko-Hinweis: Diese Analyse hat eine Konfidenz von {signal.confidence}%\n"
        recommendation += "  ⚠️ Keine Anlageberatung - Trading auf eigenes Risiko!\n"

        recommendation += "\n╚══════════════════════════════════════════════════════════════════╝\n"

        return recommendation


if __name__ == "__main__":
    # Test
    from data_fetcher import BitcoinDataFetcher

    print("=== Bitcoin Trading Signal Generator ===\n")
    print("Lade Daten...\n")

    # Hole Preisdaten
    fetcher = BitcoinDataFetcher()
    price_df = fetcher.get_historical_data(days=30)
    current_price = fetcher.get_current_price()

    if price_df.empty or not current_price:
        print("Fehler beim Laden der Daten!")
    else:
        # Generiere Signal
        generator = SignalGenerator()
        signal = generator.generate_signal(price_df, current_price)

        # Zeige Empfehlung
        recommendation = generator.get_recommendation_text(signal)
        print(recommendation)
