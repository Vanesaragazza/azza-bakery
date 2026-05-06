// Navbar shadow pas scroll
window.addEventListener("scroll", () => {
  const navbar = document.querySelector(".navbar");
  navbar.classList.toggle("scrolled", window.scrollY > 50);
});

// Fade-in saat scroll
const faders = document.querySelectorAll(".card, .about, .testi-card");

const appearOnScroll = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add("show");
    }
  });
});

faders.forEach(el => {
  el.classList.add("fade");
  appearOnScroll.observe(el);
});