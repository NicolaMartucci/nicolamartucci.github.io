// ============================
// TUTTOAPRICENA — MAIN JS
// ============================

(function() {

  // ===== NAVBAR =====
  const navbar = document.getElementById('navbar');
  const mobileBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const isHome = document.body.classList.contains('page-home');

  function updateNavbar() {
    if (!navbar) return;
    if (isHome) {
      if (window.scrollY > 60 || (mobileMenu && mobileMenu.classList.contains('open'))) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    } else {
      navbar.classList.add('solid');
    }
  }

  if (navbar) {
    window.addEventListener('scroll', updateNavbar, { passive: true });
    updateNavbar();
  }

  if (mobileBtn && mobileMenu) {
    mobileBtn.addEventListener('click', function() {
      mobileMenu.classList.toggle('open');
      const icon = mobileBtn.querySelector('.icon-hamburger');
      const iconX = mobileBtn.querySelector('.icon-x');
      if (mobileMenu.classList.contains('open')) {
        if (icon) icon.style.display = 'none';
        if (iconX) iconX.style.display = 'block';
        document.body.style.overflow = 'hidden';
      } else {
        if (icon) icon.style.display = 'block';
        if (iconX) iconX.style.display = 'none';
        document.body.style.overflow = '';
      }
      updateNavbar();
    });
  }

  // Mark active nav link
  const currentPath = window.location.pathname;
  document.querySelectorAll('.navbar-links a, .mobile-nav a').forEach(function(link) {
    const href = link.getAttribute('href');
    if (href && href !== '/' && currentPath.includes(href.replace(/^\/tuttoapricena/, ''))) {
      link.classList.add('active');
    }
  });

  // ===== HERO SLIDER =====
  const slides = document.querySelectorAll('.hero-slide');
  const dotBtns = document.querySelectorAll('.hero-dot-btn');
  if (slides.length > 0) {
    let current = 0;
    let fading = false;

    function goToSlide(idx) {
      if (fading) return;
      fading = true;
      slides[current].classList.remove('active');
      dotBtns[current] && dotBtns[current].classList.remove('active');
      current = idx;
      slides[current].classList.add('active');
      dotBtns[current] && dotBtns[current].classList.add('active');
      setTimeout(function() { fading = false; }, 1000);
    }

    dotBtns.forEach(function(btn, i) {
      btn.addEventListener('click', function() { goToSlide(i); });
    });

    setInterval(function() {
      goToSlide((current + 1) % slides.length);
    }, 6500);

    // Trigger loaded animation
    setTimeout(function() {
      const heroInner = document.querySelector('.hero-inner');
      if (heroInner) heroInner.style.opacity = '1';
      const heroInner2 = document.querySelector('.hero-inner');
      if (heroInner2) heroInner2.style.transform = 'translateY(0)';
    }, 200);
  }

  // ===== FILTER PILLS =====
  document.querySelectorAll('.filter-pill').forEach(function(pill) {
    pill.addEventListener('click', function() {
      const group = pill.closest('.filter-pills');
      if (group) {
        group.querySelectorAll('.filter-pill').forEach(function(p) { p.classList.remove('active'); });
      }
      pill.classList.add('active');
      const filterVal = pill.getAttribute('data-filter');
      const targetGrid = document.querySelector('[data-filter-grid]');
      if (targetGrid) {
        targetGrid.querySelectorAll('[data-category]').forEach(function(item) {
          if (filterVal === 'tutte' || filterVal === 'tutti' || filterVal === 'tutte' || item.getAttribute('data-category') === filterVal) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });
      }
    });
  });

  // ===== SEARCH FILTER =====
  const searchInput = document.querySelector('.filter-search input');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const val = searchInput.value.toLowerCase().trim();
      const targetGrid = document.querySelector('[data-filter-grid]');
      if (targetGrid) {
        targetGrid.querySelectorAll('[data-searchable]').forEach(function(item) {
          const text = (item.getAttribute('data-searchable') || '').toLowerCase();
          item.style.display = (!val || text.includes(val)) ? '' : 'none';
        });
      }
    });
  }

  // ===== SCROLL TO TOP ON PAGE LOAD =====
  window.scrollTo(0, 0);

})();
