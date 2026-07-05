/* =====================================================================
   MD Goatco Farm — public.js
   Landing page, legal pages and public site interactions.
   ===================================================================== */

"use strict";

// ── MOBILE NAV ────────────────────────────────────────────────────────
(function () {
  const toggle = document.querySelector(".nav-toggle");
  const mobileMenu = document.querySelector(".nav-mobile-menu");
  if (!toggle || !mobileMenu) return;

  toggle.addEventListener("click", () => {
    const open = mobileMenu.classList.toggle("open");
    toggle.classList.toggle("open", open);
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
  });

  // Close on nav link click (smooth scroll then close)
  mobileMenu.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener("click", () => {
      mobileMenu.classList.remove("open");
      toggle.classList.remove("open");
    });
  });
})();

// ── ACTIVE NAV LINK ───────────────────────────────────────────────────
(function () {
  const path = window.location.pathname.replace(/\/$/, "");
  document
    .querySelectorAll(".nav-links a, .nav-mobile-menu a")
    .forEach((link) => {
      const href = link.getAttribute("href")?.replace(/\/$/, "");
      if (href && (href === path || (path === "" && href === "/"))) {
        link.classList.add("active");
      }
    });
})();

// ── SMOOTH SCROLL for anchor links ────────────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    const target = document.querySelector(this.getAttribute("href"));
    if (!target) return;
    e.preventDefault();
    const offset = 80; // height of sticky header
    const top =
      target.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top, behavior: "smooth" });
  });
});

// ── FLASH MESSAGE AUTO-DISMISS ────────────────────────────────────────
document.querySelectorAll(".flash").forEach((flash) => {
  setTimeout(() => {
    flash.style.transition = "opacity .4s ease";
    flash.style.opacity = "0";
    setTimeout(() => flash.remove(), 400);
  }, 5000);
});

// ── LEGAL PAGE: TOC SCROLL HIGHLIGHTING ──────────────────────────────
(function () {
  const tocLinks = document.querySelectorAll(".doc-toc a");
  const sections = document.querySelectorAll(".doc-body h2[id]");
  if (!tocLinks.length || !sections.length) return;

  const onScroll = () => {
    let current = "";
    sections.forEach((s) => {
      if (window.scrollY >= s.offsetTop - 110) current = s.id;
    });
    tocLinks.forEach((a) => {
      a.classList.toggle("active", a.getAttribute("href") === "#" + current);
    });
  };

  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll(); // Run once on load
})();

// ── CONTACT FORM CHARACTER COUNT ──────────────────────────────────────
(function () {
  const textarea = document.querySelector('#message, textarea[name="message"]');
  if (!textarea) return;

  const counter = document.createElement("p");
  counter.style.cssText =
    "font-size:0.74rem;color:var(--slate-light);text-align:right;margin-top:4px;";
  textarea.parentElement.appendChild(counter);

  const update = () => {
    const len = textarea.value.length;
    const max = 5000;
    counter.textContent = len + " / " + max;
    counter.style.color =
      len > max * 0.9 ? "var(--amber)" : "var(--slate-light)";
  };
  textarea.addEventListener("input", update);
  update();
})();

// ── INTERSECTION OBSERVER: fade-in sections on scroll ────────────────
(function () {
  if (!("IntersectionObserver" in window)) return;

  const style = document.createElement("style");
  style.textContent = `.fade-in { opacity: 0; transform: translateY(18px); transition: opacity .55s ease, transform .55s ease; }
  .fade-in.visible { opacity: 1; transform: none; }`;
  document.head.appendChild(style);

  const targets = document.querySelectorAll(
    ".service-card, .role-card, .test-card, .why-item, .contact-card, .about-list li",
  );

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12, rootMargin: "0px 0px -30px 0px" },
  );

  targets.forEach((el, i) => {
    el.classList.add("fade-in");
    el.style.transitionDelay = (i % 4) * 0.08 + "s";
    observer.observe(el);
  });
})();

// ── HERO STATS COUNTER ANIMATION ─────────────────────────────────────
(function () {
  const stats = document.querySelectorAll(".hero-stat strong");
  if (!stats.length) return;

  const animateNum = (el) => {
    const raw = el.textContent.replace(/[^0-9.]/g, "");
    const end = parseFloat(raw);
    if (isNaN(end)) return;

    const suffix = el.textContent.replace(raw, "");
    const dur = 1200;
    const start = performance.now();

    const tick = (now) => {
      const pct = Math.min((now - start) / dur, 1);
      const val = Math.round(pct * end);
      el.textContent = val.toLocaleString() + suffix;
      if (pct < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };

  if (!("IntersectionObserver" in window)) {
    stats.forEach(animateNum);
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateNum(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 },
  );

  stats.forEach((el) => observer.observe(el));
})();
