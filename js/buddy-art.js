/* Nong Posh art: a round lab flask with a face, built on the Nong Tape rig from the Apex Slim site.
   Poses blend between each other, faces cross-fade, and the drink inside stays level and sloshes when he tilts or gets carried. */
const P = { navy: '#26235e', navyL: '#4b47a3', ink: '#1b1946', glass: '#eef3ff', glassD: '#c9d3ef', cap: '#f05a28', capD: '#b83a14',
  blush: '#ff8fa3', star: '#ffd23f', mint: '#7fe0c9' };

const TILT = { think: -8, curious: 10, sleep: 14, shy: -6, runA: 7, runB: 4, splat: 0 };
/* right arm and left arm, as paths from the shoulder */
const RIGHTS = {
  idle: 'M154 118q14 8 12 26', wave: 'M154 110q18-8 16-38', point: 'M154 114h44', stir: 'M154 116q16-2 22-16',
  cheer: 'M154 106q16-12 14-42', cover: 'M152 104c-6-22-30-20-40-10', hop: 'M154 116q18 2 20 18',
  think: 'M154 120c12 14 0 26-24 20', heart: 'M154 108c12-10 10-26 0-34',
  runA: 'M154 118q14 4 22 18', runB: 'M154 116q8 12-6 24', splat: 'M148 156c22 3 46 5 70 6',
  sleep: 'M154 120q14 6 8 22', shy: 'M154 116q8 10-8 24', curious: 'M154 118q16 2 18 20', sachet: 'M154 112q18-4 22-22'
};
const LEFTS = {
  idle: 'M46 118q-14 8-12 26', wave: 'M46 118q-14 8-12 26', point: 'M46 118q-14 8-12 26', stir: 'M46 118q-14 8-12 26',
  cheer: 'M46 106q-16-12-14-42', cover: 'M48 104c6-22 30-20 40-10', hop: 'M46 116q-18 2-20 16', think: 'M46 118q-14 8-12 26',
  runA: 'M46 118q-18 4-24 16', runB: 'M46 118q-4 14 6 24', splat: 'M136 156q26 3 56 4',
  heart: 'M46 108c-12-10-10-26 0-34', sleep: 'M46 122q-12 8-8 20', shy: 'M48 116q12 16 34 16', curious: 'M46 118q-14 8-12 26', sachet: 'M46 118q-14 8-12 26'
};

/* ---------- path maths: every arm becomes 3 cubic segments so any pose can blend into any other ---------- */
function toCubics(d) {
  let x = 0, y = 0; const segs = []; let start = [0, 0];
  for (const m of d.matchAll(/([MmCcQqHh])([^MmCcQqHh]*)/g)) {
    const n = m[2].trim().split(/[\s,]+|(?=-)/).filter(Boolean).map(Number), c = m[1];
    if (c === 'M') { x = n[0]; y = n[1]; start = [x, y]; }
    else if (c === 'h') { const ex = x + n[0]; segs.push([x + n[0] / 3, y, x + 2 * n[0] / 3, y, ex, y]); x = ex; }
    else if (c === 'c') for (let i = 0; i + 6 <= n.length; i += 6) { segs.push([x + n[i], y + n[i + 1], x + n[i + 2], y + n[i + 3], x + n[i + 4], y + n[i + 5]]); x += n[i + 4]; y += n[i + 5]; }
    else if (c === 'q') for (let i = 0; i + 4 <= n.length; i += 4) {
      const qx = x + n[i], qy = y + n[i + 1], ex = x + n[i + 2], ey = y + n[i + 3];
      segs.push([x + 2 / 3 * (qx - x), y + 2 / 3 * (qy - y), ex + 2 / 3 * (qx - ex), ey + 2 / 3 * (qy - ey), ex, ey]); x = ex; y = ey;
    }
  }
  const pts = [start];
  segs.forEach(s => pts.push([s[0], s[1]], [s[2], s[3]], [s[4], s[5]]));
  while (pts.length < 10) {
    let best = 0, bl = -1;
    for (let i = 0; i + 3 < pts.length; i += 3) { const l = Math.hypot(pts[i + 3][0] - pts[i][0], pts[i + 3][1] - pts[i][1]); if (l > bl) { bl = l; best = i; } }
    const [p0, p1, p2, p3] = pts.slice(best, best + 4), mid = (a, b) => [(a[0] + b[0]) / 2, (a[1] + b[1]) / 2];
    const a = mid(p0, p1), b = mid(p1, p2), c = mid(p2, p3), d2 = mid(a, b), e = mid(b, c), f = mid(d2, e);
    pts.splice(best + 1, 2, a, d2, f, e, c);
  }
  return pts;
}
const pathOf = pts => 'M' + pts[0].map(v => v.toFixed(1)).join(' ') + 'C' + pts.slice(1).map(p => p.map(v => v.toFixed(1)).join(' ')).join(' ');
const CUBE = {};
const cubes = pose => CUBE[pose] || (CUBE[pose] = { right: toCubics(RIGHTS[pose] || RIGHTS.idle), left: toCubics(LEFTS[pose] || LEFTS.idle) });

