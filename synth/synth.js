const btcPrice = parseFloat(document.body.dataset.btcPrice || 'NaN');
const btcCmcPrice = parseFloat(document.body.dataset.btcCmcPrice || 'NaN');
const coinPool = [btcPrice, btcCmcPrice].filter((value) => Number.isFinite(value));
const normalizedCoin = coinPool.length
  ? coinPool
      .map((value) => Math.min(Math.max((value - 14000) / 28000, 0), 1))
      .reduce((acc, value) => acc + value, 0) / coinPool.length
  : 0.5;

const pad = document.getElementById('synth-pad');
const indicator = document.getElementById('pad-indicator');
const instrumentSelect = document.getElementById('instrument');
const fmDepthInput = document.getElementById('fm-depth');
const lfoSpeedInput = document.getElementById('lfo-speed');
const textureInput = document.getElementById('texture');
const noiseInput = document.getElementById('noise-level');
const chorusInput = document.getElementById('chorus-depth');
const phaserInput = document.getElementById('phaser-mix');
const crusherInput = document.getElementById('crusher-fold');
const delayMixInput = document.getElementById('delay-mix');
const reverbInput = document.getElementById('reverb-mix');
const clockReactivityInput = document.getElementById('clock-reactivity');
const coinReactivityInput = document.getElementById('coin-reactivity');
const driveInput = document.getElementById('drive');
const startBtn = document.getElementById('start-btn');
const randomizeBtn = document.getElementById('randomize-btn');
const clockInfo = document.getElementById('clock-info');

class MouseSynth {
  constructor(options = {}) {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    this.ctx = new AudioContext();
    this.started = false;

    this.coinBlend = options.coinBlend ?? 0.5;
    this.clockDepth = options.clockDepth ?? 0.5;
    this.driveCeiling = options.driveCeiling ?? 0.35;
    this.instrument = 'supersaw';
    this.instrumentNoiseScale = 1;
    this.chorusAmount = chorusInput ? parseFloat(chorusInput.value) : 0.45;
    this.phaserAmount = phaserInput ? parseFloat(phaserInput.value) : 0.5;
    this.crushFold = crusherInput ? parseFloat(crusherInput.value) : 12;

    this.setupNodes();
    this.#bindEvents();
  }

