/* =====================================================================
   MD Goatco Farm — auth.js
   JS for login pages and registration form.
   ===================================================================== */

"use strict";

// ── REGISTRATION MULTI-STEP ───────────────────────────────────────────
let currentStep = 1;
const TOTAL_STEPS = 4;

function goToStep(n) {
  if (n < 1 || n > TOTAL_STEPS) return;

  // Validate current step before advancing
  if (n > currentStep && !validateStep(currentStep)) return;

  // Hide all step panes
  document
    .querySelectorAll(".reg-step")
    .forEach((s) => s.classList.remove("active"));
  document
    .querySelectorAll(".step-item")
    .forEach((s) => s.classList.remove("active", "done"));

  // Show the target pane
  const target = document.getElementById("step-" + n);
  if (!target) return;
  target.classList.add("active");

  // Update step nav states
  for (let i = 1; i <= TOTAL_STEPS; i++) {
    const nav = document.getElementById("step-nav-" + i);
    if (!nav) continue;
    if (i < n) nav.classList.add("done");
    else if (i === n) nav.classList.add("active");
  }

  currentStep = n;
  window.scrollTo({ top: 0, behavior: "smooth" });
}

/**
 * Basic client-side validation for each step.
 * Server-side validation is the authoritative check — this is UX only.
 */
function validateStep(step) {
  const pane = document.getElementById("step-" + step);
  if (!pane) return true;

  const required = pane.querySelectorAll("[required]");
  let valid = true;

  required.forEach((field) => {
    field.classList.remove("field-error");
    if (!field.value.trim()) {
      field.classList.add("field-error");
      field.style.borderColor = "var(--red)";
      valid = false;
    } else {
      field.style.borderColor = "";
    }
  });

  if (!valid) {
    const firstError = pane.querySelector(".field-error");
    firstError?.scrollIntoView({ behavior: "smooth", block: "center" });
    showStepError(
      step,
      "Please fill in all required fields before continuing.",
    );
  } else {
    clearStepError(step);
  }

  return valid;
}

function showStepError(step, msg) {
  let errBox = document.getElementById("step-error-" + step);
  if (!errBox) {
    errBox = document.createElement("div");
    errBox.id = "step-error-" + step;
    errBox.className = "form-errors";
    const pane = document.getElementById("step-" + step);
    pane.querySelector("h3").after(errBox);
  }
  errBox.innerHTML = `<p>${msg}</p>`;
}

function clearStepError(step) {
  document.getElementById("step-error-" + step)?.remove();
}

// ── FILE UPLOAD PREVIEWS ──────────────────────────────────────────────
document.querySelectorAll(".file-input").forEach((input) => {
  input.addEventListener("change", function () {
    const previewId = "preview-" + this.id;
    const preview = document.getElementById(previewId);
    const label = this.previousElementSibling;
    if (!preview || !this.files[0]) return;

    const file = this.files[0];

    // Size check (5 MB)
    if (file.size > 5 * 1024 * 1024) {
      preview.innerHTML =
        '<span class="file-name" style="color:var(--red)">⚠ File too large (max 5 MB)</span>';
      this.value = "";
      return;
    }

    if (file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = (e) => {
        preview.innerHTML = `
          <img src="${e.target.result}" class="file-thumb" alt="Preview">
          <span class="file-name">✓ ${escHtml(file.name)}</span>
        `;
      };
      reader.readAsDataURL(file);
    } else {
      // PDF
      preview.innerHTML = `<span class="file-name">📄 ${escHtml(file.name)}</span>`;
    }

    // Update label button text
    const strongEl = label?.querySelector(".file-upload-inner strong");
    if (strongEl) strongEl.textContent = "✓ File selected — click to change";
  });
});

// ── AUTH TAB SWITCHING (login page) ──────────────────────────────────
function switchTab(tabName, clickedEl) {
  document
    .querySelectorAll(".auth-pane")
    .forEach((p) => (p.style.display = "none"));
  document
    .querySelectorAll(".auth-tab")
    .forEach((t) => t.classList.remove("active"));

  const target = document.getElementById("pane-" + tabName);
  if (target) target.style.display = "block";

  if (clickedEl) {
    clickedEl.classList.add("active");
  } else {
    // Find and activate the right tab by data attribute or position
    const tabs = document.querySelectorAll(".auth-tab");
    if (tabName === "login" && tabs[0]) tabs[0].classList.add("active");
    if (tabName === "status" && tabs[1]) tabs[1].classList.add("active");
  }
}

// ── PASSWORD STRENGTH INDICATOR ───────────────────────────────────────
function initPasswordStrength() {
  const passInput = document.getElementById("password");
  if (!passInput) return;

  // Create strength bar
  const wrap = passInput.parentElement;
  const bar = document.createElement("div");
  bar.style.cssText =
    "height:3px;border-radius:2px;margin-top:6px;transition:width .3s ease,background .3s ease;width:0;";
  const label = document.createElement("p");
  label.style.cssText =
    "font-size:0.72rem;margin-top:4px;color:var(--slate-light);";
  wrap.appendChild(bar);
  wrap.appendChild(label);

  passInput.addEventListener("input", () => {
    const v = passInput.value;
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;

    const levels = [
      { width: "25%", color: "var(--red)", text: "Weak" },
      { width: "50%", color: "var(--amber)", text: "Fair" },
      { width: "75%", color: "var(--blue)", text: "Good" },
      { width: "100%", color: "var(--green)", text: "Strong" },
    ];

    if (v.length === 0) {
      bar.style.width = "0";
      label.textContent = "";
    } else {
      const lvl = levels[Math.max(0, score - 1)];
      bar.style.width = lvl.width;
      bar.style.background = lvl.color;
      label.textContent = lvl.text;
      label.style.color = lvl.color;
    }
  });
}

// ── PASSWORD CONFIRM MATCH ────────────────────────────────────────────
function initPasswordConfirm() {
  const confirm = document.getElementById("password_confirm");
  const pass = document.getElementById("password");
  if (!confirm || !pass) return;

  confirm.addEventListener("input", () => {
    if (confirm.value && confirm.value !== pass.value) {
      confirm.style.borderColor = "var(--red)";
    } else {
      confirm.style.borderColor = "";
    }
  });
}

// ── FORM SUBMISSION STATE ─────────────────────────────────────────────
document.querySelectorAll("form").forEach((form) => {
  form.addEventListener("submit", function () {
    const submitBtn = this.querySelector("[type=submit]");
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = "Submitting…";
    }
  });
});

// ── UTILITY ──────────────────────────────────────────────────────────
function escHtml(str) {
  const d = document.createElement("div");
  d.textContent = str;
  return d.innerHTML;
}

// ── INIT ─────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initPasswordStrength();
  initPasswordConfirm();
});
