(() => {
  const topic = new URLSearchParams(location.search).get('topic');
  const sel = document.querySelector('select[name=topic]');
  if (topic && sel && sel.querySelector(`option[value="${topic}"]`)) sel.value = topic;
})();
document.querySelector('.contact form')?.addEventListener('submit', () => document.dispatchEvent(new CustomEvent('buddy', { detail: 'sent' })));
