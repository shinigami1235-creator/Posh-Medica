(() => {
  // PDPA cookie choice, kept for a year in this browser. It updates Google Consent Mode so Tag Manager only measures what the visitor allowed.
  const box = document.querySelector('[data-cookies]'); if (!box) return;
  const KEY = 'posh-consent', YEAR = 365 * 864e5;
  const read = () => { try { const c = JSON.parse(localStorage.getItem(KEY) || 'null'); return c && Date.now() - c.at < YEAR ? c : null; } catch (e) { return null; } };
  const apply = c => {
    window.gtag && gtag('consent', 'update', { analytics_storage: c.analytics ? 'granted' : 'denied', ad_storage: c.marketing ? 'granted' : 'denied',
      ad_user_data: c.marketing ? 'granted' : 'denied', ad_personalization: c.marketing ? 'granted' : 'denied' });
    (window.dataLayer = window.dataLayer || []).push({ event: 'consent_update', analytics: !!c.analytics, marketing: !!c.marketing });
  };
  const save = c => { c.at = Date.now(); try { localStorage.setItem(KEY, JSON.stringify(c)); } catch (e) {} apply(c); box.hidden = true; };
  const choose = box.querySelector('[data-cookies-choose]'), saveBtn = box.querySelector('[data-cookies-save]'), openBtn = box.querySelector('[data-cookies-open]');
  const open = () => { const c = read() || {}; box.querySelector('[name=analytics]').checked = !!c.analytics; box.querySelector('[name=marketing]').checked = !!c.marketing; box.hidden = false; };
  box.querySelector('[data-cookies-accept]').addEventListener('click', () => save({ analytics: true, marketing: true }));
  box.querySelector('[data-cookies-reject]').addEventListener('click', () => save({ analytics: false, marketing: false }));
  openBtn.addEventListener('click', () => { choose.hidden = false; saveBtn.hidden = false; openBtn.hidden = true; });
  saveBtn.addEventListener('click', () => save({ analytics: box.querySelector('[name=analytics]').checked, marketing: box.querySelector('[name=marketing]').checked }));
  document.querySelectorAll('[data-cookie-settings]').forEach(b => b.addEventListener('click', () => { open(); choose.hidden = false; saveBtn.hidden = false; openBtn.hidden = true; }));
  if (!read()) box.hidden = false;
})();
