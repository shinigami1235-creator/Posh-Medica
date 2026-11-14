(() => {
  // load TikTok's player only when the feed is about to be seen
  const feed = document.getElementById('feed');
  if (!feed) return;
  const io = new IntersectionObserver(e => {
    if (!e[0].isIntersecting) return;
    io.disconnect();
    const s = document.createElement('script'); s.src = 'https://www.tiktok.com/embed.js'; s.async = true; document.body.appendChild(s);
  }, { rootMargin: '600px' });
  io.observe(feed);
})();