/* ---------- face ---------- */
const EY = 98;
function eyeOpen(x, y, s, sparkle) {
  return `<ellipse cx="${x}" cy="${y}" rx="${8.5 * s}" ry="${10.5 * s}" fill="${P.ink}"/>
    <circle cx="${x + 3 * s}" cy="${y - 4 * s}" r="${3.4 * s}" fill="#fff"/><circle cx="${x - 2.8 * s}" cy="${y + 4 * s}" r="${1.6 * s}" fill="#fff"/>
    ${sparkle ? `<path transform="translate(${x - 3} ${y - 1}) scale(.5)" d="M0-9 2-2 9 0 2 2 0 9-2 2-9 0-2-2z" fill="#fff"/>` : ''}`;
}
function eye(expr, x, side) {
  const y = EY;
  switch (expr) {
    case 'blink': case 'proud': case 'shy': case 'giggle':
      return `<path d="M${x - 9} ${y + 1}q9 ${expr === 'shy' ? 6 : -9} 18 0" fill="none" stroke="${P.ink}" stroke-width="4.5" stroke-linecap="round"/>`;
    case 'wink': return side === 'L' ? `<path d="M${x - 9} ${y + 1}q9 -9 18 0" fill="none" stroke="${P.ink}" stroke-width="4.5" stroke-linecap="round"/>` : eyeOpen(x, y, 1);
    case 'oops': return side === 'L' ? `<path d="M${x - 8} ${y - 6}l12 6-12 6" fill="none" stroke="${P.ink}" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>` : eyeOpen(x, y, 1);
    case 'sleepy': return `<path d="M${x - 9} ${y}h18" stroke="${P.ink}" stroke-width="4.5" stroke-linecap="round"/><path d="M${x - 8} ${y + 1}q8 7 16 0" fill="${P.ink}"/>`;
    case 'love': return `<path transform="translate(${x} ${y})" d="M0 9C-14 0-11-11-5-11c3 0 5 2 5 4 0-2 2-4 5-4 6 0 9 11-5 20z" fill="#E8457A"/><circle cx="${x - 4}" cy="${y - 5}" r="2.2" fill="#fff"/>`;
    case 'excited': return eyeOpen(x, y, 1.18, true);
    case 'dizzy': return `<path d="M${x} ${y}m-2 0a2 2 0 1 1 4 0a4 4 0 1 1 -8 0a6 6 0 1 1 12 0a8 8 0 1 1 -16 0" fill="none" stroke="${P.ink}" stroke-width="2.6" stroke-linecap="round"/>`;
    case 'surprised': return `<circle cx="${x}" cy="${y}" r="10.5" fill="#fff" stroke="${P.ink}" stroke-width="3"/><circle cx="${x}" cy="${y}" r="5" fill="${P.ink}"/>`;
    case 'thinking': return eyeOpen(x - 2, y - 3, 0.95);
    case 'yum': return `<path d="M${x - 9} ${y + 2}q9 -10 18 0" fill="none" stroke="${P.ink}" stroke-width="4.5" stroke-linecap="round"/>`;
    default: return eyeOpen(x, y, 1);
  }
}
function mouthOf(expr) {
  const mx = 100, my = 117;
  return {
    happy: `<path d="M${mx - 9} ${my}q9 9 18 0" fill="none" stroke="${P.ink}" stroke-width="4" stroke-linecap="round"/>`,
    blink: `<path d="M${mx - 9} ${my}q9 9 18 0" fill="none" stroke="${P.ink}" stroke-width="4" stroke-linecap="round"/>`,
    wink: `<path d="M${mx - 11} ${my - 1}q11 13 22 0z" fill="${P.ink}"/><path d="M${mx - 6} ${my + 5}q6 4 12 0" fill="#F27A9B"/>`,
    excited: `<path d="M${mx - 12} ${my - 2}q12 18 24 0z" fill="${P.ink}"/><path d="M${mx - 7} ${my + 7}q7 5 14 0" fill="#F27A9B"/>`,
    proud: `<path d="M${mx - 11} ${my - 1}q11 12 22 0" fill="none" stroke="${P.ink}" stroke-width="4" stroke-linecap="round"/>`,
    curious: `<ellipse cx="${mx + 2}" cy="${my + 2}" rx="4" ry="5" fill="${P.ink}"/>`,
    thinking: `<path d="M${mx - 8} ${my + 2}q4-4 8 0t8 0" fill="none" stroke="${P.ink}" stroke-width="3.5" stroke-linecap="round"/>`,
    sleepy: `<ellipse cx="${mx}" cy="${my + 2}" rx="3.5" ry="2.5" fill="${P.ink}"/>`,
    surprised: `<ellipse cx="${mx}" cy="${my + 3}" rx="6" ry="8" fill="${P.ink}"/>`,
    shy: `<path d="M${mx - 7} ${my + 1}q3.5 3 7 0t7 0" fill="none" stroke="${P.ink}" stroke-width="3.5" stroke-linecap="round"/>`,
    love: `<path d="M${mx - 10} ${my - 1}q10 12 20 0z" fill="${P.ink}"/>`,
    oops: `<path d="M${mx - 11} ${my - 1}h22q-2 10-11 10t-11-10z" fill="${P.ink}"/><path d="M${mx - 9} ${my}h18v3h-18z" fill="#fff"/>`,
    giggle: `<path d="M${mx - 11} ${my - 2}q11 16 22 0z" fill="${P.ink}"/><path d="M${mx - 6} ${my + 5}q6 4 12 0" fill="#F27A9B"/>`,
    dizzy: `<path d="M${mx - 9} ${my + 2}q3-4 6 0t6 0t6 0" fill="none" stroke="${P.ink}" stroke-width="3.5" stroke-linecap="round"/>`,
    yum: `<path d="M${mx - 10} ${my - 1}q10 11 20 0" fill="none" stroke="${P.ink}" stroke-width="4" stroke-linecap="round"/><path d="M${mx + 2} ${my + 3}q5 7 9 0" fill="#F27A9B" stroke="${P.ink}" stroke-width="2"/>`
  }[expr] || '';
}
const star = (x, y, s, c) => `<path transform="translate(${x} ${y}) scale(${s})" d="M0-8 2-2 8 0 2 2 0 8-2 2-8 0-2-2z" fill="${c}"/>`;
const EXTRAS = {
  oops: `<path d="M152 74q6 9 0 13-6-4 0-13z" fill="#9CD4F5"/>`,
  excited: star(36, 44, 1, P.star) + star(166, 58, .7, P.mint),
  love: `<path transform="translate(162 48) scale(.7)" d="M0 9C-14 0-11-11-5-11c3 0 5 2 5 4 0-2 2-4 5-4 6 0 9 11-5 20z" fill="#F59AB5"/>`,
  thinking: `<circle cx="150" cy="56" r="4" fill="${P.glassD}"/><circle cx="160" cy="42" r="6" fill="${P.glassD}"/>`,
  surprised: `<path d="M42 56l-8-6M38 68l-10 0M156 56l8-6" stroke="${P.cap}" stroke-width="3" stroke-linecap="round"/>`,
  giggle: `<path d="M152 62q8-4 12 2M156 74q8-2 11 4" fill="none" stroke="${P.cap}" stroke-width="3" stroke-linecap="round"/>`,
  dizzy: star(60, 44, .55, P.star) + star(140, 38, .45, P.mint) + star(100, 28, .4, P.cap),
  yum: star(40, 60, .6, P.star)
};
const BLUSH = { proud: .85, shy: 1, love: .9, excited: .7, giggle: 1, dizzy: .6, yum: .8 };
const face = expr => `<g class="bd-eyes">${eye(expr, 82, 'L')}${eye(expr, 118, 'R')}</g>${mouthOf(expr)}`;

