const v = document.getElementById('hv');
const m = document.getElementById('mute');
const icon = document.getElementById('sound-icon');
const text = document.getElementById('sound-text');

let audioCtx = null;

function playAmbientBakerySound() {
  try {
    const AudioCtx = window.AudioContext || window.webkitAudioContext;
    if (!AudioCtx) return;
    if (!audioCtx) audioCtx = new AudioCtx();
    if (audioCtx.state === 'suspended') audioCtx.resume();

    // Warm French Pastry Cafe Chime Sound
    const freqs = [293.66, 369.99, 440.00, 554.37]; // D major 7th chord
    freqs.forEach((freq, idx) => {
      const osc = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      osc.type = 'triangle';
      osc.frequency.value = freq;
      gain.gain.setValueAtTime(0.02, audioCtx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 3.0);
      osc.connect(gain);
      gain.connect(audioCtx.destination);
      osc.start(audioCtx.currentTime + (idx * 0.12));
      osc.stop(audioCtx.currentTime + 3.0);
    });
  } catch(e) {}
}

if (v && m) {
  m.addEventListener('click', () => {
    v.muted = !v.muted;
    m.setAttribute('aria-pressed', String(!v.muted));

    if (!v.muted) {
      m.classList.add('unmuted');
      if (icon) icon.textContent = '🔊';
      if (text) text.textContent = 'Suara Aktif (Klik untuk Mute)';
      playAmbientBakerySound();
    } else {
      m.classList.remove('unmuted');
      if (icon) icon.textContent = '🔇';
      if (text) text.textContent = 'Suara Diheningkan (Klik untuk Bunyi)';
    }
  });
}
