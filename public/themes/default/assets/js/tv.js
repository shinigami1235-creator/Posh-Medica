(() => {
  // The TV shows a thumbnail first and only loads YouTube when someone presses play, so the page stays light.
  const tv = document.querySelector('.tv'); if (!tv) return;
  const ids = tv.dataset.videos.split(','), screen = tv.querySelector('.tv__screen'), chs = [...tv.querySelectorAll('.tv__ch')];
  let cur = 0;
  const thumb = i => `https://i.ytimg.com/vi/${ids[i]}/hqdefault.jpg`;
  function show(i, play) {
    cur = i; chs.forEach((c, k) => c.setAttribute('aria-pressed', k === i));
    tv.classList.remove('is-static'); void tv.offsetWidth; tv.classList.add('is-static');
    setTimeout(() => {
      if (play) {
        screen.innerHTML = `<iframe src="https://www.youtube-nocookie.com/embed/${ids[i]}?autoplay=1&rel=0&playsinline=1" title="Posh Medica video ${i + 1}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>`;
        tv.classList.add('is-on');
        document.dispatchEvent(new CustomEvent('buddy', { detail: 'video' }));
      } else {
        const img = screen.querySelector('.tv__thumb');
        if (img) img.src = thumb(i); else { screen.innerHTML = playBtn(i); bind(); }
        tv.classList.remove('is-on');
      }
    }, 220);
  }
  const label = tv.querySelector('.tv__play').getAttribute('aria-label');
  const playBtn = i => `<button class="tv__play" type="button" aria-label="${label}"><img class="tv__thumb" src="${thumb(i)}" alt="" width="480" height="360"><span class="tv__btn" aria-hidden="true"><svg viewBox="0 0 24 24" width="34" height="34"><path d="M8 5.5v13l11-6.5z" fill="currentColor"/></svg></span></button>`;
  const bind = () => screen.querySelector('.tv__play').addEventListener('click', () => show(cur, true));
  bind();
  chs.forEach((c, k) => c.addEventListener('click', () => show(k, tv.classList.contains('is-on'))));
})();