/* legs are numbers (hip x, foot x, foot y for each leg) so they blend between poses */
const LEGS = {
  idle: [86, 84, 182, 114, 116, 182], hop: [84, 77, 175, 116, 123, 175], sleep: [80, 74, 174, 118, 126, 172],
  runA: [92, 108, 176, 110, 96, 183], runB: [92, 80, 183, 110, 124, 175], splat: [62, 14, 172, 68, 20, 178]
};
const legsOf = p => LEGS[p] || LEGS.idle;
const legPaths = L => {
  const f = n => n.toFixed(1);
  return [`M${f(L[0])} 164L${f(L[1])} ${f(L[2])}`, `M${f(L[3])} 164L${f(L[4])} ${f(L[5])}`,
    `M${f(L[1] - 8)} ${f(L[2] + 2)}h14`, `M${f(L[4] - 6)} ${f(L[5] + 2)}h14`];
};
const legs = pose => { const d = legPaths(legsOf(pose));
  return `<path data-k="legL" d="${d[0]}" stroke="${P.navy}" stroke-width="9" stroke-linecap="round"/><path data-k="legR" d="${d[1]}" stroke="${P.navy}" stroke-width="9" stroke-linecap="round"/>`
    + `<path data-k="footL" d="${d[2]}" stroke="${P.cap}" stroke-width="8" stroke-linecap="round"/><path data-k="footR" d="${d[3]}" stroke="${P.cap}" stroke-width="8" stroke-linecap="round"/>`; };
