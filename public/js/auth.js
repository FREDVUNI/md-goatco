/* =====================================================================
   MD Goatco Farm — auth.js
   JS for login pages and registration form.
   ===================================================================== */

"use strict";

// ── REGISTRATION MULTI-STEP ───────────────────────────────────────────
let currentStep = 1;
const TOTAL_STEPS = 4;

function showStepPane(n) {
  document
    .querySelectorAll(".reg-step")
    .forEach((s) => s.classList.remove("active"));
  document
    .querySelectorAll(".step-item")
    .forEach((s) => s.classList.remove("active", "done"));

  const target = document.getElementById("step-" + n);
  if (!target) return;
  target.classList.add("active");

  for (let i = 1; i <= TOTAL_STEPS; i++) {
    const nav = document.getElementById("step-nav-" + i);
    if (!nav) continue;
    if (i < n) nav.classList.add("done");
    else if (i === n) nav.classList.add("active");
  }

  currentStep = n;
}

function goToStep(n) {
  if (n < 1 || n > TOTAL_STEPS) return;

  // Validate current step before advancing
  if (n > currentStep && !validateStep(currentStep)) return;

  showStepPane(n);
  window.scrollTo({ top: 0, behavior: "smooth" });
}

/**
 * Runs the shared MDValidate engine (see validate.js) over just this step's
 * fields, so per-field inline errors render exactly as they do everywhere
 * else in the app. Server-side validation remains the authoritative check.
 */
function validateStep(step) {
  const pane = document.getElementById("step-" + step);
  if (!pane) return true;

  const fields = pane.querySelectorAll("input, select, textarea");
  const valid = window.MDValidate ? window.MDValidate.validateFields(fields) : true;

  if (!valid) {
    pane.querySelector(".has-error")?.scrollIntoView({ behavior: "smooth", block: "center" });
  }

  return valid;
}

// If the final submit fails because a field on an earlier, currently-hidden
// step is invalid, jump to that step so the inline error is actually visible
// instead of silently blocking submission with no feedback.
document.getElementById("regForm")?.addEventListener("validate:invalid", (e) => {
  const pane = e.detail.field.closest(".reg-step");
  if (!pane) return;
  const stepNum = parseInt(pane.id.replace("step-", ""), 10);
  if (stepNum && stepNum !== currentStep) showStepPane(stepNum);
});

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
        '<span class="file-name" style="color:var(--red)"><i class="fas fa-exclamation-triangle"></i> File too large (max 5 MB)</span>';
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
      preview.innerHTML = `<span class="file-name"><i class="fas fa-file-alt"></i> ${escHtml(file.name)}</span>`;
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

// Password confirm match is handled generically by validate.js's data-match
// attribute. Form submission loading/disabled state is handled globally by
// loader.js.

// ── UTILITY ──────────────────────────────────────────────────────────
function escHtml(str) {
  const d = document.createElement("div");
  d.textContent = str;
  return d.innerHTML;
}

// ── INIT ─────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initPasswordStrength();
});
