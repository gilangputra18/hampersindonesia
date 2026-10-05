const v = document.getElementById('hv'), m = document.getElementById('mute');
if (v && m) m.addEventListener('click', () => {
  v.muted = !v.muted;
  m.textContent = v.muted ? 'Sound off' : 'Sound on';
  m.setAttribute('aria-pressed', String(!v.muted));
});
