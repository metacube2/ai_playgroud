<?php
declare(strict_types=1);

date_default_timezone_set('Europe/Berlin');

$now = new DateTimeImmutable('now');
$dayOfYear = (int) $now->format('z') + 1;
$weekOfYear = (int) $now->format('W');
$swatchBeats = (int) floor((($now->getTimestamp() % 86400) / 86.4));
$moonPhase = (int) floor((($now->getTimestamp() / 2551443) - floor($now->getTimestamp() / 2551443)) * 100);

$worldCities = [
    ['label' => 'Berlin', 'zone' => 'Europe/Berlin'],
    ['label' => 'Tokyo', 'zone' => 'Asia/Tokyo'],
    ['label' => 'San Francisco', 'zone' => 'America/Los_Angeles'],
    ['label' => 'São Paulo', 'zone' => 'America/Sao_Paulo'],
    ['label' => 'Kapstadt', 'zone' => 'Africa/Johannesburg'],
];

$worldTimes = array_map(
    static function (array $city): array {
        $dt = new DateTimeImmutable('now', new DateTimeZone($city['zone']));
        return [
            'label' => $city['label'],
            'time' => $dt->format('H:i'),
            'date' => $dt->format('d.m.Y'),
            'weekday' => $dt->format('l'),
            'offset' => $dt->format('P'),
        ];
    },
    $worldCities
);

$timeline = [];
for ($i = 1; $i <= 5; $i++) {
    $future = $now->modify('+' . $i * 37 . ' minutes');
    $timeline[] = [
        'label' => "+" . $i * 37 . " min",
        'time' => $future->format('H:i'),
        'micro' => $future->format('s.u'),
        'iso' => $future->format(DateTimeInterface::ATOM),
    ];
}

$startMillis = (int) round(((float) $now->format('U.u')) * 1000);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hypermodern Temporal Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
            --bg: radial-gradient(circle at 20% 20%, #1d1646 0%, #05000f 60%, #020203 100%);
            --card: rgba(13, 6, 30, 0.8);
            --stroke: rgba(255, 255, 255, 0.12);
            --glow: #74f9ff;
            --accent: #ff43c1;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            min-height: 100vh;
            background: var(--bg);
            color: #f5f5ff;
            padding: clamp(1rem, 3vw, 4rem);
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        header h1 {
            font-size: clamp(2.2rem, 4vw, 3.8rem);
            margin: 0 0 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        header p {
            margin: 0;
            max-width: 520px;
            color: rgba(255, 255, 255, 0.75);
            font-size: 1rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.2rem;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 20px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255,67,193,0.35), transparent 45%);
            opacity: 0.5;
            pointer-events: none;
        }

        .card h2 {
            margin: 0 0 0.8rem;
            font-size: 1rem;
            letter-spacing: 0.3em;
            font-weight: 600;
            color: var(--glow);
        }

        .primary-time {
            font-size: clamp(3rem, 8vw, 4.5rem);
            font-weight: 600;
            letter-spacing: 0.08em;
        }

        .primary-date {
            font-family: 'IBM Plex Mono', monospace;
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }

        .metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .metric {
            flex: 1;
            min-width: 120px;
        }

        .metric span {
            display: block;
            font-family: 'IBM Plex Mono', monospace;
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
        }

        .metric strong {
            font-size: 1.4rem;
        }

        ul.timeline {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.75rem;
        }

        ul.timeline li {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 0.9rem;
            font-family: 'IBM Plex Mono', monospace;
        }

        ul.timeline li span {
            display: block;
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
        }

        .worldtime {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .city {
            display: flex;
            justify-content: space-between;
            font-family: 'IBM Plex Mono', monospace;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 0.5rem;
        }

        .city:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .city strong {
            font-size: 1rem;
        }

        .city span {
            color: rgba(255,255,255,0.65);
        }

        .nano-clock {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1.1rem;
            margin-top: 0.8rem;
            color: var(--accent);
        }

        footer {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.55);
            text-align: center;
        }

        @media (max-width: 600px) {
            body {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Hypermodern Temporal Hub</h1>
    <p>Ein futuristisches Datums-Dashboard, das terrestrische, kosmische und spekulative Zeitsysteme in einem einzigen, vibrierenden Interface verschmilzt.</p>
</header>

<section class="grid">
    <article class="card">
        <h2>JETZT</h2>
        <div class="primary-time" id="clock" aria-live="polite"><?= htmlspecialchars($now->format('H:i:s'), ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="primary-date">Tag <?= $dayOfYear; ?> · Woche <?= $weekOfYear; ?> · ISO <?= htmlspecialchars($now->format(DateTimeInterface::ATOM), ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="nano-clock" id="nano">µ<?= $now->format('u'); ?></div>
        <div class="metrics">
            <div class="metric">
                <span>Swatch Beats</span>
                <strong>@<?= str_pad((string) $swatchBeats, 3, '0', STR_PAD_LEFT); ?></strong>
            </div>
            <div class="metric">
                <span>Gravitationsphase</span>
                <strong><?= $moonPhase; ?>%</strong>
            </div>
            <div class="metric">
                <span>Unix-Epoche</span>
                <strong><?= number_format($now->getTimestamp(), 0, ',', '.'); ?></strong>
            </div>
        </div>
    </article>

    <article class="card">
        <h2>WELTWELLEN</h2>
        <div class="worldtime">
            <?php foreach ($worldTimes as $city): ?>
                <div class="city">
                    <div>
                        <strong><?= htmlspecialchars($city['label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span><?= htmlspecialchars($city['weekday'], ENT_QUOTES, 'UTF-8'); ?> · <?= htmlspecialchars($city['date'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($city['time'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span><?= htmlspecialchars($city['offset'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </article>

    <article class="card">
        <h2>TEMPORAL-LINSE</h2>
        <ul class="timeline">
            <?php foreach ($timeline as $moment): ?>
                <li>
                    <span><?= htmlspecialchars($moment['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <strong><?= htmlspecialchars($moment['time'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    <span><?= htmlspecialchars($moment['micro'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><?= htmlspecialchars($moment['iso'], ENT_QUOTES, 'UTF-8'); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>

<footer>
    Synchronisiert mit Serverzeit · Hypermodernität trifft Präzision auf <?= htmlspecialchars($now->format('d.m.Y'), ENT_QUOTES, 'UTF-8'); ?>.
</footer>

<script>
    const serverMillis = <?= json_encode($startMillis, JSON_THROW_ON_ERROR); ?>;
    const clientBoot = Date.now();
    const drift = serverMillis - clientBoot;
    const clock = document.getElementById('clock');
    const nano = document.getElementById('nano');

    function pad(value, length = 2) {
        return String(value).padStart(length, '0');
    }

    function tick() {
        const precise = new Date(Date.now() + drift);
        const hours = pad(precise.getHours());
        const minutes = pad(precise.getMinutes());
        const seconds = pad(precise.getSeconds());
        const millis = precise.getMilliseconds();
        clock.textContent = `${hours}:${minutes}:${seconds}`;
        nano.textContent = `µ${pad(millis, 3)}${String(Math.floor((millis / 1000) * 1000)).padStart(3, '0')}`;
        requestAnimationFrame(tick);
    }

    tick();
</script>
</body>
</html>
