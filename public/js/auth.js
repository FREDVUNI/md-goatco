// MD Goatco Farm — Auth JS
(function(){
  'use strict';

  // ── Mobile nav toggle ────────────────────────────────────────────
  const toggle = document.querySelector('.nav-toggle');
  const menu   = document.querySelector('.nav-mobile-menu');
  if (toggle && menu) {
    toggle.addEventListener('click', function(){
      const open = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // ── Password strength meter (register page) ──────────────────────
  const pw    = document.getElementById('password');
  const bar   = document.getElementById('pw-strength-bar');
  const label = document.getElementById('pw-strength-label');
  if (pw && bar) {
    pw.addEventListener('input', function(){
      const v = this.value;
      let score = 0;
      if (v.length >= 8)          score++;
      if (/[A-Z]/.test(v))        score++;
      if (/[0-9]/.test(v))        score++;
      if (/[^A-Za-z0-9]/.test(v)) score++;
      const levels = [
        {pct:'25%', color:'#DC2626', text:'Weak'},
        {pct:'50%', color:'#D97706', text:'Fair'},
        {pct:'75%', color:'#2B5BA8', text:'Good'},
        {pct:'100%',color:'#059669', text:'Strong'},
      ];
      if (!v.length){ bar.style.width='0'; if(label) label.textContent=''; return; }
      const lvl = levels[Math.max(0, score-1)];
      bar.style.width      = lvl.pct;
      bar.style.background = lvl.color;
      if (label){ label.textContent = lvl.text; label.style.color = lvl.color; }
    });
  }

  // ── Password confirm match ────────────────────────────────────────
  const confirm = document.getElementById('password_confirm');
  if (confirm && pw) {
    confirm.addEventListener('input', function(){
      this.style.borderColor = this.value && this.value !== pw.value ? '#DC2626' : '';
    });
  }

  // ── Multi-step registration form ─────────────────────────────────
  const steps    = document.querySelectorAll('.reg-step');
  const nextBtns = document.querySelectorAll('.reg-next');
  const prevBtns = document.querySelectorAll('.reg-prev');
  const stepInds = document.querySelectorAll('.step-ind');
  let current    = 0;

  function showStep(n){
    steps.forEach((s,i)=>{ s.style.display = i===n ? '' : 'none'; });
    stepInds.forEach((s,i)=>{ s.classList.toggle('active', i<=n); });
  }
  if (steps.length){ showStep(0); }

  nextBtns.forEach(function(btn){
    btn.addEventListener('click', function(){
      // Validate current step fields
      const currentStep = steps[current];
      const fields = currentStep.querySelectorAll('[required]');
      let valid = true;
      fields.forEach(function(f){
        if (!f.value.trim()){ f.style.borderColor='#DC2626'; valid=false; }
        else f.style.borderColor='';
      });
      if (!valid) return;
      if (current < steps.length-1){ current++; showStep(current); window.scrollTo(0,0); }
    });
  });
  prevBtns.forEach(function(btn){
    btn.addEventListener('click', function(){
      if (current > 0){ current--; showStep(current); }
    });
  });

  // ── File upload preview ───────────────────────────────────────────
  document.querySelectorAll('.file-upload-input').forEach(function(input){
    input.addEventListener('change', function(){
      const preview = document.getElementById(this.id + '_preview');
      if (!preview) return;
      const file = this.files[0];
      if (!file) return;
      if (file.type.startsWith('image/')){
        const reader = new FileReader();
        reader.onload = function(e){ preview.src = e.target.result; preview.style.display='block'; };
        reader.readAsDataURL(file);
      } else {
        preview.textContent = file.name;
        preview.style.display = 'block';
      }
    });
  });
})();
