"""
Bitcoin Trading Signal System
Ein System zur Generierung von Kauf-/Verkaufsempfehlungen für Bitcoin
basierend auf MACD-Indikatoren und News-Sentiment-Analyse
"""

__version__ = "1.0.0"
__author__ = "Bitcoin Trading Signal System"

from .data_fetcher import BitcoinDataFetcher
from .macd_indicator import MACDIndicator, MACDSignal
from .news_sentiment import NewsSentimentAnalyzer, SentimentScore
from .signal_generator import SignalGenerator, TradingAction, TradingSignal
from .bitcoin_trader import BitcoinTrader

__all__ = [
    'BitcoinDataFetcher',
    'MACDIndicator',
    'MACDSignal',
    'NewsSentimentAnalyzer',
    'SentimentScore',
    'SignalGenerator',
    'TradingAction',
    'TradingSignal',
    'BitcoinTrader',
]
