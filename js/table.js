(() => {
  const data = window.POSH_PRODUCTS || [];
  const list = document.querySelector('.ptable__cells');
  const card = document.querySelector('.labcard');
  const T = s => (window.POSH_T || {})[s] || s;
  const GROUP = { fiber: T('Fiber'), care: T('Health Care'), skin: T('Skin'), pung: T('Pungtalai Series') };
  const FORM = { powder: T('Powder'), capsule: T('Capsule'), jelly: T('Jelly') };
  const ICON = {
    powder: '<path d="M4 14c2-4 6-4 8 0" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="6" cy="6" r="1.3"/><circle cx="10" cy="4" r="1.3"/><circle cx="8" cy="9" r="1.3"/>',
    capsule: '<rect x="2" y="5.5" width="12" height="5" rx="2.5" transform="rotate(-35 8 8)" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 4.6 9.9 11" stroke="currentColor" stroke-width="2" transform="rotate(-35 8 8) translate(-1 .2)"/>',
    jelly: '<path d="M5 2h6l-1 3v8a2 2 0 0 1-4 0V5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
  };

  // periodic-table placement on a 12 x 7 grid
  const slots = { fiber: [], care: [], skin: [], pung: [] };
  for (let r = 1; r <= 5; r++) for (let c = 1; c <= 3; c++) slots.fiber.push([c, r]);
  for (let r = 4; r <= 5; r++) for (let c = 4; c <= 9; c++) slots.care.push([c, r]);
  slots.skin.push([11, 2], [12, 2]);
  for (let r = 3; r <= 5; r++) for (let c = 10; c <= 12; c++) slots.skin.push([c, r]);
  for (let c = 4; c <= 9; c++) slots.pung.push([c, 7]);
  const used = { fiber: 0, care: 0, skin: 0, pung: 0 };

  list.innerHTML = data.map(p => {
    const [c, r] = slots[p.group][used[p.group]++];
    return `<li class="el" data-g="${p.group}" data-form="${p.form}" style="grid-column:${c};grid-row:${r}">
      <button type="button" data-id="${p.id}" aria-label="${p.name}, ${GROUP[p.group]}, ${FORM[p.form]}">
        <span class="el__top"><span>${String(p.id).padStart(2, '0')}</span><svg class="el__form" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">${ICON[p.form]}</svg></span>
        <span class="el__sym" aria-hidden="true">${p.sym}</span>
        <span class="el__name" lang="${/[a-z]/i.test(p.name) ? 'en' : 'th'}">${p.name}</span>
      </button>
      <img class="el__pack" src="${p.img}" alt="" loading="lazy">
    </li>`;
  }).join('');

  document.querySelectorAll('.chip').forEach(ch => ch.addEventListener('click', () => {
    document.querySelectorAll('.chip').forEach(x => x.classList.toggle('is-on', x === ch));
    const f = ch.dataset.form;
    list.querySelectorAll('.el').forEach(el => el.classList.toggle('is-dim', f !== 'all' && el.dataset.form !== f));
  }));

  function open(id) {
    const p = data.find(x => x.id == id); if (!p) return;
    card.style.setProperty('--c-t', getComputedStyle(list.querySelector(`[data-id="${id}"]`)).backgroundColor);
    card.querySelector('.labcard__media img').src = p.img;
    card.querySelector('.labcard__no').textContent = 'No. ' + String(p.id).padStart(2, '0');
    card.querySelector('.labcard__group').textContent = `${GROUP[p.group]} · ${FORM[p.form]}`;
    card.querySelector('.labcard__name').textContent = p.name;
    card.querySelector('.labcard__desc').textContent = p.desc;
    card.querySelector('.labcard__desc').previousElementSibling.hidden = card.querySelector('.labcard__desc').hidden = !p.desc;
    card.querySelector('.labcard__formula').innerHTML = p.ingr.map(i => `<li>${i}</li>`).join('');
    card.querySelector('.labcard__formula').previousElementSibling.hidden = !p.ingr.length;
    card.querySelector('.labcard__badges').innerHTML = p.badges.map(b => `<li>${b}</li>`).join('');
    card.querySelector(".labcard__go").href = `product.html?id=${p.id}`;
    card.showModal();
  }
  list.addEventListener('click', e => { const b = e.target.closest('button[data-id]'); if (b) open(b.dataset.id); });
  card.querySelector('.labcard__x').addEventListener('click', () => card.close());
  card.addEventListener('click', e => { if (e.target === card) card.close(); });
  window.__openCard = open;
})();