  setupNodes() {
    const ctx = this.ctx;

    this.masterGain = ctx.createGain();
    this.masterGain.gain.value = 0.0;

    this.compressor = ctx.createDynamicsCompressor();
    this.compressor.threshold.value = -8;
    this.compressor.knee.value = 12;
    this.compressor.ratio.value = 12;
    this.compressor.attack.value = 0.004;
    this.compressor.release.value = 0.25;

    this.panner = ctx.createStereoPanner();

    this.carrier = ctx.createOscillator();
    this.carrier.type = 'sawtooth';

    this.harmonic = ctx.createOscillator();
    this.harmonic.type = 'triangle';
    this.harmonic.detune.value = 702; // perfect fifth

    this.fmOsc = ctx.createOscillator();
    this.fmGain = ctx.createGain();
    this.fmGain.gain.value = 320;

    this.ampLfo = ctx.createOscillator();
    this.ampLfo.type = 'sine';
    this.ampLfo.frequency.value = 6;
    this.ampLfoGain = ctx.createGain();
    this.ampLfoGain.gain.value = 0.5;

    this.filterLfo = ctx.createOscillator();
    this.filterLfo.type = 'triangle';
    this.filterLfo.frequency.value = 0.5;
    this.filterLfoGain = ctx.createGain();
    this.filterLfoGain.gain.value = 800;

    this.sampleHold = ctx.createOscillator();
    this.sampleHold.type = 'square';
    this.sampleHold.frequency.value = 8;
    this.sampleHoldGain = ctx.createGain();
    this.sampleHoldGain.gain.value = 0.0025;

    this.filter = ctx.createBiquadFilter();
    this.filter.type = 'bandpass';
    this.filter.frequency.value = 600;
    this.filter.Q.value = 8;

    this.delay = ctx.createDelay(1.2);
    this.delay.delayTime.value = 0.45;
    this.feedback = ctx.createGain();
    this.feedback.gain.value = 0.32;

    this.noise = this.#createNoise();
    this.noiseGain = ctx.createGain();
    this.noiseGain.gain.value = 0.0;

    this.distortion = ctx.createWaveShaper();
    this.#setDrive(400);

    this.chorusDelay = ctx.createDelay(0.05);
    this.chorusDelay.delayTime.value = 0.018;
    this.chorusDepthGain = ctx.createGain();
    this.chorusDepthGain.gain.value = 0.003;
    this.chorusLfo = ctx.createOscillator();
    this.chorusLfo.frequency.value = 0.25;
    this.chorusLfo.connect(this.chorusDepthGain).connect(this.chorusDelay.delayTime);
    this.chorusDry = ctx.createGain();
    this.chorusDry.gain.value = 0.6;
    this.chorusWet = ctx.createGain();
    this.chorusWet.gain.value = 0.4;
    this.chorusSum = ctx.createGain();

    this.phaserInput = ctx.createGain();
    this.phaserDry = ctx.createGain();
    this.phaserDry.gain.value = 0.5;
    this.phaserWet = ctx.createGain();
    this.phaserWet.gain.value = 0.5;
    this.phaserLfo = ctx.createOscillator();
    this.phaserLfo.frequency.value = 0.12;
    this.phaserLfoGain = ctx.createGain();
    this.phaserLfoGain.gain.value = 280;
    this.phaserStages = Array.from({ length: 4 }, (_, index) => {
      const stage = ctx.createBiquadFilter();
      stage.type = 'allpass';
      stage.frequency.value = 300 + index * 220;
      stage.Q.value = 2.5;
      return stage;
    });
    this.phaserStages.forEach((stage, index, arr) => {
      if (index > 0) {
        arr[index - 1].connect(stage);
      }
      this.phaserLfoGain.connect(stage.frequency);
    });
    this.phaserLfo.connect(this.phaserLfoGain);
    this.postPhaser = ctx.createGain();

    this.crusher = ctx.createWaveShaper();
    this.#updateCrusher(this.crushFold);
    this.crusherDry = ctx.createGain();
    this.crusherDry.gain.value = 0.55;
    this.crusherWet = ctx.createGain();
    this.crusherWet.gain.value = 0.45;
    this.crusherOutput = ctx.createGain();

    this.coinMorph = ctx.createGain();
    this.coinMorph.gain.value = this.coinBlend;

    this.reverb = ctx.createConvolver();
    this.reverb.buffer = this.#makeImpulse(2.5);
    this.reverbGain = ctx.createGain();
    this.reverbGain.gain.value = 0.25;

    this.dryGain = ctx.createGain();
    this.dryGain.gain.value = 1 - (delayMixInput ? parseFloat(delayMixInput.value) : 0.6);
    this.delayWet = ctx.createGain();
    this.delayWet.gain.value = delayMixInput ? parseFloat(delayMixInput.value) : 0.6;

    // Connections
    this.fmOsc.connect(this.fmGain).connect(this.carrier.frequency);
    this.harmonic.connect(this.filter);
    this.carrier.connect(this.filter);
    this.ampLfo.connect(this.ampLfoGain).connect(this.masterGain.gain);
    this.filterLfo.connect(this.filterLfoGain).connect(this.filter.frequency);
    this.sampleHold.connect(this.sampleHoldGain).connect(this.filter.detune);

    this.filter.connect(this.distortion);
    this.noise.connect(this.noiseGain).connect(this.filter);

    this.distortion.connect(this.chorusDry).connect(this.chorusSum);
    this.distortion.connect(this.chorusDelay).connect(this.chorusWet).connect(this.chorusSum);
    this.chorusSum.connect(this.phaserInput);
    this.phaserInput.connect(this.phaserDry).connect(this.postPhaser);
    this.phaserInput.connect(this.phaserStages[0]);
    this.phaserStages[this.phaserStages.length - 1].connect(this.phaserWet).connect(this.postPhaser);
    this.postPhaser.connect(this.crusherDry).connect(this.crusherOutput);
    this.postPhaser.connect(this.crusher).connect(this.crusherWet).connect(this.crusherOutput);

    this.crusherOutput.connect(this.dryGain).connect(this.masterGain);
    this.crusherOutput.connect(this.delay);
    this.delay.connect(this.feedback).connect(this.delay);
    this.delay.connect(this.coinMorph);
    this.coinMorph.connect(this.delayWet);
    this.delayWet.connect(this.reverb);
    this.reverb.connect(this.reverbGain).connect(this.masterGain);

    this.masterGain.connect(this.panner).connect(this.compressor).connect(ctx.destination);

    this.carrier.start();
    this.harmonic.start();
    this.fmOsc.start();
    this.ampLfo.start();
    this.filterLfo.start();
    this.sampleHold.start();
    this.noise.start();
    this.chorusLfo.start();
    this.phaserLfo.start();
  }

