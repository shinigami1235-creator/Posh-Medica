(() => {
  const belt = document.querySelector('.belt');
  if (!belt) return;
  new IntersectionObserver(e => belt.classList.toggle('is-on', e[0].isIntersecting), { threshold: 0.3 }).observe(belt);
})();
