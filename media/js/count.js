/**
 * KOU Ecologi: counts the numbers up when they scroll into view. The real figure is in
 * the page from the start; visitors who prefer reduced motion just see it.
 * (C) 2026 Kind of Useful. GNU General Public License version 2 or later.
 */
(function (w, d) {
  'use strict';
  if (!('IntersectionObserver' in w) || (w.matchMedia && w.matchMedia('(prefers-reduced-motion: reduce)').matches)) { return; }

  var els = d.querySelectorAll('[data-kou-eco-count]');
  if (!els.length) { return; }

  function run(el) {
    var end = parseFloat(el.getAttribute('data-kou-eco-count')) || 0;
    var dp = parseInt(el.getAttribute('data-kou-eco-dp'), 10) || 0;
    var final = el.textContent;
    var fmt = w.Intl ? new Intl.NumberFormat(d.documentElement.lang || undefined, { minimumFractionDigits: dp, maximumFractionDigits: dp }) : null;
    var start = null;
    var span = 1200;
    el.setAttribute('aria-hidden', 'true');
    var sr = d.createElement('span');
    sr.className = 'visually-hidden';
    sr.textContent = final;
    el.parentNode.insertBefore(sr, el.nextSibling);
    function step(t) {
      if (start === null) { start = t; }
      var p = Math.min(1, (t - start) / span);
      var v = end * (1 - Math.pow(1 - p, 3));
      el.textContent = p < 1 ? (fmt ? fmt.format(v) : v.toFixed(dp)) : final;
      if (p < 1) { w.requestAnimationFrame(step); } else { el.removeAttribute('aria-hidden'); sr.remove(); }
    }
    w.requestAnimationFrame(step);
  }

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (en.isIntersecting) { io.unobserve(en.target); run(en.target); }
    });
  }, { threshold: 0.4 });
  els.forEach(function (el) { io.observe(el); });
}(window, document));
