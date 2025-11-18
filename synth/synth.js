const btcPrice = parseFloat(document.body.dataset.btcPrice || 'NaN');
const normalizedCoin = Number.isFinite(btcPrice)
  ? Math.min(Math.max((btcPrice - 15000) / 25000, 0), 1)
  : 0.5;

const pad = document.getElementById('synth-pad');
const indicator = document.getElementById('pad-indicator');
const fmDepthInput = document.getElementById('fm-depth');
const lfoSpeedInput = document.getElementById('lfo-speed');
const textureInput = document.getElementById('texture');
const startBtn = document.getElementById('start-btn');
const randomizeBtn = document.getElementById('randomize-btn');

class MouseSynth {
  constructor(options = {}) {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    this.ctx = new AudioContext();
    this.started = false;

    this.coinBlend = options.coinBlend ?? 0.5;

    this.setupNodes();
    this.#bindEvents();
  }

  setupNodes() {
    const ctx = this.ctx;

    this.masterGain = ctx.createGain();
    this.masterGain.gain.value = 0.0;

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

    this.coinMorph = ctx.createGain();
    this.coinMorph.gain.value = this.coinBlend;

    this.reverb = ctx.createConvolver();
    this.reverb.buffer = this.#makeImpulse(2.5);
    this.reverbGain = ctx.createGain();
    this.reverbGain.gain.value = 0.25;

    // Connections
    this.fmOsc.connect(this.fmGain).connect(this.carrier.frequency);
    this.harmonic.connect(this.filter);
    this.carrier.connect(this.filter);
    this.ampLfo.connect(this.ampLfoGain).connect(this.masterGain.gain);
    this.filterLfo.connect(this.filterLfoGain).connect(this.filter.frequency);
    this.sampleHold.connect(this.sampleHoldGain).connect(this.filter.detune);

    this.filter.connect(this.distortion);
    this.noise.connect(this.noiseGain).connect(this.filter);

    const wet = ctx.createGain();
    const dry = ctx.createGain();
    this.distortion.connect(dry).connect(this.masterGain);
    this.distortion.connect(this.delay);
    this.delay.connect(this.feedback).connect(this.delay);
    this.delay.connect(this.coinMorph);
    this.coinMorph.connect(wet);
    wet.connect(this.reverb);
    this.reverb.connect(this.reverbGain).connect(this.masterGain);

    this.masterGain.connect(ctx.destination);

    this.carrier.start();
    this.harmonic.start();
    this.fmOsc.start();
    this.ampLfo.start();
    this.filterLfo.start();
    this.sampleHold.start();
    this.noise.start();
  }

  async start() {
    if (this.started) return;
    await this.ctx.resume();
    this.masterGain.gain.linearRampToValueAtTime(0.8, this.ctx.currentTime + 0.5);
    this.started = true;
  }

  #bindEvents() {
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
    this.#updateTexture(textureInput.value, x, y);
  }

  handlePointerLeave() {
    if (!this.started) return;
    this.masterGain.gain.cancelScheduledValues(this.ctx.currentTime);
    this.masterGain.gain.setTargetAtTime(0.15, this.ctx.currentTime, 0.5);
  }

  randomize() {
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
  }

  #updateTexture(value, x = 0.5, y = 0.5) {
    const amount = parseFloat(value);
    const drive = 150 + amount * 850 + this.coinBlend * 400;
    this.#setDrive(drive);
    const morph = amount * (0.6 + this.coinBlend * 0.8);
    this.distortion.oversample = morph > 0.5 ? '4x' : '2x';
    this.reverbGain.gain.setTargetAtTime(0.15 + morph * 0.6, this.ctx.currentTime, 0.3);
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
}

const synth = new MouseSynth({ coinBlend: normalizedCoin });

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

if (!Number.isFinite(btcPrice)) {
  const status = document.getElementById('btc-status');
  if (status) {
    status.insertAdjacentHTML('beforeend', '<div style="color:#ffa3a3;">BTC Feed nicht erreichbar – Synth läuft im Fantasy-Modus.</div>');
  }
}

