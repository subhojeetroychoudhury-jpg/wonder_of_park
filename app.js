
(function () {
  const details = document.querySelectorAll('details.faqItem');
  details.forEach(d => {
    d.addEventListener('toggle', () => {
      if (d.open) {

        details.forEach(other => { if (other !== d) other.open = false; });
      }
    });
  });


  const scene = document.querySelector('.scene');
  if (!scene) return;

  let raf = null;
  window.addEventListener('mousemove', (e) => {
    if (raf) return;
    raf = requestAnimationFrame(() => {
      raf = null;
      const x = (e.clientX / window.innerWidth) - 0.5;
      const y = (e.clientY / window.innerHeight) - 0.5;
      scene.style.transform = `translate(${x * 6}px, ${y * 6}px)`;
    });
  }, { passive: true });
})();

