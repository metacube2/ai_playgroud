<?php
$altcoins = [
    [
        'name' => 'Ethereum (ETH)',
        'symbol' => 'ETH',
        'price' => 3725.42,
        'ma200' => 3450.15,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'BNB',
        'symbol' => 'BNB',
        'price' => 598.12,
        'ma200' => 612.77,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'Solana (SOL)',
        'symbol' => 'SOL',
        'price' => 158.34,
        'ma200' => 143.05,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'XRP',
        'symbol' => 'XRP',
        'price' => 0.57,
        'ma200' => 0.63,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'Dogecoin (DOGE)',
        'symbol' => 'DOGE',
        'price' => 0.19,
        'ma200' => 0.15,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'Cardano (ADA)',
        'symbol' => 'ADA',
        'price' => 0.48,
        'ma200' => 0.62,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'Avalanche (AVAX)',
        'symbol' => 'AVAX',
        'price' => 47.22,
        'ma200' => 44.61,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'Polkadot (DOT)',
        'symbol' => 'DOT',
        'price' => 8.81,
        'ma200' => 7.29,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'Chainlink (LINK)',
        'symbol' => 'LINK',
        'price' => 17.02,
        'ma200' => 18.40,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
    [
        'name' => 'Polygon (MATIC)',
        'symbol' => 'MATIC',
        'price' => 0.92,
        'ma200' => 0.98,
        'last_update' => '2024-04-21 14:00 UTC',
    ],
];

function determineSignal(float $price, float $ma200): array
{
    if ($price >= $ma200) {
        return ['LONG', 'Preis notiert über dem 200-Tage-Durchschnitt.'];
    }

    return ['SHORT', 'Preis notiert unter dem 200-Tage-Durchschnitt.'];
}

function formatNumber(float $value, int $decimals = 2): string
{
    return number_format($value, $decimals, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Altcoins – MA200 Signale</title>
    <style>
        :root {
            color-scheme: light dark;
            --bg: #0f172a;
            --fg: #f8fafc;
            --card: #1e293b;
            --accent: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top, rgba(16,185,129,0.25), transparent 55%),
                var(--bg);
            color: var(--fg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        main {
            width: min(1100px, 100%);
            background: rgba(15,23,42,0.85);
            border: 1px solid rgba(148,163,184,0.2);
            border-radius: 24px;
            box-shadow: 0 15px 60px rgba(0,0,0,0.45);
            padding: 2rem 2.5rem 2.5rem;
        }

        header {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        header h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1.2;
        }

        header p {
            margin: 0;
            color: var(--muted);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        thead th {
            text-align: left;
            font-size: 0.85rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            border-bottom: 1px solid rgba(148,163,184,0.2);
            padding-bottom: 0.75rem;
        }

        tbody td {
            padding: 1rem 0;
            border-bottom: 1px solid rgba(148,163,184,0.1);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .symbol {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .price {
            font-variant-numeric: tabular-nums;
        }

        .signal {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        .signal-long {
            background: rgba(16,185,129,0.15);
            border: 1px solid rgba(16,185,129,0.5);
            color: var(--accent);
        }

        .signal-short {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.5);
            color: var(--danger);
        }

        .telegram-card {
            margin-top: 2rem;
            background: rgba(30,41,59,0.9);
            border: 1px solid rgba(59,130,246,0.4);
            border-radius: 18px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .telegram-card h2 {
            margin: 0;
            font-size: 1.2rem;
        }

        .telegram-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .telegram-card pre {
            margin: 0;
            padding: 1rem;
            background: rgba(15,23,42,0.8);
            border-radius: 12px;
            border: 1px solid rgba(148,163,184,0.15);
            font-size: 0.9rem;
            white-space: pre-wrap;
        }

        @media (max-width: 720px) {
            main {
                padding: 1.5rem;
            }

            table, thead, tbody, tr, td, th {
                display: block;
            }

            thead {
                display: none;
            }

            tbody tr {
                border: 1px solid rgba(148,163,184,0.2);
                border-radius: 18px;
                padding: 1rem 1.25rem;
                margin-bottom: 1rem;
            }

            tbody td {
                border: none;
                padding: 0.35rem 0;
            }

            tbody td::before {
                content: attr(data-label);
                display: block;
                font-size: 0.75rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: var(--muted);
                margin-bottom: 0.2rem;
            }
        }
    </style>
</head>
<body>
<main>
    <header>
        <h1>Top 10 Altcoins &amp; MA200 Signale</h1>
        <p>Überblick über mögliche Einstiegszonen basierend auf dem 200-Tage-Durchschnitt. Die Signale (Long oder Short)
            können direkt an den Telegram-Channel weitergeleitet werden.</p>
    </header>

    <table>
        <thead>
            <tr>
                <th>Asset</th>
                <th>Preis (USD)</th>
                <th>MA200 (USD)</th>
                <th>Abweichung</th>
                <th>Signal</th>
                <th>Zuletzt aktualisiert</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($altcoins as $coin):
            [$signal, $reason] = determineSignal($coin['price'], $coin['ma200']);
            $diff = $coin['price'] - $coin['ma200'];
            $diffPercent = ($coin['ma200'] > 0)
                ? ($diff / $coin['ma200']) * 100
                : 0;
        ?>
            <tr>
                <td data-label="Asset">
                    <strong><?php echo htmlspecialchars($coin['name']); ?></strong>
                    <div class="symbol"><?php echo htmlspecialchars($coin['symbol']); ?></div>
                </td>
                <td data-label="Preis" class="price">$ <?php echo formatNumber($coin['price']); ?></td>
                <td data-label="MA200" class="price">$ <?php echo formatNumber($coin['ma200']); ?></td>
                <td data-label="Abweichung">
                    <?php echo ($diff >= 0 ? '+' : '-') . formatNumber(abs($diff)); ?>
                    <span class="symbol">(<?php echo ($diffPercent >= 0 ? '+' : '-') . formatNumber(abs($diffPercent)); ?> %)</span>
                </td>
                <td data-label="Signal">
                    <span class="signal signal-<?php echo strtolower($signal); ?>">
                        <?php echo $signal; ?>
                    </span>
                    <div class="symbol"><?php echo $reason; ?></div>
                </td>
                <td data-label="Update" class="symbol"><?php echo htmlspecialchars($coin['last_update']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $signals = array_map(function ($coin) {
        [$signal, $reason] = determineSignal($coin['price'], $coin['ma200']);
        return sprintf('%s (%s): %s – %s', $coin['name'], $coin['symbol'], $signal, $reason);
    }, $altcoins);
    ?>

    <section class="telegram-card">
        <h2>Telegram Broadcast</h2>
        <p>Kopiere die Zusammenfassung, um sie in deinem Signal-Channel zu posten, sobald eine MA200-Überschreitung
            stattfindet.</p>
        <pre><?php echo htmlspecialchars(implode("\n", $signals)); ?></pre>
    </section>
</main>
</body>
</html>
