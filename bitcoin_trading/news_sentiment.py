"""
News Sentiment Analyzer für Bitcoin
Analysiert Nachrichten und bestimmt das Markt-Sentiment
"""

import requests
from datetime import datetime, timedelta
from typing import List, Dict, Optional, Tuple
from enum import Enum
import re


class SentimentScore(Enum):
    """Sentiment-Score-Typen"""
    VERY_POSITIVE = "SEHR POSITIV"
    POSITIVE = "POSITIV"
    NEUTRAL = "NEUTRAL"
    NEGATIVE = "NEGATIV"
    VERY_NEGATIVE = "SEHR NEGATIV"


class NewsSentimentAnalyzer:
    """
    Analysiert Bitcoin-bezogene Nachrichten und bestimmt das Sentiment
    """

    def __init__(self, api_key: Optional[str] = None):
        """
        Args:
            api_key: NewsAPI-Schlüssel (optional, verwendet Free Tier wenn None)
        """
        self.api_key = api_key
        self.newsapi_url = "https://newsapi.org/v2/everything"

        # Sentiment-Keyword-Listen
        self.positive_keywords = [
            'bullish', 'surge', 'rally', 'gain', 'rise', 'soar', 'jump',
            'breakthrough', 'adoption', 'institutional', 'buy', 'investment',
            'growth', 'profit', 'support', 'upgrade', 'positive', 'optimistic',
            'breakout', 'moon', 'accumulation', 'bull run', 'all-time high',
            'ath', 'recovery', 'uptrend', 'momentum', 'strong'
        ]

        self.negative_keywords = [
            'bearish', 'crash', 'plunge', 'fall', 'drop', 'decline', 'sell',
            'regulation', 'ban', 'warning', 'risk', 'fear', 'panic', 'loss',
            'hack', 'fraud', 'scam', 'bubble', 'concern', 'volatile',
            'downtrend', 'resistance', 'bear market', 'dump', 'correction',
            'selling pressure', 'oversold', 'weak', 'uncertainty'
        ]

        # Crypto News Sources (kostenlos zugänglich)
        self.crypto_news_sources = [
            'https://cryptopanic.com/api/v1/posts/',
            'https://min-api.cryptocompare.com/data/v2/news/',
        ]

    def fetch_news_newsapi(self, days: int = 1) -> List[Dict]:
        """
        Holt Bitcoin-News von NewsAPI

        Args:
            days: Anzahl Tage zurück

        Returns:
            Liste von News-Artikeln
        """
        if not self.api_key:
            return []

        try:
            from_date = (datetime.now() - timedelta(days=days)).strftime('%Y-%m-%d')

            params = {
                'q': 'bitcoin OR BTC OR cryptocurrency',
                'from': from_date,
                'sortBy': 'publishedAt',
                'language': 'en',
                'apiKey': self.api_key
            }

            response = requests.get(self.newsapi_url, params=params, timeout=15)

            if response.status_code == 200:
                data = response.json()
                return data.get('articles', [])

        except Exception as e:
            print(f"Fehler beim Abrufen von NewsAPI: {e}")

        return []

    def fetch_news_cryptocompare(self, limit: int = 50) -> List[Dict]:
        """
        Holt Bitcoin-News von CryptoCompare (kostenlos)

        Args:
            limit: Anzahl News-Artikel

        Returns:
            Liste von News-Artikeln
        """
        try:
            url = "https://min-api.cryptocompare.com/data/v2/news/"
            params = {
                'categories': 'BTC',
                'lang': 'EN'
            }

            response = requests.get(url, params=params, timeout=15)

            if response.status_code == 200:
                data = response.json()
                news_list = data.get('Data', [])

                # Formatiere zu einheitlichem Format
                formatted_news = []
                for item in news_list[:limit]:
                    formatted_news.append({
                        'title': item.get('title', ''),
                        'description': item.get('body', ''),
                        'publishedAt': datetime.fromtimestamp(item.get('published_on', 0)),
                        'source': item.get('source', ''),
                        'url': item.get('url', '')
                    })

                return formatted_news

        except Exception as e:
            print(f"Fehler beim Abrufen von CryptoCompare: {e}")

        return []

    def analyze_text_sentiment(self, text: str) -> Tuple[float, Dict]:
        """
        Analysiert Sentiment eines Textes

        Args:
            text: Zu analysierender Text

        Returns:
            Tuple aus (Score -1 bis +1, Details)
        """
        if not text:
            return 0.0, {'positive': 0, 'negative': 0}

        text_lower = text.lower()

        # Zähle positive und negative Keywords
        positive_count = sum(1 for keyword in self.positive_keywords if keyword in text_lower)
        negative_count = sum(1 for keyword in self.negative_keywords if keyword in text_lower)

        # Berechne Score (-1 bis +1)
        total_keywords = positive_count + negative_count
        if total_keywords == 0:
            score = 0.0
        else:
            score = (positive_count - negative_count) / total_keywords

        details = {
            'positive': positive_count,
            'negative': negative_count,
            'total': total_keywords
        }

        return score, details

    def analyze_news_sentiment(self, days: int = 1, limit: int = 50) -> Tuple[SentimentScore, Dict]:
        """
        Analysiert Gesamt-Sentiment der aktuellen Bitcoin-Nachrichten

        Args:
            days: Anzahl Tage zurück
            limit: Max. Anzahl Artikel

        Returns:
            Tuple aus (Sentiment, Details)
        """
        # Hole News von verschiedenen Quellen
        news_articles = []

        # Versuche NewsAPI (falls API-Key vorhanden)
        if self.api_key:
            news_articles.extend(self.fetch_news_newsapi(days))

        # Hole von CryptoCompare (kostenlos)
        crypto_news = self.fetch_news_cryptocompare(limit)
        news_articles.extend(crypto_news)

        if not news_articles:
            return SentimentScore.NEUTRAL, {
                'reason': 'Keine News gefunden',
                'confidence': 0,
                'articles_analyzed': 0
            }

        # Analysiere jeden Artikel
        sentiment_scores = []
        positive_articles = 0
        negative_articles = 0
        neutral_articles = 0

        for article in news_articles[:limit]:
            title = article.get('title', '')
            description = article.get('description', '')
            combined_text = f"{title} {description}"

            score, details = self.analyze_text_sentiment(combined_text)
            sentiment_scores.append(score)

            if score > 0.2:
                positive_articles += 1
            elif score < -0.2:
                negative_articles += 1
            else:
                neutral_articles += 1

        # Berechne Durchschnitts-Sentiment
        avg_sentiment = sum(sentiment_scores) / len(sentiment_scores) if sentiment_scores else 0

        # Bestimme Gesamt-Sentiment
        if avg_sentiment > 0.4:
            sentiment = SentimentScore.VERY_POSITIVE
            confidence = min(95, int(70 + abs(avg_sentiment) * 50))
        elif avg_sentiment > 0.15:
            sentiment = SentimentScore.POSITIVE
            confidence = min(85, int(60 + abs(avg_sentiment) * 50))
        elif avg_sentiment < -0.4:
            sentiment = SentimentScore.VERY_NEGATIVE
            confidence = min(95, int(70 + abs(avg_sentiment) * 50))
        elif avg_sentiment < -0.15:
            sentiment = SentimentScore.NEGATIVE
            confidence = min(85, int(60 + abs(avg_sentiment) * 50))
        else:
            sentiment = SentimentScore.NEUTRAL
            confidence = 50

        details = {
            'average_sentiment': float(avg_sentiment),
            'confidence': confidence,
            'articles_analyzed': len(news_articles),
            'positive_articles': positive_articles,
            'negative_articles': negative_articles,
            'neutral_articles': neutral_articles,
            'top_articles': news_articles[:5]  # Top 5 für Details
        }

        return sentiment, details

    def get_trending_topics(self, news_articles: List[Dict]) -> List[str]:
        """
        Extrahiert Trend-Topics aus News

        Args:
            news_articles: Liste von News-Artikeln

        Returns:
            Liste der häufigsten Topics
        """
        topics = []
        keywords = [
            'regulation', 'etf', 'institutional', 'mining',
            'halving', 'adoption', 'sec', 'fed', 'inflation',
            'blockchain', 'defi', 'nft', 'altcoin'
        ]

        for article in news_articles:
            text = f"{article.get('title', '')} {article.get('description', '')}".lower()
            for keyword in keywords:
                if keyword in text:
                    topics.append(keyword)

        # Zähle Häufigkeit
        from collections import Counter
        topic_counts = Counter(topics)
        return [topic for topic, count in topic_counts.most_common(5)]


if __name__ == "__main__":
    # Test
    print("=== Bitcoin News Sentiment Analyzer ===\n")

    analyzer = NewsSentimentAnalyzer()

    print("Lade Bitcoin-Nachrichten...")
    sentiment, details = analyzer.analyze_news_sentiment(days=1, limit=30)

    print(f"\n=== Sentiment-Analyse ===")
    print(f"Gesamt-Sentiment: {sentiment.value}")
    print(f"Konfidenz: {details['confidence']}%")
    print(f"Durchschnitts-Score: {details['average_sentiment']:.3f}")
    print(f"\nAnalysierte Artikel: {details['articles_analyzed']}")
    print(f"  Positiv: {details['positive_articles']}")
    print(f"  Neutral: {details['neutral_articles']}")
    print(f"  Negativ: {details['negative_articles']}")

    if details.get('top_articles'):
        print(f"\n=== Top 3 Schlagzeilen ===")
        for i, article in enumerate(details['top_articles'][:3], 1):
            print(f"{i}. {article.get('title', 'N/A')}")
