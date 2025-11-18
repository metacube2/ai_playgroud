<?php
$btcPrice = null;
$btcChange = null;
$btcSource = 'https://api.coindesk.com/v1/bpi/currentprice/USD.json';
try {
    $response = @file_get_contents($btcSource);
    if ($response !== false) {
        $payload = json_decode($response, true);
        if (isset($payload['bpi']['USD']['rate_float'])) {
            $btcPrice = (float) $payload['bpi']['USD']['rate_float'];
        }
        if (isset($payload['chartName'])) {
            $btcChange = $payload['chartName'];
        }

    }
} catch (Throwable $e) {
    // Silently ignore network failures, we degrade gracefully in the UI.
}


$btcLabel = $btcPrice ? number_format($btcPrice, 2) . ' $' : 'unbekannt';
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
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
    </style>
</head>
<body data-btc-price="<?= htmlspecialchars((string) ($btcPrice ?? '')) ?>">
    <div class="panel">
        <h1>Mouse Synth Lab</h1>
        <p>
            Zieh deine Maus durch den Pad und verwandle Bewegungen in Klang. Drei LFOs,
            FM-Experimente und ein Delay/Distortion-Hybrid werden live verschaltet.
            Der aktuelle Bitcoin-Kurs (<strong><?= htmlspecialchars($btcLabel) ?></strong>)
            steuert, wie aggressiv der Mix moduliert und rückgekoppelt wird.
        </p>
        <div class="status" id="btc-status">
            <div>Bitcoin Quelle: <span><?= htmlspecialchars($btcSource) ?></span></div>
            <div>Letzter Wert: <span><?= htmlspecialchars($btcLabel) ?></span></div>
        </div>
        <div class="controls">
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
            Kreise fahren. Je nach Bitcoin-Laune schalten sich neue Rückkopplungen zu.
        </p>
    </div>
    <script src="synth.js" type="module"></script>
</body>
</html>
