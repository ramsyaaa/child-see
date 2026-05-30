/* ══════════════════════════════════════
   Ferensa Studio — Main JS
══════════════════════════════════════ */

// ── Reveal on Scroll ──────────────────
function initReveal() {
  const els = document.querySelectorAll('.reveal');
  const check = () => els.forEach(el => {
    if (el.getBoundingClientRect().top < window.innerHeight - 60)
      el.classList.add('active');
  });
  window.addEventListener('scroll', check, { passive: true });
  check();
}

// ── Sticky Nav Shadow ─────────────────
function initNav() {
  const nav = document.querySelector('.site-nav');
  if (!nav) return;
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 20);
  }, { passive: true });
}

// ── Mobile Menu ────────────────────────
function initMobileMenu() {
  const btn  = document.getElementById('menu-btn');
  const menu = document.getElementById('mobile-menu');
  const close = document.getElementById('menu-close');
  if (!btn || !menu) return;

  btn.addEventListener('click', () => {
    menu.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
    menu.classList.add('translate-x-0', 'opacity-100');
  });
  const closeFn = () => {
    menu.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
    menu.classList.remove('translate-x-0', 'opacity-100');
  };
  if (close) close.addEventListener('click', closeFn);
  menu.addEventListener('click', e => { if (e.target === menu) closeFn(); });
}

// ── Back to Top ────────────────────────
function initBackToTop() {
  const btn = document.getElementById('to-top');
  if (!btn) return;
  window.addEventListener('scroll', () => {
    btn.classList.toggle('show', window.scrollY > 500);
  }, { passive: true });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// ── Carousel ──────────────────────────
function initCarousel(wrapSelector) {
  const wrap   = document.querySelector(wrapSelector);
  if (!wrap) return;
  const track  = wrap.querySelector('.carousel-track');
  const slides = wrap.querySelectorAll('.carousel-slide');
  const dots   = wrap.querySelectorAll('.carousel-dot');
  const prevBtn = wrap.querySelector('.carousel-prev');
  const nextBtn = wrap.querySelector('.carousel-next');
  if (!track || slides.length < 2) return;

  let current = 0;
  let timer;

  const go = (n) => {
    current = (n + slides.length) % slides.length;
    track.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d,i) => d.classList.toggle('active', i === current));
  };

  const startAuto = () => { timer = setInterval(() => go(current + 1), 5500); };
  const stopAuto  = () => clearInterval(timer);

  dots.forEach((d, i) => d.addEventListener('click', () => { go(i); stopAuto(); startAuto(); }));
  if (prevBtn) prevBtn.addEventListener('click', () => { go(current - 1); stopAuto(); startAuto(); });
  if (nextBtn) nextBtn.addEventListener('click', () => { go(current + 1); stopAuto(); startAuto(); });

  // touch swipe
  let startX = 0;
  track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; stopAuto(); }, { passive: true });
  track.addEventListener('touchend', e => {
    const dx = e.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 50) go(dx < 0 ? current + 1 : current - 1);
    startAuto();
  });

  wrap.addEventListener('mouseenter', stopAuto);
  wrap.addEventListener('mouseleave', startAuto);

  go(0);
  startAuto();
}

// ── Accordion ─────────────────────────
function initAccordion() {
  document.querySelectorAll('.accordion-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const content = trigger.nextElementSibling;
      const icon    = trigger.querySelector('.acc-icon');
      const isOpen  = content.classList.contains('open');
      document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('open'));
      document.querySelectorAll('.acc-icon').forEach(i => { i.style.transform = 'rotate(0deg)'; });
      if (!isOpen) {
        content.classList.add('open');
        if (icon) icon.style.transform = 'rotate(45deg)';
      }
    });
  });
}