  async start() {
    if (this.started) return;
    await this.ctx.resume();
    this.masterGain.gain.linearRampToValueAtTime(0.65, this.ctx.currentTime + 0.5);
    this.started = true;
    this.tickClock();
  }

  #bindEvents() {
    if (instrumentSelect) {
      instrumentSelect.addEventListener('change', () => {
        this.setInstrument(instrumentSelect.value);
      });
    }

    fmDepthInput.addEventListener('input', () => {
      this.fmGain.gain.setTargetAtTime(parseFloat(fmDepthInput.value), this.ctx.currentTime, 0.05);
    });

    lfoSpeedInput.addEventListener('input', () => {
      const rate = parseFloat(lfoSpeedInput.value);
      this.ampLfo.frequency.setTargetAtTime(rate, this.ctx.currentTime, 0.1);
      this.filterLfo.frequency.setTargetAtTime(rate * 0.25, this.ctx.currentTime, 0.1);
    });

    textureInput.addEventListener('input', () => {
      this.#updateTexture(parseFloat(textureInput.value));
    });

    noiseInput.addEventListener('input', () => {
      this.setNoiseLevel(parseFloat(noiseInput.value));
    });

    chorusInput.addEventListener('input', () => {
      this.setChorusDepth(parseFloat(chorusInput.value));
    });

    phaserInput.addEventListener('input', () => {
      this.setPhaserMix(parseFloat(phaserInput.value));
    });

    crusherInput.addEventListener('input', () => {
      this.setCrusherFold(parseFloat(crusherInput.value));
    });

    delayMixInput.addEventListener('input', () => {
      this.setDelayMix(parseFloat(delayMixInput.value));
    });

    reverbInput.addEventListener('input', () => {
      this.setReverbMix(parseFloat(reverbInput.value));
    });

    clockReactivityInput.addEventListener('input', () => {
      this.setClockDepth(parseFloat(clockReactivityInput.value));
    });

    coinReactivityInput.addEventListener('input', () => {
      this.setCoinBlend(parseFloat(coinReactivityInput.value));
    });

    driveInput.addEventListener('input', () => {
      this.setDriveCeiling(parseFloat(driveInput.value));
      this.#updateTexture(parseFloat(textureInput.value));
    });

