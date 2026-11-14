(() => {
  // One-screen hero. Tamarind pours once on load; the flavour picker replays the pour with the chosen flavour.
  const P = [
    { key: 'tamarind', id: 2, group: 'Fiber · Powder', name: 'Tamarind Fiber', bg: '#f3d3b0', ink: '#6b3a1c', liquid: '#b07a4e', grain: '#a87c55' },
    { key: 'tangerine', id: 10, group: 'Fiber · Powder', name: 'Tangerine Fiber', bg: '#ffd59e', ink: '#9a4306', liquid: '#f39a2c', grain: '#f2bf7a' },
    { key: 'lime', id: 16, group: 'Fiber · Powder', name: 'Lime Fiber', bg: '#e6f47a', ink: '#4f6b12', liquid: '#f1d43a', grain: '#d9e46a' },
    { key: 'strawberry', id: 53, group: 'Fiber · Powder', name: 'Strawberry Fiber Kick', bg: '#ffd0d3', ink: '#a8102f', liquid: '#e2344e', grain: '#efa0aa' },
    { key: 'lychee', id: 37, group: 'Skin · Powder', name: 'Collagen Lychee Snow Plus', bg: '#ffc4d0', ink: '#a5243f', liquid: '#f2a9b4', grain: '#fbe9ec' },
  ];
  const hero = document.querySelector('.hero');
  if (!hero) return;
  const small = matchMedia('(max-width: 760px)').matches;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const sfx = small ? '-s' : '';
  const stage = hero.querySelector('.hero__stage');
  const bench = hero.querySelector('.bench');
  const sachet = hero.querySelector('.sachet');
  const body = sachet.querySelector('.sachet__body');
  const strip = sachet.querySelector('.sachet__strip');
  const splash = hero.querySelector('.bench__splash');
  const drink = hero.querySelector('.glass__drink');
  const texts = [...hero.querySelectorAll('.hero__text')];
  const tag = hero.querySelector('.tag');
  const picks = [...hero.querySelectorAll('.flavours button')];
  const cv = hero.querySelector('.hero__fx');
  const ctx = cv.getContext('2d');
  if (small) hero.querySelectorAll('.glass img[data-small]').forEach(i => { i.src = i.dataset.small; });

  const sprites = {};
  P.forEach(p => {
    [`${p.key}-sachet`, `${p.key}-splash`, `glass-${p.key}-drink${sfx}`].forEach(f => { const i = new Image(); i.src = `assets/img/hero/${f}.webp`; });
    const g = new Image(); g.src = `assets/img/hero/grains-${p.key}.webp`; sprites[p.key] = g;
  });

  const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
  const seg = (s, a, b) => clamp((s - a) / (b - a));
  const ease = t => t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
  const out = t => 1 - Math.pow(1 - t, 3);
  const back = t => { const c = 1.7; return 1 + (c + 1) * Math.pow(t - 1, 3) + c * Math.pow(t - 1, 2); };
  const lerp = (a, b, t) => a + (b - a) * t;

  let cur = 0, pours = 0;
  function setProduct(i) {
    cur = i; const p = P[i]; landT = null; grains.length = 0; bubbles.length = 0; puffs.length = 0;
    hero.style.setProperty('--accent-bg', p.bg);
    hero.style.setProperty('--accent-ink', p.ink);
    hero.style.setProperty('--accent', p.liquid);
    body.src = strip.src = `assets/img/hero/${p.key}-sachet.webp`;
    splash.src = `assets/img/hero/${p.key}-splash.webp`;
    drink.src = `assets/img/hero/glass-${p.key}-drink${sfx}.webp`;
    tag.href = 'product.html?id=' + p.id;
    tag.querySelector('.tag__no b').textContent = String(p.id).padStart(2, '0');
    tag.querySelector('.tag__group').textContent = p.group;
    tag.querySelector('.tag__name').textContent = p.name;
    picks.forEach((b, k) => { b.setAttribute('aria-checked', k === i); b.tabIndex = k === i ? 0 : -1; });
    texts.forEach((t, k) => t.classList.toggle('is-on', k === pours % texts.length));
    pours++;
  }

  let W = 0, H = 0, bw = 0, bh = 0, bx = 0, by = 0, sw = 0, sh = 0, dpr = 1;
  function measure() {
    dpr = Math.min(small ? 1.5 : 2, devicePixelRatio || 1);
    W = stage.clientWidth; H = stage.clientHeight;
    cv.width = W * dpr; cv.height = H * dpr;
    const r = bench.getBoundingClientRect(), sr = stage.getBoundingClientRect();
    bw = r.width; bh = r.height; bx = r.left - sr.left; by = r.top - sr.top;
    sw = sachet.offsetWidth; sh = sw * 612 / 421;
  }
  // Sachet geometry: the torn edge sits 37% of the sachet's height above its centre (the strip clip starts at 13%).
  const EDGE = -0.37;
  const rot = (x, y, t) => ({ x: x * Math.cos(t) - y * Math.sin(t), y: x * Math.sin(t) + y * Math.cos(t) });
  // the corner of the torn edge that hangs lowest when tipped
  function lowCorner(t) { const a = rot(0.5 * sw, EDGE * sh, t), b = rot(-0.5 * sw, EDGE * sh, t); return a.y > b.y ? { u: 1, ...a } : { u: -1, ...b }; }
  function pourPose(t) { const k = lowCorner(t); return { x: bw * 0.5 - k.x, y: bh * 0.0 - k.y }; }

  let empty = 0, landT = null, cen = { x: 0, y: 0 }, theta = 0, pourAmt = 0, fizz = 0, burst = 0, burstDone = false, shake = 0;
  const grains = [], drops = [], bubbles = [], puffs = [];

  function frame(s) {
    const arrive = out(seg(s, 0, .12)), tear = seg(s, .12, .2), tip = ease(seg(s, .18, .3)), leave = ease(seg(s, .56, .66));
    const home = { x: bw * 1.05, y: bh * 0.12 }, T = -1.95, pose = pourPose(T);
    pourAmt = seg(s, .31, .34) * (1 - seg(s, .52, .57));
    shake = Math.sin(s * 150) * pourAmt;
    theta = lerp(0.18, T, tip) + shake * 0.07 - leave * 0.6;
    const cx = lerp(home.x, pose.x, tip) + (1 - arrive) * bw * 0.9 + leave * bw * 1.2 + shake * 3;
    const cy = lerp(home.y, pose.y, tip) - (1 - arrive) * bh * 0.6 - leave * bh * 0.9 - Math.abs(shake) * 4;
    cen.x = bx + cx; cen.y = by + cy;
    sachet.style.transform = `translate3d(${cx - sw / 2}px,${cy - sh / 2}px,0) rotate(${theta}rad)`;
    sachet.style.opacity = (s <= 0 ? 0 : 1 - leave).toFixed(3);
    strip.style.transform = `translate3d(${tear * sw * 0.9}px,${-tear * sh * 0.5}px,0) rotate(${tear * 0.9}rad)`;
    strip.style.opacity = (1 - seg(s, .17, .24)).toFixed(3);
    hero.style.setProperty('--drink', (1 - ease(seg(s, .9, 1))).toFixed(3));
    empty = seg(s, .9, .97);
    const rise = seg(s, .62, .82), fade = seg(s, .9, 1), k = back(rise);
    splash.style.opacity = (Math.min(1, rise * 2) * (1 - fade)).toFixed(3);
    splash.style.transform = `translate(-50%,${-50 - 6 * k}%) scale(${(0.15 + 0.85 * k) * (1 + 0.15 * fade)}) rotate(${(1 - k) * -40}deg)`;
    if (rise > 0.05 && !burstDone) { burstDone = true; burst = 1; }
  }

  // one bubble drawn once, then stamped: clear middle, bright rim, a highlight up-left and a small glint down-right
  const bub = document.createElement('canvas'); bub.width = bub.height = 64;
  (() => { const b = bub.getContext('2d'), g = b.createRadialGradient(32, 32, 0, 32, 32, 30);
    g.addColorStop(0, 'rgba(255,255,255,0.02)'); g.addColorStop(0.62, 'rgba(255,255,255,0.08)'); g.addColorStop(0.86, 'rgba(255,255,255,0.55)'); g.addColorStop(0.97, 'rgba(255,255,255,0.85)'); g.addColorStop(1, 'rgba(255,255,255,0)');
    b.fillStyle = g; b.beginPath(); b.arc(32, 32, 30, 0, 6.283); b.fill();
    b.fillStyle = 'rgba(255,255,255,0.95)'; b.beginPath(); b.ellipse(22, 20, 8, 5, -0.7, 0, 6.283); b.fill();
    b.fillStyle = 'rgba(255,255,255,0.6)'; b.beginPath(); b.ellipse(43, 44, 3.5, 2, -0.7, 0, 6.283); b.fill(); })();

  let lastCen = null;
  function fx(dt) {
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0); ctx.clearRect(0, 0, W, H);
    const p = P[cur], sp = sprites[p.key];
    // the liquid inside the glass, measured from the drink render: surface at 32% of the glass height, bottom at 82%, walls taper inward
    const inTop = by + bh * 0.325, inBot = by + bh * 0.815, rimY = by + bh * 0.06;
    const wallL = y => bx + bw * (0.14 + 0.05 * clamp((y - inTop) / (inBot - inTop))), wallR = y => bx + bw - (wallL(y) - bx);
    // colour starts only when the first grains reach the water, spreads down fast then slower, and the glass evens out last
    if (landT !== null) landT += dt;
    const lt = landT === null ? 0 : landT;
    hero.style.setProperty('--on', clamp(lt / 0.3).toFixed(3));
    hero.style.setProperty('--D', (95 * Math.sqrt(clamp(lt / 4.5))).toFixed(1));
    hero.style.setProperty('--even', ease(clamp((lt - 2) / 2.6)).toFixed(3));
    fizz = clamp((lt - 0.2) / 0.6) * (1 - clamp((lt - 4.5) / 2)) * (1 - empty);
    // the sachet's own velocity, so grains leave the torn edge moving with it
    const vel = lastCen ? { x: (cen.x - lastCen.x) / Math.max(dt, 1e-3), y: (cen.y - lastCen.y) / Math.max(dt, 1e-3) } : { x: 0, y: 0 };
    lastCen = { x: cen.x, y: cen.y };
    const low = lowCorner(theta), out = rot(0, -1, theta);
    const flow = pourAmt * (0.55 + 0.45 * Math.max(0, shake / Math.max(pourAmt, 1e-3)));
    for (let n = 0; n < flow * (small ? 260 : 560) * dt; n++) {
      const u = 0.25 + 0.75 * Math.random(), e = rot(low.u * u * 0.5 * sw, EDGE * sh, theta);
      grains.push({ x: cen.x + e.x, y: cen.y + e.y, vx: clamp(vel.x * 0.2, -40, 40) + out.x * 30 + (Math.random() - .5) * 18, vy: Math.max(0, vel.y * 0.2) + out.y * 30 + 20 + Math.random() * 30,
        r: Math.random() < .35 ? 0.7 + Math.random() * 1.2 : 2.5 + Math.random() * 4.5, f: (Math.random() * 12) | 0, a: Math.random() * 6.28, life: 0 });
    }
    for (let g = grains.length - 1; g >= 0; g--) {
      const q = grains[g]; q.vy += 1400 * dt; q.x += q.vx * dt; q.y += q.vy * dt; q.life += dt;
      const inGlass = q.x > wallL(inTop) + 4 && q.x < wallR(inTop) - 4;
      // a grain that would miss the glass is dropped at the rim rather than falling past it
      if (!inGlass && q.y > rimY) { grains.splice(g, 1); continue; }
      if ((inGlass && q.y > inTop) || q.life > 2.5) {
        grains.splice(g, 1);
        if (inGlass) { if (landT === null) landT = 0; if (puffs.length < 90 && Math.random() < .5) puffs.push({ x: q.x, y: inTop + 3 + Math.random() * 6, r: 2 + Math.random() * 3, life: 0, vy: 8 + Math.random() * 18 }); }
        continue;
      }
      if (q.r < 2 || !sp.complete) { ctx.fillStyle = p.grain; ctx.beginPath(); ctx.arc(q.x, q.y, q.r, 0, 6.283); ctx.fill(); }
      else { ctx.save(); ctx.translate(q.x, q.y); ctx.rotate(q.a + q.life * 6); ctx.drawImage(sp, q.f * 64, 0, 64, 64, -q.r, -q.r, q.r * 2, q.r * 2); ctx.restore(); }
    }
    // each grain that lands blooms into a soft cloud that sinks and spreads as it dissolves
    for (let k = puffs.length - 1; k >= 0; k--) {
      const q = puffs[k]; q.life += dt; q.y += q.vy * dt; q.r += 9 * dt;
      q.x = clamp(q.x, wallL(q.y) + q.r, wallR(q.y) - q.r);
      if (q.life > 1.4 || q.y > inBot - q.r) { puffs.splice(k, 1); continue; }
      const g = ctx.createRadialGradient(q.x, q.y, 0, q.x, q.y, q.r);
      g.addColorStop(0, p.liquid); g.addColorStop(1, p.liquid + '00');
      ctx.globalAlpha = 0.35 * (1 - q.life / 1.4); ctx.fillStyle = g; ctx.beginPath(); ctx.arc(q.x, q.y, q.r, 0, 6.283); ctx.fill();
    }
    ctx.globalAlpha = 1;
    // bubbles: bigger ones rise faster, they wobble, and they pop in a small ring at the surface
    for (let n = 0; n < fizz * (small ? 22 : 34) * dt; n++) {
      const r = Math.random() < .8 ? 1.2 + Math.random() * 2.2 : 3 + Math.random() * 2.5, wall = Math.random() < .3;
      const y = inBot - r - 2 - Math.random() * 40, L = wallL(y) + r + 3, R = wallR(y) - r - 3;
      const x = wall ? (Math.random() < .5 ? L + Math.random() * 4 : R - Math.random() * 4) : L + 6 + Math.random() * (R - L - 12);
      bubbles.push({ x, y, r, v: 16 + r * 7 + Math.random() * 10, w: Math.random() * 6.28, hold: wall ? 0.5 + Math.random() * 1.5 : 0, pop: -1 });
    }
    for (let b = bubbles.length - 1; b >= 0; b--) {
      const q = bubbles[b];
      if (q.pop >= 0) {
        q.pop += dt; if (q.pop > 0.25) { bubbles.splice(b, 1); continue; }
        ctx.globalAlpha = 0.7 * (1 - q.pop / 0.25); ctx.strokeStyle = '#fff'; ctx.lineWidth = 1;
        const pr = Math.min(q.r + q.pop * 24, q.x - wallL(inTop) - 1, wallR(inTop) - q.x - 1); ctx.beginPath(); ctx.ellipse(q.x, inTop + 2, Math.max(1, pr), Math.max(1, pr) * 0.3, 0, 0, 6.283); ctx.stroke(); continue;
      }
      if (q.hold > 0) q.hold -= dt; else { q.y -= q.v * dt; q.w += dt * 3; q.x += Math.sin(q.w) * q.r * 0.05; }
      q.x = clamp(q.x, wallL(q.y) + q.r + 2, wallR(q.y) - q.r - 2);
      if (q.y < inTop + q.r + 1) { q.pop = 0; continue; }
      const sx = 1 + Math.sin(q.w * 1.7) * 0.06, d = q.r * 2;
      ctx.globalAlpha = Math.min(0.9, (q.y - inTop) / 20) * (1 - empty);
      ctx.drawImage(bub, q.x - q.r * sx, q.y - q.r / sx, d * sx, d / sx);
    }
    ctx.globalAlpha = 1;
    if (burst) {
      for (let n = 0; n < (small ? 26 : 46); n++) { const a = -Math.PI / 2 + (Math.random() - .5) * 2.6, v = 380 + Math.random() * 520;
        drops.push({ x: bx + bw * 0.5, y: by + bh * 0.06, vx: Math.cos(a) * v, vy: Math.sin(a) * v, r: 3 + Math.random() * 9, life: 0 }); }
      burst = 0;
    }
    for (let d = drops.length - 1; d >= 0; d--) {
      const q = drops[d]; q.vy += 1300 * dt; q.x += q.vx * dt; q.y += q.vy * dt; q.life += dt;
      if (q.y > H + 40 || q.life > 2.4) { drops.splice(d, 1); continue; }
      ctx.fillStyle = p.liquid; ctx.globalAlpha = Math.max(0, 1 - q.life / 2.4);
      ctx.beginPath(); ctx.ellipse(q.x, q.y, q.r, q.r * 1.25, Math.atan2(q.vy, q.vx), 0, 6.283); ctx.fill();
      ctx.fillStyle = '#fff'; ctx.beginPath(); ctx.arc(q.x - q.r * .3, q.y - q.r * .35, q.r * .28, 0, 6.283); ctx.fill(); ctx.globalAlpha = 1;
    }
  }

  // timeline: 0 -> HOLD pours the drink (3.4 s); HOLD -> 1 empties the glass (0.5 s) before the next flavour
  const HOLD = 0.86;
  let s = 0, target = HOLD, next = null, started = false;
  function choose(i) {
    if (i === cur && next === null && s >= HOLD - 0.001) return;
    next = i; target = 1;
  }
  picks.forEach((b, i) => {
    b.addEventListener('click', () => choose(i));
    b.addEventListener('keydown', e => {
      const d = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 }[e.key];
      if (d) { e.preventDefault(); const n = (i + d + picks.length) % picks.length; picks[n].focus(); choose(n); }
    });
  });

  window.__heroSet = (v, land) => { s = v; target = v; if (land !== undefined) landT = land; frame(v); };
  window.__heroPick = i => { setProduct(i); s = 0; target = HOLD; burstDone = false; frame(0); };
  measure(); addEventListener('resize', measure);
  setProduct(0);
  if (reduce) { s = target = HOLD; burstDone = true; frame(s); landT = 99; fx(0); return; }
  frame(0);

  let last = performance.now(), visible = true;
  new IntersectionObserver(e => { visible = e[0].isIntersecting; }).observe(hero);
  setTimeout(() => { started = true; }, 500);
  (function loop(t) {
    const dt = Math.min(0.05, (t - last) / 1000); last = t;
    if (visible && started && !window.__heroFrozen) {
      if (s < target) {
        s = Math.min(target, s + dt * (target === 1 ? 0.28 : 1 / 3.4));
        if (s >= 1 && next !== null) { setProduct(next); next = null; s = 0; target = HOLD; burstDone = false; }
      }
      frame(s);
    }
    if (visible) fx(dt);
    requestAnimationFrame(loop);
  })(last);
})();