// ── Calendar ──────────────────────────
function initCalendar() {
  const grid     = document.getElementById('cal-grid');
  const monthEl  = document.getElementById('cal-month');
  const prevBtn  = document.getElementById('cal-prev');
  const nextBtn  = document.getElementById('cal-next');
  if (!grid) return;

  // Sample events
  const events = {
    '2026-04-07': ['Yoga 07:00', 'Pilates 09:00'],
    '2026-04-08': ['Power Yoga 07:00'],
    '2026-04-09': ['Meditation 18:00'],
    '2026-04-10': ['Yoga Nidra 17:00', 'Yin 19:00'],
    '2026-04-12': ['Weekend Yoga 08:00', 'Sound Bath 14:00'],
    '2026-04-14': ['Yoga 07:00', 'Pilates 09:00'],
    '2026-04-16': ['Slow Flow 19:00'],
    '2026-04-19': ['Yoga 07:00'],
    '2026-04-21': ['Community Yoga 16:00'],
    '2026-04-23': ['Pilates Core 09:30'],
    '2026-04-26': ['Yoga Flow 07:00'],
  };

  let year = 2026, month = 3; // April (0-indexed)
  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

  const pad = n => String(n).padStart(2,'0');
  const today = new Date();

  const render = () => {
    monthEl.textContent = `${months[month]} ${year}`;
    const first = new Date(year, month, 1).getDay();
    const days  = new Date(year, month + 1, 0).getDate();
    const prevDays = new Date(year, month, 0).getDate();

    let html = '';
    // Day headers
    ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(d => {
      html += `<div class="text-center text-xs font-semibold text-[#7a7a7a] py-2 uppercase tracking-wider">${d}</div>`;
    });

    // Prev month overflow
    for (let i = 0; i < first; i++) {
      const d = prevDays - first + i + 1;
      html += `<div class="cal-day other-month"><span class="text-xs font-medium text-gray-300">${d}</span></div>`;
    }

    // Current month
    for (let d = 1; d <= days; d++) {
      const key = `${year}-${pad(month+1)}-${pad(d)}`;
      const evts = events[key] || [];
      const isToday = today.getFullYear()===year && today.getMonth()===month && today.getDate()===d;
      html += `<div class="cal-day ${evts.length?'has-class':''} ${isToday?'today':''}" onclick="openDayModal('${key}')">
        <span class="text-xs font-semibold ${isToday ? 'text-[#B85C38]' : 'text-[#1a1a1a]'}">${d}</span>
        ${evts.slice(0,2).map(e=>`<div class="cal-event">${e}</div>`).join('')}
        ${evts.length>2 ? `<div class="text-[9px] text-[#C4923A] mt-1">+${evts.length-2} more</div>` : ''}
      </div>`;
    }

    // Next month overflow
    const total = first + days;
    const rem   = total % 7 === 0 ? 0 : 7 - (total % 7);
    for (let d = 1; d <= rem; d++) {
      html += `<div class="cal-day other-month"><span class="text-xs font-medium text-gray-300">${d}</span></div>`;
    }

    grid.innerHTML = html;
  };

  if (prevBtn) prevBtn.addEventListener('click', () => {
    month--; if (month < 0) { month = 11; year--; } render();
  });
  if (nextBtn) nextBtn.addEventListener('click', () => {
    month++; if (month > 11) { month = 0; year++; } render();
  });

  render();
}

window.openDayModal = (key) => {
  const modal = document.getElementById('day-modal');
  const title = document.getElementById('modal-date');
  const list  = document.getElementById('modal-events');
  if (!modal) return;
  const events = {
    '2026-04-07': ['Yoga 07:00 — Maya Anindita', 'Pilates 09:00 — Sarah Kusuma'],
    '2026-04-08': ['Power Yoga 07:00 — Maya Anindita'],
    '2026-04-09': ['Meditation 18:00 — Devi Lakshmi'],
    '2026-04-10': ['Yoga Nidra 17:00 — Devi Lakshmi', 'Yin & Restore 19:00 — Maya Anindita'],
    '2026-04-12': ['Weekend Yoga 08:00 — Maya Anindita', 'Sound Bath 14:00 — Devi Lakshmi'],
  };
  const d = new Date(key + 'T00:00:00');
  title.textContent = d.toLocaleDateString('en-US',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  const evts = events[key] || [];
  list.innerHTML = evts.length
    ? evts.map(e=>`<div class="flex items-center gap-3 py-3 border-b border-[#e8e0d0] last:border-0">
        <div class="w-8 h-8 bg-gradient-to-br from-[#C4923A] to-[#B85C38] rounded-full flex items-center justify-center flex-shrink-0">
          <i class="fas fa-leaf text-white text-xs"></i>
        </div>
        <div><p class="text-sm font-medium text-[#1a1a1a]">${e.split('—')[0]}</p>
        <p class="text-xs text-[#7a7a7a]">${e.split('—')[1]||''}</p></div>
        <a href="bundles.html" class="btn-grad btn-grad-sm ml-auto">Book</a>
      </div>`).join('')
    : '<p class="text-sm text-[#7a7a7a] py-4 text-center">No classes scheduled for this day.</p>';
  modal.classList.remove('opacity-0','pointer-events-none');
  modal.classList.add('opacity-100');
};

window.closeModal = () => {
  const modal = document.getElementById('day-modal');
  if (modal) { modal.classList.add('opacity-0','pointer-events-none'); modal.classList.remove('opacity-100'); }
};

// ── Form helpers ──────────────────────
window.togglePwd = (id, el) => {
  const input = document.getElementById(id);
  const icon  = el.querySelector('i');
  if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
  else { input.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
};

// ── Init All ───────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initReveal();
  initNav();
  initMobileMenu();
  initBackToTop();
  initCarousel('#hero-carousel');
  initCarousel('#gallery-carousel');
  initAccordion();
  initCalendar();
});
