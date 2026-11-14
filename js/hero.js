(() => {
  const P = [
    { key: 'tamarind', no: '02', group: 'Fiber · Powder', name: 'Tamarind Fiber', href: 'products/tamarind-fiber.html',
      bg: '#ffd2a6', ink: '#7a3f22', liquid: '#e9a3a6', grain: '#b07a4e' },
    { key: 'lime', no: '16', group: 'Fiber · Powder', name: 'Lime Fiber', href: 'products/lime-fiber.html',
      bg: '#e6f47a', ink: '#4f6b12', liquid: '#f1d43a', grain: '#d9e46a' },
    { key: 'lychee', no: '37', group: 'Skin · Powder', name: 'Collagen Lychee Snow Plus', href: 'products/collagen-lychee-snow-plus.html',
      bg: '#ffc4d0', ink: '#a5243f', liquid: '#f2a9b4', grain: '#fbe9ec' },
  ];

  const hero = document.querySelector('.hero');
  const stage = hero.querySelector('.hero__stage');
  const bench = hero.querySelector('.bench');
  const sachet = hero.querySelector('.sachet');
  const body = sachet.querySelector('.sachet__body');
  const strip = sachet.querySelector('.sachet__strip');
  const splash = hero.querySelector('.bench__splash');
  const drinks = [...hero.querySelectorAll('.glass__drink')];
  const sprites = {}; P.forEach(p => { const i = new Image(); i.src = `assets/img/hero/grains-${p.key}.webp`; sprites[p.key] = i; });
  const lines = [...hero.querySelectorAll('.hero__line')];
  const texts = [...hero.querySelectorAll('.hero__text')];
  const tubes = [...hero.querySelectorAll('.tubes li')];
  const tag = hero.querySelector('.tag');
  const cv = hero.querySelector('.hero__fx');
  const ctx = cv.getContext('2d');
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // preload
  P.forEach(p => { ['sachet', 'splash'].forEach(k => { const i = new Image(); i.src = `assets/img/hero/${p.key}-${k}.webp`; }); });

  const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
  const seg = (s, a, b) => clamp((s - a) / (b - a));
  const ease = t => t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
  const out = t => 1 - Math.pow(1 - t, 3);
  const back = t => { const c = 1.7; return 1 + (c + 1) * Math.pow(t - 1, 3) + c * Math.pow(t - 1, 2); };
  const lerp = (a, b, t) => a + (b - a) * t;

  let active = -1;
  function setProduct(i) {
    if (i === active) return;
    active = i;
    const p = P[i];
    hero.style.setProperty('--accent-bg', p.bg);
    hero.style.setProperty('--accent-ink', p.ink);
    hero.style.setProperty('--accent', p.liquid);
    body.src = strip.src = `assets/img/hero/${p.key}-sachet.webp`;
    splash.src = `assets/img/hero/${p.key}-splash.webp`;
    drinks[0].src = `assets/img/hero/glass-${p.key}-tint.jpg`; drinks[1].src = `assets/img/hero/glass-${p.key}-shine.jpg`;
    tag.href = p.href;
    tag.querySelector('.tag__no b').textContent = p.no;
    tag.querySelector('.tag__group').textContent = p.group;
    tag.querySelector('.tag__name').textContent = p.name;
    texts.forEach((t, k) => t.classList.toggle('is-on', k === i));
  }

  // geometry, in px relative to the bench box
  let W = 0, H = 0, bw = 0, bh = 0, bx = 0, by = 0, sw = 0, sh = 0, dpr = 1;
  function measure() {
    dpr = Math.min(2, devicePixelRatio || 1);
    W = stage.clientWidth; H = stage.clientHeight;
    cv.width = W * dpr; cv.height = H * dpr;
    const r = bench.getBoundingClientRect(), sr = stage.getBoundingClientRect();
    bw = r.width; bh = r.height; bx = r.left - sr.left; by = r.top - sr.top;
    sw = sachet.offsetWidth; sh = sw * 612 / 421;
  }

  // where the sachet's centre has to sit so its torn mouth is over the glass
  function pourPose(theta) {
    const mx = 0, my = -0.42 * sh;                 // mouth, relative to sachet centre
    const c = Math.cos(theta), s = Math.sin(theta);
    const rx = mx * c - my * s, ry = mx * s + my * c;
    const tx = bw * 0.5, ty = bh * 0.0;           // target: just above the glass rim
    return { x: tx - rx, y: ty - ry };
  }

  let mouth = { x: 0, y: 0 }, pourAmt = 0, fizz = 0, burst = 0, lastBurstSeg = -1;
  const grains = [], drops = [], bubbles = [];

  function frame(progress) {
    const n = P.length;
    const f = clamp(progress) * n;
    const i = Math.min(n - 1, Math.floor(f));
    const s = f - i;
    setProduct(i);

    // giant type drifts
    lines.forEach((l, k) => { l.style.transform = `translate3d(${(+l.dataset.dir) * (progress * 18 - 4)}vw,0,0)`; });

    // sachet: arrive, tear, tip, pour, leave
    const arrive = out(seg(s, 0, .12));
    const tear = seg(s, .12, .2);
    const tip = ease(seg(s, .18, .3));
    const leave = ease(seg(s, .56, .66));
    const home = { x: bw * 1.05, y: bh * 0.12 };
    const theta = lerp(0.18, -1.95, tip);
    const pose = pourPose(-1.95);
    let cx = lerp(home.x, pose.x, tip), cy = lerp(home.y, pose.y, tip);
    cx += (1 - arrive) * bw * 0.9 + leave * bw * 1.2;
    cy += -(1 - arrive) * bh * 0.6 - leave * bh * 0.9;
    const left = cx - sw / 2, top = cy - sh / 2;
    sachet.style.left = '0px'; sachet.style.top = '0px';
    sachet.style.transform = `translate3d(${left}px,${top}px,0) rotate(${theta - leave * 0.6}rad)`;
    sachet.style.opacity = (1 - leave).toFixed(3);
    strip.style.transform = `translate3d(${tear * sw * 0.9}px,${-tear * sh * 0.5}px,0) rotate(${tear * 0.9}rad)`;
    strip.style.opacity = (1 - seg(s, .17, .24)).toFixed(3);

    // mouth position in stage px for the powder stream
    const my = -0.42 * sh, c = Math.cos(theta), sn = Math.sin(theta);
    mouth.x = bx + cx + (-my * sn); mouth.y = by + cy + (my * c);
    pourAmt = seg(s, .28, .34) * (1 - seg(s, .5, .56));

    // liquid takes the colour from the top down
    const mix = ease(seg(s, .3, .62));
    const drain = ease(seg(s, .93, 1));
    hero.style.setProperty('--mix', (8 + 98 * mix).toFixed(1));
    hero.style.setProperty('--drink', (1 - drain).toFixed(3));

    fizz = seg(s, .36, .6) * (1 - seg(s, .86, .95));

    // splash ring rises out of the glass
    const rise = seg(s, .62, .82), fade = seg(s, .92, 1);
    const k = back(rise);
    splash.style.opacity = (Math.min(1, rise * 2) * (1 - fade)).toFixed(3);
    splash.style.transform = `translate(-50%,${-50 - 6 * k}%) scale(${(0.15 + 0.85 * k) * (1 + 0.15 * fade)}) rotate(${(1 - k) * -40}deg)`;
    if (rise > 0.05 && rise < 0.5 && lastBurstSeg !== i) { lastBurstSeg = i; burst = 1; }
    if (s < 0.5) lastBurstSeg = -1;

    tubes.forEach((t, k2) => t.style.setProperty('--fill', k2 < i ? 1 : k2 === i ? s.toFixed(3) : 0));
  }

  // canvas: powder stream and juice drops (time based, scroll sets the rate)
  function fx(dt) {
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.clearRect(0, 0, W, H);
    const p = P[active] || P[0];
    const surface = { x: bx + bw * 0.5, y: by + bh * 0.30 };
    const rate = pourAmt * 520 * dt;
    for (let n = 0; n < rate; n++) {
      grains.push({ x: mouth.x + (Math.random() - .5) * 10, y: mouth.y, vx: (surface.x - mouth.x) * 0.9 + (Math.random() - .5) * 46, vy: 40 + Math.random() * 40,
        r: Math.random() < .35 ? 0.7 + Math.random() * 1.2 : 2.5 + Math.random() * 4.5, f: (Math.random() * 12) | 0, a: Math.random() * 6.28, life: 0 });
    }
    for (let g = grains.length - 1; g >= 0; g--) {
      const q = grains[g];
      q.vy += 1400 * dt; q.x += q.vx * dt; q.y += q.vy * dt; q.vx *= 0.96; q.life += dt;
      if (q.y > surface.y + Math.random() * 20 || q.life > 2) { grains.splice(g, 1); continue; }
      const sp = sprites[p.key];
      if (q.r < 2 || !sp.complete) { ctx.fillStyle = p.grain; ctx.beginPath(); ctx.arc(q.x, q.y, q.r, 0, 6.283); ctx.fill(); }
      else { ctx.save(); ctx.translate(q.x, q.y); ctx.rotate(q.a + q.life * 6); ctx.drawImage(sp, q.f * 64, 0, 64, 64, -q.r, -q.r, q.r * 2, q.r * 2); ctx.restore(); }
    }
    // bubbles rising inside the glass
    const inL = bx + bw * 0.16, inR = bx + bw * 0.84, inTop = by + bh * 0.33, inBot = by + bh * 0.84;
    for (let n = 0; n < fizz * 34 * dt; n++) bubbles.push({ x: inL + Math.random() * (inR - inL), y: inBot, r: 1 + Math.random() * 3, v: 60 + Math.random() * 90, w: Math.random() * 6.28 });
    ctx.strokeStyle = 'rgba(255,255,255,.55)'; ctx.lineWidth = 1;
    for (let b = bubbles.length - 1; b >= 0; b--) {
      const q = bubbles[b]; q.y -= q.v * dt; q.w += dt * 4; q.x += Math.sin(q.w) * .4;
      if (q.y < inTop) { bubbles.splice(b, 1); continue; }
      ctx.globalAlpha = Math.min(.8, (q.y - inTop) / 40);
      ctx.beginPath(); ctx.arc(q.x, q.y, q.r, 0, 6.283); ctx.stroke();
      ctx.fillStyle = 'rgba(255,255,255,.5)'; ctx.beginPath(); ctx.arc(q.x - q.r * .35, q.y - q.r * .35, q.r * .3, 0, 6.283); ctx.fill();
    }
    ctx.globalAlpha = 1;
    if (burst > 0) {
      for (let n = 0; n < 46; n++) {
        const a = -Math.PI / 2 + (Math.random() - .5) * 2.6, v = 380 + Math.random() * 520;
        drops.push({ x: bx + bw * 0.5, y: by + bh * 0.06, vx: Math.cos(a) * v, vy: Math.sin(a) * v, r: 3 + Math.random() * 9, life: 0 });
      }
      burst = 0;
    }
    for (let d = drops.length - 1; d >= 0; d--) {
      const q = drops[d];
      q.vy += 1300 * dt; q.x += q.vx * dt; q.y += q.vy * dt; q.life += dt;
      if (q.y > H + 40 || q.life > 2.4) { drops.splice(d, 1); continue; }
      ctx.fillStyle = p.liquid; ctx.globalAlpha = Math.max(0, 1 - q.life / 2.4);
      ctx.beginPath(); ctx.ellipse(q.x, q.y, q.r, q.r * 1.25, Math.atan2(q.vy, q.vx), 0, 6.283); ctx.fill();
      ctx.fillStyle = '#fff'; ctx.beginPath(); ctx.arc(q.x - q.r * .3, q.y - q.r * .35, q.r * .28, 0, 6.283); ctx.fill();
      ctx.globalAlpha = 1;
    }
  }

  function progress() {
    const r = hero.getBoundingClientRect();
    const span = hero.offsetHeight - innerHeight;
    return span > 0 ? clamp(-r.top / span) : 0;
  }

  window.__heroSet = v => { frame(v); };   // used by the preview renderer

  measure();
  addEventListener('resize', measure);
  if (reduce) { frame(0.27); return; }
  let last = performance.now();
  (function loop(t) {
    const dt = Math.min(0.05, (t - last) / 1000); last = t;
    if (!window.__heroFrozen) frame(progress());
    fx(dt);
    requestAnimationFrame(loop);
  })(last);
})();