    randomizeBtn.addEventListener('click', () => this.randomize());
  }

  handlePointer(event) {
    if (!this.started) return;
    const rect = pad.getBoundingClientRect();
    const x = (event.clientX - rect.left) / rect.width;
    const y = (event.clientY - rect.top) / rect.height;
    const freq = 120 + (1 - y) * 1080;

    this.carrier.frequency.setTargetAtTime(freq, this.ctx.currentTime, 0.05);
    this.harmonic.frequency.setTargetAtTime(freq * 1.5, this.ctx.currentTime, 0.05);
    this.filter.frequency.setTargetAtTime(200 + x * 5200, this.ctx.currentTime, 0.08);
    this.filter.Q.setTargetAtTime(4 + y * 18, this.ctx.currentTime, 0.1);
    this.noiseGain.gain.setTargetAtTime(x * 0.3, this.ctx.currentTime, 0.2);

    this.sampleHold.frequency.setTargetAtTime(4 + x * 20, this.ctx.currentTime, 0.1);
    this.delay.delayTime.setTargetAtTime(0.15 + y * 0.6, this.ctx.currentTime, 0.2);
    this.feedback.gain.setTargetAtTime(0.2 + x * 0.7 * this.coinBlend, this.ctx.currentTime, 0.2);
    this.coinMorph.gain.setTargetAtTime(this.coinBlend * (0.4 + y * 0.6), this.ctx.currentTime, 0.3);
    this.fmGain.gain.setTargetAtTime(parseFloat(fmDepthInput.value) + x * 200, this.ctx.currentTime, 0.05);
    this.chorusLfo.frequency.setTargetAtTime(0.1 + x * 2.4, this.ctx.currentTime, 0.2);
    this.phaserLfo.frequency.setTargetAtTime(0.05 + y * 1.2 + this.phaserAmount * 0.4, this.ctx.currentTime, 0.2);
    this.#updateTexture(textureInput.value, x, y);
    this.tickClock();
  }

  handlePointerLeave() {
    if (!this.started) return;
    this.masterGain.gain.cancelScheduledValues(this.ctx.currentTime);
    this.masterGain.gain.setTargetAtTime(0.15, this.ctx.currentTime, 0.5);
  }

  randomize() {
    const instruments = Array.from(instrumentSelect.options).map((option) => option.value);
    const instrument = instruments[Math.floor(Math.random() * instruments.length)];
    instrumentSelect.value = instrument;
    this.setInstrument(instrument);

    const fm = 80 + Math.random() * 720;
    fmDepthInput.value = fm.toFixed(0);
    this.fmGain.gain.setTargetAtTime(fm, this.ctx.currentTime, 0.1);

    const lfo = 0.1 + Math.random() * 16;
    lfoSpeedInput.value = lfo.toFixed(2);
    this.ampLfo.frequency.setTargetAtTime(lfo, this.ctx.currentTime, 0.2);
    this.filterLfo.frequency.setTargetAtTime(lfo * 0.3, this.ctx.currentTime, 0.2);

    const texture = Math.random();
    textureInput.value = texture.toFixed(2);
    this.#updateTexture(texture);

    const noise = Math.random();
    noiseInput.value = noise.toFixed(2);
    this.setNoiseLevel(noise);

    const delayMix = Math.random();
    delayMixInput.value = delayMix.toFixed(2);
    this.setDelayMix(delayMix);

    const reverbMix = Math.random();
    reverbInput.value = reverbMix.toFixed(2);
    this.setReverbMix(reverbMix);

    const chorusAmt = Math.random();
    chorusInput.value = chorusAmt.toFixed(2);
    this.setChorusDepth(chorusAmt);

    const phaserAmt = Math.random();
    phaserInput.value = phaserAmt.toFixed(2);
    this.setPhaserMix(phaserAmt);

    const crushFold = Math.floor(2 + Math.random() * 30);
    crusherInput.value = crushFold.toString();
    this.setCrusherFold(crushFold);

    const clockDepth = Math.random();
    clockReactivityInput.value = clockDepth.toFixed(2);
    this.setClockDepth(clockDepth);

    const coinDepth = Math.random();
    coinReactivityInput.value = coinDepth.toFixed(2);
    this.setCoinBlend(coinDepth);

    const drive = Math.random();
    driveInput.value = drive.toFixed(2);
    this.setDriveCeiling(drive);
    this.#updateTexture(texture);
  }

  #updateTexture(value, x = 0.5, y = 0.5) {
    const amount = parseFloat(value);
    const drive = 150 + amount * 850 + this.coinBlend * 400;
    const driveLimit = 150 + this.driveCeiling * 700;
    this.#setDrive(Math.min(drive, driveLimit));
    const morph = amount * (0.6 + this.coinBlend * 0.8);
    this.distortion.oversample = morph > 0.5 ? '4x' : '2x';
    this.reverbGain.gain.setTargetAtTime(0.1 + morph * 0.5, this.ctx.currentTime, 0.3);
    const filterType = morph > 0.7 ? 'notch' : morph > 0.35 ? 'bandpass' : 'lowpass';
    this.filter.type = filterType;
    this.coinMorph.gain.setTargetAtTime(this.coinBlend * (0.4 + morph), this.ctx.currentTime, 0.3);
    this.delay.delayTime.setTargetAtTime(0.2 + morph * 0.4 + (x * y) * 0.2, this.ctx.currentTime, 0.2);
  }

  #setDrive(amount) {
    const curve = new Float32Array(1024);
    for (let i = 0; i < curve.length; i++) {
      const x = (i / curve.length) * 2 - 1;
      curve[i] = Math.tanh(x * amount * 0.01);
    }
    this.distortion.curve = curve;
  }

  #updateCrusher(levels) {
    const steps = Math.max(2, levels);
    const curve = new Float32Array(1024);
    for (let i = 0; i < curve.length; i++) {
      const x = (i / (curve.length - 1)) * 2 - 1;
      const normalized = (x + 1) / 2;
      const quantized = Math.round(normalized * (steps - 1)) / (steps - 1);
      curve[i] = quantized * 2 - 1;
    }
    this.crusher.curve = curve;
  }

  #createNoise() {
    const buffer = this.ctx.createBuffer(1, this.ctx.sampleRate * 4, this.ctx.sampleRate);
    const data = buffer.getChannelData(0);
    for (let i = 0; i < data.length; i++) {
      data[i] = Math.random() * 2 - 1;
    }
    const noise = this.ctx.createBufferSource();
    noise.buffer = buffer;
    noise.loop = true;
    return noise;
  }

  #makeImpulse(seconds) {
    const rate = this.ctx.sampleRate;
    const length = rate * seconds;
    const impulse = this.ctx.createBuffer(2, length, rate);
    for (let ch = 0; ch < 2; ch++) {
      const data = impulse.getChannelData(ch);
      for (let i = 0; i < length; i++) {
        data[i] = (Math.random() * 2 - 1) * Math.pow(1 - i / length, 2);
      }
    }
    return impulse;
  }

  setInstrument(mode) {
    this.instrument = mode;
    switch (mode) {
      case 'fm-bell':
        this.carrier.type = 'sine';
        this.harmonic.type = 'sine';
        this.harmonic.detune.setTargetAtTime(1200, this.ctx.currentTime, 0.2);
        this.filter.Q.setTargetAtTime(14, this.ctx.currentTime, 0.2);
        this.filter.type = 'bandpass';
        this.instrumentNoiseScale = 0.2;
        break;
      case 'pulse-pluck':
        this.carrier.type = 'square';
        this.harmonic.type = 'square';
        this.harmonic.detune.setTargetAtTime(305, this.ctx.currentTime, 0.2);
        this.filter.type = 'lowpass';
        this.filter.Q.setTargetAtTime(6, this.ctx.currentTime, 0.2);
        this.instrumentNoiseScale = 0.6;
        break;
      default:
        this.carrier.type = 'sawtooth';
        this.harmonic.type = 'triangle';
        this.harmonic.detune.setTargetAtTime(702, this.ctx.currentTime, 0.2);
        this.filter.type = 'bandpass';
        this.filter.Q.setTargetAtTime(10, this.ctx.currentTime, 0.2);
        this.instrumentNoiseScale = 1;
    }
    this.setNoiseLevel(parseFloat(noiseInput.value));
  }

  setNoiseLevel(amount) {
    const scale = this.instrumentNoiseScale ?? 1;
    this.noiseGain.gain.setTargetAtTime(amount * 0.6 * scale, this.ctx.currentTime, 0.1);
  }

  setDelayMix(amount) {
    this.dryGain.gain.setTargetAtTime(Math.max(0.05, 1 - amount), this.ctx.currentTime, 0.2);
    this.delayWet.gain.setTargetAtTime(amount, this.ctx.currentTime, 0.2);
  }

  setReverbMix(amount) {
    this.reverbGain.gain.setTargetAtTime(0.05 + amount * 0.7, this.ctx.currentTime, 0.2);
  }

  setCoinBlend(amount) {
    this.coinBlend = amount;
    this.coinMorph.gain.setTargetAtTime(amount * 0.8, this.ctx.currentTime, 0.2);
  }

  setClockDepth(amount) {
    this.clockDepth = amount;
    this.tickClock();
  }

  setDriveCeiling(amount) {
    this.driveCeiling = amount;
  }

  setChorusDepth(amount) {
    this.chorusAmount = amount;
    const depth = 0.001 + amount * 0.01;
    this.chorusDepthGain.gain.setTargetAtTime(depth, this.ctx.currentTime, 0.2);
    const wet = 0.15 + amount * 0.85;
    const dry = Math.max(0.15, 1 - wet * 0.8);
    this.chorusWet.gain.setTargetAtTime(wet, this.ctx.currentTime, 0.2);
    this.chorusDry.gain.setTargetAtTime(dry, this.ctx.currentTime, 0.2);
  }

  setPhaserMix(amount) {
    this.phaserAmount = amount;
    this.phaserWet.gain.setTargetAtTime(amount, this.ctx.currentTime, 0.2);
    this.phaserDry.gain.setTargetAtTime(1 - amount, this.ctx.currentTime, 0.2);
    this.phaserLfo.frequency.setTargetAtTime(0.05 + amount * 1.2, this.ctx.currentTime, 0.3);
    this.phaserLfoGain.gain.setTargetAtTime(120 + amount * 620, this.ctx.currentTime, 0.3);
  }

  setCrusherFold(levels) {
    const fold = Math.max(2, Math.round(levels));
    this.crushFold = fold;
    this.#updateCrusher(fold);
    const wet = Math.min(0.85, 0.2 + fold / 40);
    this.crusherWet.gain.setTargetAtTime(wet, this.ctx.currentTime, 0.2);
    this.crusherDry.gain.setTargetAtTime(1 - wet * 0.7, this.ctx.currentTime, 0.2);
  }

  tickClock() {
    const now = new Date();
    const hourFactor = now.getHours() / 23 || 0;
    const secondFactor = now.getSeconds() / 59 || 0;
    const milliFactor = now.getMilliseconds() / 999 || 0;
    const depth = this.clockDepth;
    const detune = (hourFactor - 0.5) * 600 * depth;
    this.filter.detune.setTargetAtTime(detune, this.ctx.currentTime, 0.4);
    this.harmonic.detune.setTargetAtTime(702 + detune * 0.4, this.ctx.currentTime, 0.4);
    const pan = (secondFactor - 0.5) * 1.8 * depth;
    this.panner.pan.setTargetAtTime(pan, this.ctx.currentTime, 0.3);
    const ampMod = 0.2 + hourFactor * 0.7 * depth;
    this.ampLfoGain.gain.setTargetAtTime(ampMod, this.ctx.currentTime, 0.3);
    const shRate = 2 + milliFactor * 14 * depth;
    this.sampleHold.frequency.setTargetAtTime(shRate, this.ctx.currentTime, 0.2);
  }
}

