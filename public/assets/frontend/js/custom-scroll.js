const navLinks = document.querySelectorAll('.scrollto');
const sections = document.querySelectorAll('section');

// 1. Klik link scroll
navLinks.forEach(link => {
  link.addEventListener('click', function (e) {
    const targetUrl = new URL(this.href);
    const targetHash = targetUrl.hash;
    const currentPath = window.location.pathname;

    if (targetUrl.pathname !== currentPath) {
      // Redirect ke halaman lain + hash
      window.location.href = targetUrl.pathname + targetHash;
    } else {
      // Scroll smooth jika halaman sama
      e.preventDefault();
      const target = document.querySelector(targetHash);
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
        setActiveLink(targetHash); // set active saat scroll manual
      }
    }
  });
});

// 2. Scroll manual: ubah active link
window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(section => {
    const sectionTop = section.offsetTop;
    const sectionHeight = section.clientHeight;
    if (scrollY >= sectionTop - sectionHeight / 3) {
      current = section.getAttribute('id');
    }
  });

  setActiveLink('#' + current);
});

// 3. Saat halaman dimuat, scroll ke hash jika ada
window.addEventListener('load', () => {
  const hash = window.location.hash;
  if (hash) {
    const target = document.querySelector(hash);
    if (target) {
      setTimeout(() => {
        target.scrollIntoView({ behavior: 'smooth' });
        setActiveLink(hash);
      }, 300); // delay supaya layout siap
    }
  }
});

// 4. Fungsi untuk aktifkan menu
function setActiveLink(hash) {
  navLinks.forEach(link => {
    const linkHash = new URL(link.href).hash;
    if (linkHash === hash) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}
