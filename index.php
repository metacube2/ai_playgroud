<?php
$title = "MausSynth Lab";
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        :root {
            --bg: radial-gradient(circle at center, #1f014d 0%, #05010d 100%);
            --accent: #ff2bd7;
            --glow: rgba(255, 43, 215, 0.35);
            --text: #f7f3ff;
        }

        * {
            box-sizing: border-box;
            cursor: none;
        }

        body {
            margin: 0;
            font-family: 'Orbitron', 'Segoe UI', sans-serif;
            color: var(--text);
            background: var(--bg);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 8px var(--accent);
        }

        p.description {
            margin: 0;
            opacity: 0.7;
            letter-spacing: 0.08em;
            text-align: center;
            max-width: 420px;
        }

        .stage {
            position: relative;
            width: min(90vw, 900px);
            height: min(70vh, 520px);
            border-radius: 24px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            background: rgba(10, 5, 25, 0.7);
            backdrop-filter: blur(6px);
            box-shadow: 0 0 30px rgba(5, 0, 20, 0.7);
            overflow: hidden;
        }

        canvas#visualizer {
            width: 100%;
            height: 100%;
            display: block;
        }

        .cursor {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
            height: 80px;
            margin-top: -40px;
            margin-left: -40px;
            border-radius: 50%;
            pointer-events: none;
            border: 2px solid var(--accent);
            box-shadow: 0 0 30px var(--accent), inset 0 0 20px var(--glow);
            mix-blend-mode: screen;
            transition: transform 0.1s ease-out;
        }

        .hud {
            position: absolute;
            right: 16px;
            bottom: 16px;
            padding: 12px 16px;
            background: rgba(10, 5, 25, 0.8);
            border-radius: 12px;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hud span.label {
            color: rgba(255,255,255,0.6);
            margin-right: 8px;
        }

        .hint {
            margin-top: 1rem;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            opacity: 0.75;
        }

        @media (max-width: 600px) {
            * {
                cursor: default;
            }

            .cursor {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="description">Schiebe deine Maus über das Klangfeld, um Frequenz, Filter und verrückte Modulation zu steuern. Klicke, um Beats zu triggern.</p>

    <div class="stage" id="stage">
        <canvas id="visualizer"></canvas>
        <div class="cursor" id="cursor"></div>
        <div class="hud" id="hud">
            <div><span class="label">Freq</span><span id="freqReadout">-- Hz</span></div>
            <div><span class="label">Reso</span><span id="resoReadout">--</span></div>
            <div><span class="label">Noise</span><span id="noiseReadout">--</span></div>
        </div>
    </div>

    <p class="hint">Tipp: Halte die Maustaste gedrückt, bewege dich in Kreisen &ndash; und genieße den abgefahrenen Klangteppich!</p>

    <script>
        const stage = document.getElementById('stage');
        const cursor = document.getElementById('cursor');
        const canvas = document.getElementById('visualizer');
        const ctx = canvas.getContext('2d');
        const freqReadout = document.getElementById('freqReadout');
        const resoReadout = document.getElementById('resoReadout');
        const noiseReadout = document.getElementById('noiseReadout');

        let audioCtx;
        let masterGain;
        let filter;
        let lfo;
        let lfoGain;
        let noise;
        let noiseGain;
        let compressor;
        let isPressed = false;
        let analyser;
        let bufferLength;
        let dataArray;

        function setupAudio() {
            if (audioCtx) return;
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();

            const osc = audioCtx.createOscillator();
            osc.type = 'sawtooth';

            filter = audioCtx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.value = 200;
            filter.Q.value = 12;

            lfo = audioCtx.createOscillator();
            lfo.type = 'sine';
            lfo.frequency.value = 4;

            lfoGain = audioCtx.createGain();
            lfoGain.gain.value = 1200;

            noise = createNoiseSource();
            noiseGain = audioCtx.createGain();
            noiseGain.gain.value = 0.05;

            masterGain = audioCtx.createGain();
            masterGain.gain.value = 0.0;

            compressor = audioCtx.createDynamicsCompressor();
            compressor.threshold.value = -24;
            compressor.ratio.value = 12;
            compressor.attack.value = 0.005;
            compressor.release.value = 0.2;

            analyser = audioCtx.createAnalyser();
            analyser.fftSize = 1024;
            bufferLength = analyser.frequencyBinCount;
            dataArray = new Uint8Array(bufferLength);

            osc.connect(filter);
            filter.connect(masterGain);

            lfo.connect(lfoGain);
            lfoGain.connect(filter.frequency);

            noise.connect(noiseGain).connect(masterGain);

            masterGain.connect(compressor).connect(audioCtx.destination);
            masterGain.connect(analyser);

            osc.start();
            lfo.start();
            noise.start(0);
        }

        function createNoiseSource() {
            const rate = audioCtx.sampleRate;
            const bufferSize = rate * 2;
            const buffer = audioCtx.createBuffer(1, bufferSize, rate);
            const output = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                output[i] = Math.random() * 2 - 1;
            }
            const noiseSource = audioCtx.createBufferSource();
            noiseSource.buffer = buffer;
            noiseSource.loop = true;
            return noiseSource;
        }

        function resizeCanvas() {
            canvas.width = stage.clientWidth;
            canvas.height = stage.clientHeight;
        }

        function updateAudio(x, y) {
            if (!audioCtx) return;
            const rect = stage.getBoundingClientRect();
            const normX = (x - rect.left) / rect.width;
            const normY = (y - rect.top) / rect.height;

            const minFreq = 80;
            const maxFreq = 1200;
            const freq = minFreq * Math.pow(maxFreq / minFreq, normX);
            filter.frequency.setTargetAtTime(freq, audioCtx.currentTime, 0.02);

            const q = 4 + normY * 20;
            filter.Q.setTargetAtTime(q, audioCtx.currentTime, 0.02);

            const noiseLevel = normY * 0.3;
            noiseGain.gain.setTargetAtTime(noiseLevel, audioCtx.currentTime, 0.05);

            const lfoSpeed = 0.5 + normX * 8;
            lfo.frequency.setTargetAtTime(lfoSpeed, audioCtx.currentTime, 0.05);

            freqReadout.textContent = `${freq.toFixed(1)} Hz`;
            resoReadout.textContent = q.toFixed(2);
            noiseReadout.textContent = noiseLevel.toFixed(2);
        }

        function animate() {
            requestAnimationFrame(animate);
            if (!analyser) return;
            analyser.getByteFrequencyData(dataArray);
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const barWidth = (canvas.width / bufferLength) * 2.5;
            let x = 0;
            for (let i = 0; i < bufferLength; i++) {
                const barHeight = dataArray[i] / 255 * canvas.height;
                const hue = (i / bufferLength) * 360;
                ctx.fillStyle = `hsla(${hue}, 90%, 60%, 0.6)`;
                ctx.fillRect(x, canvas.height - barHeight, barWidth, barHeight);
                x += barWidth + 1;
            }
        }

        function pointerMove(event) {
            const x = event.clientX;
            const y = event.clientY;
            cursor.style.transform = `translate(${x}px, ${y}px) scale(${isPressed ? 1.2 : 1})`;
            updateAudio(x, y);
        }

        function pointerDown() {
            isPressed = true;
            if (!audioCtx) {
                setupAudio();
            }
            if (!audioCtx) return;

            const resumePromise = audioCtx.state === 'suspended'
                ? audioCtx.resume()
                : Promise.resolve();

            resumePromise
                .then(() => {
                    masterGain.gain.cancelScheduledValues(audioCtx.currentTime);
                    masterGain.gain.setTargetAtTime(0.7, audioCtx.currentTime, 0.02);
                    lfoGain.gain.setTargetAtTime(900, audioCtx.currentTime, 0.08);
                })
                .catch((error) => {
                    console.error('Failed to resume AudioContext', error);
                });
        }

        function pointerUp() {
            isPressed = false;
            if (!audioCtx) return;
            masterGain.gain.setTargetAtTime(0.0, audioCtx.currentTime, 0.05);
            lfoGain.gain.setTargetAtTime(200, audioCtx.currentTime, 0.08);
        }

        stage.addEventListener('pointermove', pointerMove);
        stage.addEventListener('pointerdown', pointerDown);
        window.addEventListener('pointerup', pointerUp);

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
        animate();

        document.addEventListener('keydown', (event) => {
            if (!audioCtx) return;
            if (event.code === 'Space') {
                const now = audioCtx.currentTime;
                const burst = audioCtx.createOscillator();
                burst.type = 'square';
                burst.frequency.value = filter.frequency.value * 0.5;

                const burstGain = audioCtx.createGain();
                burstGain.gain.value = 0;
                burst.connect(burstGain).connect(masterGain);

                burst.start(now);
                burstGain.gain.setValueAtTime(0, now);
                burstGain.gain.linearRampToValueAtTime(0.8, now + 0.05);
                burstGain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
                burst.stop(now + 0.6);
            }
        });
    </script>
</body>
</html>