const synth = new MouseSynth({
  coinBlend: normalizedCoin,
  clockDepth: parseFloat(clockReactivityInput.value),
  driveCeiling: parseFloat(driveInput.value),
});

coinReactivityInput.value = normalizedCoin.toFixed(2);
synth.setCoinBlend(normalizedCoin);
if (instrumentSelect) {
  synth.setInstrument(instrumentSelect.value);
}
if (noiseInput) {
  synth.setNoiseLevel(parseFloat(noiseInput.value));
}
if (delayMixInput) {
  synth.setDelayMix(parseFloat(delayMixInput.value));
}
if (reverbInput) {
  synth.setReverbMix(parseFloat(reverbInput.value));
}
if (chorusInput) {
  synth.setChorusDepth(parseFloat(chorusInput.value));
}
if (phaserInput) {
  synth.setPhaserMix(parseFloat(phaserInput.value));
}
if (crusherInput) {
  synth.setCrusherFold(parseFloat(crusherInput.value));
}

startBtn.addEventListener('click', () => synth.start());

pad.addEventListener('pointerdown', async (event) => {
  await synth.start();
  pad.setPointerCapture(event.pointerId);
  indicator.style.opacity = '1';
});

pad.addEventListener('pointermove', (event) => {
  indicator.style.left = `${event.offsetX}px`;
  indicator.style.top = `${event.offsetY}px`;
  synth.handlePointer(event);
});

pad.addEventListener('pointerup', (event) => {
  pad.releasePointerCapture(event.pointerId);
  synth.handlePointerLeave();
});

pad.addEventListener('pointerleave', () => synth.handlePointerLeave());

function updateClockStatus() {
  const now = new Date();
  const formatted = now.toLocaleTimeString('de-DE', { hour12: false });
  if (clockInfo) {
    clockInfo.innerHTML = `Lokale Zeit: <span>${formatted}.${now.getMilliseconds().toString().padStart(3, '0')}</span>`;
  }
  synth.tickClock();
}

setInterval(updateClockStatus, 1000);
updateClockStatus();

if (!coinPool.length) {
  const status = document.getElementById('btc-status');
  if (status) {
    status.insertAdjacentHTML('beforeend', '<div style="color:#ffa3a3;">BTC Feeds nicht erreichbar – Synth läuft im Fantasy-Modus.</div>');
  }
}

