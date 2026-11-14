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

  // flask buddy: comes out once the visitor scrolls past the first screen, hops on click and when a new section arrives
  const buddy = document.querySelector('.buddy');
  if (!buddy) return;
  const hero = document.querySelector('.hero');
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const bubbles = buddy.querySelector('.buddy__bubbles');
  function hop() {
    if (reduce) return;
    buddy.classList.remove('is-hop'); void buddy.offsetWidth; buddy.classList.add('is-hop');
    for (let i = 0; i < 4; i++) {
      const b = document.createElement('i'); b.style.setProperty('--dx', (Math.random() * 40 - 20) + 'px'); b.style.animationDelay = i * 0.12 + 's';
      b.style.left = 40 + Math.random() * 20 + '%'; bubbles.appendChild(b); setTimeout(() => b.remove(), 1600);
    }
  }
  function place() {
    const past = hero ? hero.getBoundingClientRect().bottom < innerHeight * 0.4 : scrollY > 200;
    const nearFoot = document.querySelector('.foot').getBoundingClientRect().top < innerHeight - 40;
    const roomy = innerWidth >= 1392;   // only where the page margin is wide enough for it to stand on empty space
    buddy.classList.toggle('is-out', past && !nearFoot && roomy);
  }
  addEventListener('scroll', place, { passive: true }); addEventListener('resize', place); place();
  buddy.addEventListener('click', hop);
  let seen = new WeakSet();
  const io = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting && !seen.has(e.target)) { seen.add(e.target); if (buddy.classList.contains('is-out')) hop(); } }), { threshold: 0.5 });
  document.querySelectorAll('main > section').forEach(s => io.observe(s));
})();
