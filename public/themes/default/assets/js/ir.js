(() => {
  const links = [...document.querySelectorAll('.irnav a')];
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) links.forEach(a => a.classList.toggle('is-on', a.getAttribute('href') === '#' + e.target.id));
  }), { rootMargin: '-30% 0px -60% 0px' });
  document.querySelectorAll('.irsec').forEach(s => io.observe(s));
})();
