<?php
$btcCoindeskPrice = null;
$btcCoinmarketcapPrice = null;
$btcSource = 'https://api.coindesk.com/v1/bpi/currentprice/USD.json';
$btcCmcSource = 'https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=USD';

$fetchJson = static function (string $url) {
    try {
        $response = @file_get_contents($url);
        if ($response !== false) {
            return json_decode($response, true);
        }
    } catch (Throwable $e) {
        // Ignore network failures – we fall back to fantasy values.
    }
    return null;
};

$coindeskPayload = $fetchJson($btcSource);
if (isset($coindeskPayload['bpi']['USD']['rate_float'])) {
    $btcCoindeskPrice = (float) $coindeskPayload['bpi']['USD']['rate_float'];
}

$coinmarketcapPayload = $fetchJson($btcCmcSource);
if (is_array($coinmarketcapPayload) && isset($coinmarketcapPayload[0]['price_usd'])) {
    $btcCoinmarketcapPrice = (float) $coinmarketcapPayload[0]['price_usd'];
}

$btcLabel = $btcCoindeskPrice ? number_format($btcCoindeskPrice, 2) . ' $' : 'unbekannt';
$btcCmcLabel = $btcCoinmarketcapPrice ? number_format($btcCoinmarketcapPrice, 2) . ' $' : 'unbekannt';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mouse Synth Lab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
            font-family: 'Space Grotesk', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top, #10152b, #050608 60%);
            color: #e4f6ff;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            padding: 2rem;
        }
        .panel {
            background: rgba(15, 18, 40, 0.85);
            border: 1px solid rgba(102, 204, 255, 0.25);
            border-radius: 20px;
            padding: 2rem;
            width: min(960px, 100%);
            box-shadow: 0 30px 60px rgba(5, 10, 50, 0.55);
            backdrop-filter: blur(10px);
        }
        h1 {
            margin: 0 0 1rem 0;
            font-size: clamp(2rem, 4vw, 3rem);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        p {
            margin: 0 0 1rem 0;
            line-height: 1.6;
        }
        .synth-pad {
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(35, 58, 122, 0.9), rgba(161, 92, 255, 0.75));
            border: 1px solid rgba(255,255,255,0.2);
            height: 320px;
            position: relative;
            overflow: hidden;
            cursor: crosshair;
        }
        .synth-pad::after {
            content: "";
            position: absolute;
            inset: 1rem;
            border: 1px dashed rgba(255,255,255,0.2);
            border-radius: 18px;
        }
        .pad-indicator {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: transform 0.1s ease-out;
        }
        .controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.65);
        }
        input[type="range"] {
            width: 100%;
        }
        button {
            border: none;
            border-radius: 999px;
            padding: 0.85rem 1.8rem;
            font-size: 1rem;
            cursor: pointer;
            background: linear-gradient(135deg, #6a5af9, #32d9ff);
            color: #050608;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 30px rgba(50, 217, 255, 0.3);
        }
        .status {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        .status span {
            font-weight: 600;
            color: #7fffd4;
        }
        select,
        option {
            font-size: 1rem;
            border-radius: 999px;
            padding: 0.4rem 0.75rem;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(5, 6, 8, 0.4);
            color: inherit;
        }
    </style>
</head>
<body data-btc-price="<?= htmlspecialchars((string) ($btcCoindeskPrice ?? '')) ?>" data-btc-cmc-price="<?= htmlspecialchars((string) ($btcCoinmarketcapPrice ?? '')) ?>">
    <div class="panel">
        <h1>Mouse Synth Lab</h1>
        <p>
            Zieh deine Maus durch den Pad und verwandle Bewegungen in Klang. Drei LFOs,
            FM-Experimente und ein Delay/Distortion-Hybrid werden live verschaltet.
            Die Bitcoin-Feeds von Coindesk (<strong><?= htmlspecialchars($btcLabel) ?></strong>)
            und CoinMarketCap (<strong><?= htmlspecialchars($btcCmcLabel) ?></strong>)
            mischen das Routing zusätzlich – sogar die Uhrzeit entscheidet, welches Instrument
            sich meldet und wie die Resonanzen pulsieren.
        </p>
        <div class="status" id="btc-status">
            <div>Coindesk: <span><?= htmlspecialchars($btcLabel) ?></span></div>
            <div>CoinMarketCap: <span><?= htmlspecialchars($btcCmcLabel) ?></span></div>
            <div id="clock-info">Lokale Zeit: <span>---</span></div>
        </div>
        <div class="controls">
            <div>
                <label for="instrument">Instrument</label>
                <select id="instrument">
                    <option value="supersaw">Supersaw Orbit</option>
                    <option value="fm-bell">FM Bell</option>
                    <option value="pulse-pluck">Pulse Pluck</option>
                </select>
            </div>
            <div>
                <label for="fm-depth">FM-Intensität</label>
                <input id="fm-depth" type="range" min="10" max="800" value="320">
            </div>
            <div>
                <label for="lfo-speed">LFO-Speed</label>
                <input id="lfo-speed" type="range" min="0.05" max="18" value="6" step="0.05">
            </div>
            <div>
                <label for="texture">Texture Morph</label>
                <input id="texture" type="range" min="0" max="1" step="0.01" value="0.4">
            </div>
            <div>
                <label for="noise-level">Noise Lift</label>
                <input id="noise-level" type="range" min="0" max="1" step="0.01" value="0.2">
            </div>
            <div>
                <label for="chorus-depth">Chorus Orbit</label>
                <input id="chorus-depth" type="range" min="0" max="1" step="0.01" value="0.45">
            </div>
            <div>
                <label for="phaser-mix">Phaser Drift</label>
                <input id="phaser-mix" type="range" min="0" max="1" step="0.01" value="0.5">
            </div>
            <div>
                <label for="crusher-fold">Bitcrush Fold</label>
                <input id="crusher-fold" type="range" min="2" max="32" step="1" value="12">
            </div>
            <div>
                <label for="delay-mix">Delay Mix</label>
                <input id="delay-mix" type="range" min="0" max="1" step="0.01" value="0.6">
            </div>
            <div>
                <label for="reverb-mix">Reverb Mix</label>
                <input id="reverb-mix" type="range" min="0" max="1" step="0.01" value="0.35">
            </div>
            <div>
                <label for="clock-reactivity">Zeit-Reaktivität</label>
                <input id="clock-reactivity" type="range" min="0" max="1" step="0.01" value="0.5">
            </div>
            <div>
                <label for="coin-reactivity">BTC Morph</label>
                <input id="coin-reactivity" type="range" min="0" max="1" step="0.01" value="0.5">
            </div>
            <div>
                <label for="drive">Drive Ceiling</label>
                <input id="drive" type="range" min="0" max="1" step="0.01" value="0.35">
            </div>
            <div style="display:flex;align-items:end;gap:0.75rem;">
                <button id="start-btn">Synth starten</button>
                <button id="randomize-btn" type="button">Chaos Patch</button>
            </div>
        </div>
        <div class="synth-pad" id="synth-pad">
            <div class="pad-indicator" id="pad-indicator" style="left:50%;top:50%;"></div>
        </div>
        <p style="font-size:0.9rem;color:rgba(255,255,255,0.65);margin-top:1.5rem;">
            Tipp: Halte die Maus gedrückt, damit der AudioContext aktiv bleibt, und lass den Cursor
            Kreise fahren. Je nach Bitcoin-Laune (Coindesk + CoinMarketCap) und Tageszeit schalten
            sich neue Instrumente, Delays und Resonanzen zu.
        </p>
    </div>
    <script src="synth.js" type="module"></script>
</body>
</html>
