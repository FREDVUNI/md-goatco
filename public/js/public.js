// MD Goatco Farm — Public JS
(function(){
  'use strict';

  // ── Mobile nav ───────────────────────────────────────────────────
  const toggle = document.querySelector('.nav-toggle');
  const menu   = document.querySelector('.nav-mobile-menu');
  if (toggle && menu){
    toggle.addEventListener('click', function(){
      const open = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.querySelectorAll('span').forEach(function(s,i){
        if (open){ if(i===0)s.style.transform='rotate(45deg) translate(5px,5px)'; if(i===1)s.style.opacity='0'; if(i===2)s.style.transform='rotate(-45deg) translate(5px,-5px)'; }
        else { s.style.transform=''; s.style.opacity=''; }
      });
    });
  }

  // ── Smooth scroll for anchor links ───────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function(a){
    a.addEventListener('click', function(e){
      const target = document.querySelector(this.getAttribute('href'));
      if (target){ e.preventDefault(); target.scrollIntoView({behavior:'smooth', block:'start'}); menu?.classList.remove('open'); }
    });
  });

  // ── Sticky nav shadow ─────────────────────────────────────────────
  const header = document.querySelector('header');
  if (header){
    window.addEventListener('scroll', function(){
      header.style.boxShadow = window.scrollY > 10 ? '0 2px 20px rgba(0,0,0,0.08)' : '';
    });
  }

  // ── Auto-dismiss flash ────────────────────────────────────────────
  document.querySelectorAll('.flash').forEach(function(el){
    setTimeout(function(){ el.style.opacity='0'; el.style.transition='opacity .4s'; setTimeout(function(){ el.remove(); },400); }, 5000);
  });
})();
