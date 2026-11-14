(() => {
  // Stat tubes fill and the numbers count up the first time the row scrolls into view.
  const row = document.querySelector('.gauges');
  if (!row || !('IntersectionObserver' in window)) return;
  if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const items = [...row.querySelectorAll('.gauge')];
  const vals = items.map(g => g.querySelector('.gauge__value'));
  const finals = vals.map(v => v.textContent);
  items.forEach(g => g.style.setProperty('--lvl', 0));
  const parse = t => { const m = t.match(/[\d,]+/); return m ? { n: +m[0].replace(/,/g, ''), pre: t.slice(0, m.index), post: t.slice(m.index + m[0].length), comma: m[0].includes(',') } : null; };
  const fmt = (p, n) => p.pre + (p.comma ? n.toLocaleString('en-US') : String(n)) + p.post;
  const parts = finals.map(parse);
  parts.forEach((p, i) => { if (p) vals[i].textContent = fmt(p, 0); });
  const io = new IntersectionObserver(es => {
    if (!es.some(e => e.isIntersecting)) return;
    io.disconnect();
    row.classList.add('is-filling');
    items.forEach((g, i) => setTimeout(() => g.style.removeProperty('--lvl'), i * 140));
    const t0 = performance.now(), D = 1700;
    const tick = now => {
      const k = Math.min(1, (now - t0) / D), e = 1 - Math.pow(1 - k, 3);
      parts.forEach((p, i) => { if (p) vals[i].textContent = k < 1 ? fmt(p, Math.round(p.n * e)) : finals[i]; });
      if (k < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  }, { threshold: 0.35 });
  io.observe(row);
})();