const LIFT = { hop: -14, splat: 6, runA: -5, runB: 0 };

/* a tiny Posh sachet he can hold up */
const SACHET = `<g transform="translate(176 76) rotate(18)"><rect x="-11" y="-15" width="22" height="30" rx="3" fill="#fff" stroke="${P.navy}" stroke-width="2.5"/>
  <rect x="-11" y="-15" width="22" height="9" rx="3" fill="${P.cap}"/><circle cx="0" cy="4" r="5.5" fill="var(--buddy-liq,#f39a2c)"/><path d="M-7 12h14" stroke="${P.navy}" stroke-width="2" stroke-linecap="round"/></g>`;
const SPOON = `<g transform="translate(176 100) rotate(-28)"><rect x="-2" y="-34" width="4" height="40" rx="2" fill="${P.glassD}" stroke="${P.navy}" stroke-width="1.5"/><ellipse cx="0" cy="-36" rx="5" ry="7" fill="${P.glassD}" stroke="${P.navy}" stroke-width="1.5"/></g>`;

/* the flask: neck 82 to 118 wide, bulb centred at (100,112) radius 54 */
const BODY = 'M84 34V62C58 70 46 92 46 112a54 54 0 0 0 108 0c0-20-12-42-38-50V34z';
let uid = 0;
function markup(id, size, s) {
  const c = cubes(s.pose), rp = pathOf(c.right), lp = pathOf(c.left), hand = c.left[9], rh = c.right[9];
  const hop = s.pose === 'hop', lift = LIFT[s.pose] || 0;
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="-15 0 230 200" width="${size}" height="${size * 200 / 230}" class="buddy-svg" overflow="visible">
  <defs>
    <clipPath id="${id}in"><path d="${BODY}"/></clipPath>
    <radialGradient id="${id}g" cx=".36" cy=".38" r=".75"><stop offset="0" stop-color="#fff"/><stop offset=".6" stop-color="${P.glass}"/><stop offset="1" stop-color="${P.glassD}"/></radialGradient>
    <linearGradient id="${id}l" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="var(--buddy-liq,#f39a2c)"/><stop offset="1" stop-color="var(--buddy-liq2,#d9700e)"/></linearGradient>
    <linearGradient id="${id}c" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ff8a5c"/><stop offset="1" stop-color="${P.capD}"/></linearGradient>
  </defs>
  <ellipse data-k="shadow" cx="100" cy="190" rx="${hop ? 34 : 44}" ry="7" fill="${P.ink}" opacity="${hop ? .08 : .14}"/>
  <g data-k="tilt" transform="translate(0 ${lift}) rotate(${TILT[s.pose] || 0} 100 112)">
    <g data-k="legs">${legs(s.pose)}</g>
    <path data-k="left" d="${lp}" fill="none" stroke="${P.navy}" stroke-width="8.5" stroke-linecap="round"/>
    <path data-k="right" d="${rp}" fill="none" stroke="${P.navy}" stroke-width="8.5" stroke-linecap="round"/>
    <g data-k="case">
      <path d="${BODY}" fill="url(#${id}g)"/>
      <g clip-path="url(#${id}in)"><g data-k="liq">
        <g data-k="wave"><path d="M-60 128q15-7 30 0t30 0t30 0t30 0t30 0t30 0t30 0t30 0t30 0V230h-270z" fill="url(#${id}l)"/>
        <path d="M-60 128q15-7 30 0t30 0t30 0t30 0t30 0t30 0t30 0t30 0t30 0" fill="none" stroke="#fff" stroke-opacity=".55" stroke-width="2.5"/></g>
        <g class="bd-fizz" fill="#fff" fill-opacity=".75"><circle cx="78" cy="156" r="3"/><circle cx="112" cy="160" r="2.2"/><circle cx="96" cy="150" r="1.8"/><circle cx="124" cy="146" r="2.6"/></g>
      </g></g>
      <path d="M58 86a50 50 0 0 1 26-20" fill="none" stroke="#fff" stroke-opacity=".9" stroke-width="6" stroke-linecap="round"/>
      <path d="M62 140a44 44 0 0 0 12 12" fill="none" stroke="#fff" stroke-opacity=".6" stroke-width="4" stroke-linecap="round"/>
      <path d="M90 40v18" stroke="#fff" stroke-opacity=".8" stroke-width="4" stroke-linecap="round"/>
      <path d="${BODY}" fill="none" stroke="${P.navy}" stroke-width="4.5" stroke-linejoin="round"/>
      <g data-k="blush" opacity="${BLUSH[s.expr] ?? .5}"><ellipse cx="72" cy="110" rx="8.5" ry="5" fill="${P.blush}"/><ellipse cx="128" cy="110" rx="8.5" ry="5" fill="${P.blush}"/></g>
      <g data-k="look"><g data-k="face">${face(s.expr)}</g></g>
      <g data-k="back" opacity="${s.pose === 'splat' ? 1 : 0}"><path d="${BODY}" fill="url(#${id}g)" stroke="${P.navy}" stroke-width="4.5"/></g>
    </g>
    <g data-k="cap"><rect x="77" y="18" width="46" height="20" rx="7" fill="url(#${id}c)" stroke="${P.navy}" stroke-width="4"/><path d="M84 24h12" stroke="#fff" stroke-opacity=".7" stroke-width="3" stroke-linecap="round"/></g>
    <circle data-k="hand" cx="${hand[0]}" cy="${hand[1]}" r="7.5" fill="${P.navyL}" stroke="${P.navy}" stroke-width="2"/>
    <circle data-k="rhand" cx="${rh[0]}" cy="${rh[1]}" r="7.5" fill="${P.navyL}" stroke="${P.navy}" stroke-width="2"/>
    <g data-k="sachet" opacity="${s.pose === 'sachet' ? 1 : 0}">${SACHET}</g>
    <g data-k="spoon" opacity="${s.pose === 'stir' ? 1 : 0}">${SPOON}</g>
  </g>
  <g data-k="extras">${EXTRAS[s.expr] || ''}</g>
</svg>`;
}

/* the live character */
export function createBuddy(el, size) {
  const id = 'pb' + (++uid);
  el.innerHTML = markup(id, size, { expr: 'happy', pose: 'idle' });
  const q = k => el.querySelector(`[data-k=${k}]`);
  const k = { shadow: q('shadow'), tilt: q('tilt'), legL: q('legL'), legR: q('legR'), footL: q('footL'), footR: q('footR'), left: q('left'), right: q('right'), blush: q('blush'),
    look: q('look'), face: q('face'), back: q('back'), case: q('case'), hand: q('hand'), rhand: q('rhand'), sachet: q('sachet'), spoon: q('spoon'), extras: q('extras'),
    liq: q('liq'), wave: q('wave'), cap: q('cap') };
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const num = (p, turn) => ({ tilt: TILT[p] || 0, lift: LIFT[p] || 0, sh: p === 'hop' ? 34 : p === 'splat' ? 58 : 44, sachet: p === 'sachet' ? 1 : 0, spoon: p === 'stir' ? 1 : 0,
    legs: legsOf(p), turn, back: p === 'splat' ? 1 : 0, flat: p === 'splat' ? 1 : 0 });
  let cur = { pose: 'idle', expr: 'happy', turn: 0, pts: cubes('idle'), n: num('idle', 0) };
  let from = null, to = null, t0 = 0, dur = 420, raf = 0;
  let look = [0, 0], lookT = [0, 0];
  // the drink: its angle chases "level" with a spring, so it sloshes past and settles
  let slosh = 0, sloshV = 0, ext = 0, capT = 0, capY = 0, waveX = 0, lastNow = performance.now();
  const ease = x => { const c = 1.25; return 1 + (c + 1) * Math.pow(x - 1, 3) + c * Math.pow(x - 1, 2); };
  const lerp = (a, b, t) => a + (b - a) * t;
  const lerpPts = (A, B, t) => A.map((p, i) => [lerp(p[0], B[i][0], t), lerp(p[1], B[i][1], t)]);

  function paint(pts, n) {
    k.left.setAttribute('d', pathOf(pts.left)); k.right.setAttribute('d', pathOf(pts.right));
    k.hand.setAttribute('cx', pts.left[9][0].toFixed(1)); k.hand.setAttribute('cy', pts.left[9][1].toFixed(1));
    k.rhand.setAttribute('cx', pts.right[9][0].toFixed(1)); k.rhand.setAttribute('cy', pts.right[9][1].toFixed(1));
    k.tilt.setAttribute('transform', `translate(0 ${n.lift.toFixed(1)}) rotate(${n.tilt.toFixed(1)} 100 112)`);
    k.shadow.setAttribute('rx', n.sh.toFixed(1));
    k.sachet.setAttribute('opacity', Math.max(0, Math.min(1, n.sachet)).toFixed(2));
    k.spoon.setAttribute('opacity', Math.max(0, Math.min(1, n.spoon)).toFixed(2));
    const tn = n.turn || 0;
    k.back.setAttribute('opacity', (n.back || 0).toFixed(2));
    const flat = n.flat || 0, fl = 1 - flat * 0.5, sx = 1 - tn * 0.07;
    k.case.setAttribute('transform', `translate(100 166) scale(${sx.toFixed(3)} ${fl.toFixed(3)}) translate(-100 -166)`);
    k.look.setAttribute('transform', `translate(${(look[0] * 4 + tn * 15 + 100).toFixed(2)} ${(look[1] * 3).toFixed(2)}) scale(${(1 - tn * 0.12).toFixed(3)} 1) translate(-100 0)`);
    k.blush.setAttribute('transform', `translate(${(tn * 11).toFixed(2)} 0)`);
    const d = legPaths(n.legs.map((v, i) => i % 3 === 2 ? v : v + tn * 6));
    k.legL.setAttribute('d', d[0]); k.legR.setAttribute('d', d[1]); k.footL.setAttribute('d', d[2]); k.footR.setAttribute('d', d[3]);
    // the drink keeps its surface level against his tilt and lean, plus the slosh
    k.liq.setAttribute('transform', `rotate(${(-(n.tilt + ext) + slosh).toFixed(2)} 100 128)`);
    k.wave.setAttribute('transform', `translate(${waveX.toFixed(1)} 0)`);
    k.cap.setAttribute('transform', `translate(0 ${capY.toFixed(1)}) rotate(${(capY * -0.9).toFixed(1)} 100 28)`);
  }
  function frame(now) {
    raf = 0; let busy = false;
    const dt = Math.min(0.05, (now - lastNow) / 1000); lastNow = now;
    if (to) {
      const x = Math.min(1, (now - t0) / dur), e = ease(x);
      cur.pts = { right: lerpPts(from.pts.right, to.pts.right, e), left: lerpPts(from.pts.left, to.pts.left, e) };
      const s1 = Math.min(1, x * 1.15);
      const prevTilt = cur.n.tilt;
      cur.n = { tilt: lerp(from.n.tilt, to.n.tilt, e), lift: lerp(from.n.lift, to.n.lift, e), sh: lerp(from.n.sh, to.n.sh, e),
        sachet: lerp(from.n.sachet, to.n.sachet, Math.min(1, x * 1.6)), spoon: lerp(from.n.spoon, to.n.spoon, Math.min(1, x * 1.6)),
        legs: from.n.legs.map((v, i) => lerp(v, to.n.legs[i], s1)), turn: lerp(from.n.turn, to.n.turn, s1),
        back: lerp(from.n.back || 0, to.n.back, Math.min(1, x * 2.2)), flat: lerp(from.n.flat || 0, to.n.flat, Math.min(1, x * 1.4)) };
      sloshV += (cur.n.tilt - prevTilt) * 6;
      if (x >= 1) { cur.pts = to.pts; cur.n = to.n; to = null; } else busy = true;
    }
    const dx = lookT[0] - look[0], dy = lookT[1] - look[1];
    if (Math.abs(dx) + Math.abs(dy) > 0.01) { look = [look[0] + dx * 0.16, look[1] + dy * 0.16]; busy = true; }
    if (!reduce) {
      sloshV += (-slosh * 60 - sloshV * 4.5) * dt; slosh += sloshV * dt;
      slosh = Math.max(-26, Math.min(26, slosh));
      waveX = (waveX + dt * 22) % 30;
      if (capT > 0) { capT -= dt; capY = -Math.sin(Math.max(0, capT) / 0.9 * Math.PI) * 26; }
      else capY = 0;
      busy = true;
    }
    paint(cur.pts, cur.n);
    if (busy) raf = requestAnimationFrame(frame);
  }
  const kick = () => { if (!raf) { lastNow = performance.now(); raf = requestAnimationFrame(frame); } };

  function setFace(expr) {
    if (expr === cur.expr) return;
    cur.expr = expr;
    const old = k.face, neu = old.cloneNode(false);
    neu.innerHTML = face(expr); neu.style.opacity = 0; neu.style.transition = 'opacity .16s ease';
    old.style.transition = 'opacity .16s ease';
    old.after(neu); k.face = neu;
    requestAnimationFrame(() => { neu.style.opacity = 1; old.style.opacity = 0; });
    setTimeout(() => old.remove(), 220);
    k.blush.style.transition = 'opacity .3s ease'; k.blush.setAttribute('opacity', BLUSH[expr] ?? .5);
    const ex = k.extras; ex.style.transition = 'opacity .16s ease'; ex.style.opacity = 0;
    setTimeout(() => { ex.innerHTML = EXTRAS[expr] || ''; ex.style.opacity = 1; }, 160);
  }
  function setPose(pose, ms, turn) {
    const tn = turn ?? cur.turn;
    if (pose === cur.pose && tn === cur.turn && !to) return;
    cur.pose = pose; cur.turn = tn;
    from = { pts: cur.pts, n: cur.n }; to = { pts: cubes(pose), n: num(pose, tn) };
    dur = reduce ? 1 : (ms || 420); t0 = performance.now(); kick();
  }
  kick();
  return {
    set({ expr, pose, ms, turn } = {}) { if (expr) setFace(expr); if (pose || turn !== undefined) setPose(pose || cur.pose, ms, turn); },
    look(x, y) { lookT = [x, y]; if (reduce) look = lookT; kick(); },
    /* lean of the whole body in degrees, from the spring that carries him around */
    lean(deg) { const d = deg - ext; ext = deg; sloshV += d * 4; kick(); },
    /* a shove to the drink, e.g. when he lands or gets shaken */
    shake(v) { sloshV += v; kick(); },
    popCap() { capT = 0.9; kick(); },
    blink() {
      const eyes = k.face.querySelector('.bd-eyes'); if (!eyes || cur.expr !== 'happy') return;
      eyes.style.transformBox = 'fill-box'; eyes.style.transformOrigin = 'center'; eyes.style.transition = 'transform .07s ease-in';
      eyes.style.transform = 'scaleY(.12)';
      setTimeout(() => { eyes.style.transition = 'transform .11s ease-out'; eyes.style.transform = ''; }, 90);
    },
    get pose() { return cur.pose; }, get expr() { return cur.expr; }, get turn() { return cur.turn; }
  };
}
