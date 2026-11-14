(() => {
  // header: dropdowns and phone menu
  const top = document.querySelector('.top');
  const burger = top.querySelector('.top__burger');
  const groups = [...top.querySelectorAll('.nav__group')];
  const close = except => groups.forEach(g => { if (g !== except) { g.classList.remove('is-open'); g.querySelector('button').setAttribute('aria-expanded', 'false'); } });
  groups.forEach(g => {
    const b = g.querySelector('button');
    b.addEventListener('click', e => { e.stopPropagation(); const open = !g.classList.contains('is-open'); close(g); g.classList.toggle('is-open', open); b.setAttribute('aria-expanded', open); });
    if (matchMedia('(hover:hover) and (pointer:fine)').matches) {
      g.addEventListener('mouseenter', () => { if (innerWidth > 1060) { close(g); g.classList.add('is-open'); b.setAttribute('aria-expanded', 'true'); } });
      g.addEventListener('mouseleave', () => { if (innerWidth > 1060) close(); });
    }
  });
  document.addEventListener('click', e => { if (!top.contains(e.target)) { close(); top.classList.remove('is-open'); burger.setAttribute('aria-expanded', 'false'); } });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { close(); top.classList.remove('is-open'); burger.setAttribute('aria-expanded', 'false'); } });
  burger.addEventListener('click', e => { e.stopPropagation(); const open = !top.classList.contains('is-open'); top.classList.toggle('is-open', open); burger.setAttribute('aria-expanded', open); });
  top.querySelectorAll('.nav__sub a, .nav > li > a').forEach(a => a.addEventListener('click', () => { close(); top.classList.remove('is-open'); }));

})();

// Shrink each page header's outline word until the whole word fits inside the header.
(() => {
  const fit = () => document.querySelectorAll('.phead__giant').forEach(el => {
    el.style.fontSize = '';
    const box = el.parentElement, maxW = box.clientWidth - 24, maxH = box.clientHeight - 20;
    let fs = parseFloat(getComputedStyle(el).fontSize);
    for (let i = 0; i < 40 && (el.scrollWidth > maxW || el.offsetHeight > maxH); i++) { fs *= 0.95; el.style.fontSize = fs + 'px'; }
  });
  (document.fonts ? document.fonts.ready : Promise.resolve()).then(fit);
  let t; addEventListener('resize', () => { clearTimeout(t); t = setTimeout(fit, 120); });
})();
