/* =====================================================================
   MD Goatco Farm — dashboard.js
   Shared JS for all 4 role dashboards.
   ===================================================================== */

'use strict';

// ── SIDEBAR TOGGLE (mobile) ───────────────────────────────────────────
const sidebar   = document.getElementById('sidebar');
const mainArea  = document.getElementById('main');
let sidebarOpen = false;

function toggleSidebar() {
  sidebarOpen = !sidebarOpen;
  sidebar?.classList.toggle('open', sidebarOpen);
  // Backdrop
  if (sidebarOpen) {
    let bd = document.getElementById('sb-backdrop');
    if (!bd) {
      bd = document.createElement('div');
      bd.id = 'sb-backdrop';
      bd.style.cssText = 'position:fixed;inset:0;background:rgba(26,34,56,0.45);z-index:199;';
      bd.addEventListener('click', () => toggleSidebar());
      document.body.appendChild(bd);
    }
    bd.style.display = 'block';
  } else {
    const bd = document.getElementById('sb-backdrop');
    if (bd) bd.style.display = 'none';
  }
}

// ── NOTIFICATIONS DROPDOWN ────────────────────────────────────────────
function toggleNotifs() {
  const dd = document.getElementById('notifDropdown');
  if (!dd) return;
  const isOpen = dd.classList.toggle('show');
  if (isOpen) markNotifsRead();
}

function markNotifsRead() {
  // Optimistic UI — remove unread styling immediately
  document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
  document.querySelectorAll('.notif-badge').forEach(el => el.remove());

  // Fire-and-forget request to mark all read server-side
  const role = document.body.className.replace('role-', '');
  fetch(`/api/v1/notifications/read-all`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  }).catch(() => {}); // Silent fail — UX already updated
}

// Close notif dropdown when clicking outside
document.addEventListener('click', (e) => {
  const wrap = document.querySelector('.notif-wrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('notifDropdown')?.classList.remove('show');
  }
});

// ── FLASH ALERT AUTO-DISMISS ──────────────────────────────────────────
document.querySelectorAll('.alert').forEach(alert => {
  // Auto-dismiss success messages after 5 seconds
  if (alert.classList.contains('alert-success')) {
    setTimeout(() => {
      alert.style.transition = 'opacity .4s ease';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 400);
    }, 5000);
  }
});

// ── MODAL HELPERS ─────────────────────────────────────────────────────
function openModal(id) {
  document.getElementById(id)?.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal(id) {
  document.getElementById(id)?.classList.remove('open');
  document.body.style.overflow = '';
}

// Close modal on overlay click or Escape key
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeModal(overlay.id);
  });
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(m => closeModal(m.id));
  }
});

// ── CONFIRMATION DIALOGS ──────────────────────────────────────────────
/**
 * Attach to any form submit button:
 * <button data-confirm="Are you sure?">Delete</button>
 */
document.querySelectorAll('[data-confirm]').forEach(btn => {
  btn.addEventListener('click', (e) => {
    if (!confirm(btn.dataset.confirm)) {
      e.preventDefault();
      e.stopPropagation();
    }
  });
});

// ── TABLE SEARCH ──────────────────────────────────────────────────────
/**
 * Client-side live search for tables.
 * Usage: <input data-search-table="myTable" placeholder="Search…">
 *        <table id="myTable">…</table>
 */
document.querySelectorAll('[data-search-table]').forEach(input => {
  const tableId = input.dataset.searchTable;
  const table   = document.getElementById(tableId);
  if (!table) return;

  input.addEventListener('input', () => {
    const q = input.value.trim().toLowerCase();
    table.querySelectorAll('tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });
});

// ── CSRF HELPER FOR AJAX ──────────────────────────────────────────────
function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content
      || document.querySelector('input[name="csrf_token"]')?.value
      || '';
}

// ── AJAX FORM SUBMISSION (optional, for inline approve/reject) ────────
/**
 * Post a form via fetch and reload on success.
 * Usage: <button onclick="ajaxPost('/admin/applications/1/approve')">Approve</button>
 */
async function ajaxPost(url, body = {}) {
  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': getCsrfToken(),
      },
      body: JSON.stringify(body),
    });
    if (res.ok) {
      location.reload();
    } else {
      const data = await res.json().catch(() => ({}));
      alert(data.message || 'Request failed. Please try again.');
    }
  } catch (err) {
    console.error('ajaxPost error:', err);
    alert('Network error. Please check your connection.');
  }
}

// ── GOAT TAG LIVE LOOKUP (for vet visit log form) ─────────────────────
let tagLookupTimer;

function initTagLookup() {
  const tagInput    = document.getElementById('goat_tag');
  const nameDisplay = document.getElementById('goat_name_display');
  const idHidden    = document.getElementById('goat_id');
  const tagResult   = document.getElementById('tagResult');

  if (!tagInput) return;

  tagInput.addEventListener('input', () => {
    const val = tagInput.value.trim();
    clearTimeout(tagLookupTimer);
    if (tagResult) tagResult.innerHTML = '';
    if (val.length < 4) return;

    tagLookupTimer = setTimeout(async () => {
      try {
        const res  = await fetch(`/vet/animals/lookup?tag=${encodeURIComponent(val)}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await res.json();
        if (data.status === 'success' && data.data) {
          const goat = data.data;
          if (nameDisplay) nameDisplay.value = goat.name;
          if (idHidden)    idHidden.value    = goat.id;
          if (tagResult)   tagResult.innerHTML = `<span class="tag-found">✓ Found: ${escHtml(goat.name)} (${escHtml(goat.breed || '')})</span>`;
        } else {
          if (nameDisplay) nameDisplay.value = '';
          if (idHidden)    idHidden.value    = '';
          if (tagResult)   tagResult.innerHTML = '<span class="tag-not-found">Tag not found in herd</span>';
        }
      } catch { /* silent */ }
    }, 400);
  });
}

// ── GROWTH CHART ANIMATION ────────────────────────────────────────────
function animateGrowthBars() {
  document.querySelectorAll('.growth-bar').forEach(bar => {
    const target = bar.style.width;
    bar.style.width = '0';
    requestAnimationFrame(() => {
      bar.style.transition = 'width .7s ease';
      bar.style.width = target;
    });
  });
}

// ── DATE HELPERS ──────────────────────────────────────────────────────
function timeAgo(dateString) {
  const date  = new Date(dateString);
  const now   = new Date();
  const diff  = Math.floor((now - date) / 1000);
  if (diff < 60)   return 'Just now';
  if (diff < 3600) return Math.floor(diff / 60) + ' min ago';
  if (diff < 86400)return Math.floor(diff / 3600) + ' hr ago';
  return Math.floor(diff / 86400) + ' days ago';
}

// Apply timeAgo to all .notif-time elements
document.querySelectorAll('.notif-time[data-time]').forEach(el => {
  el.textContent = timeAgo(el.dataset.time);
});

// ── ESCAPE HELPER ─────────────────────────────────────────────────────
function escHtml(str) {
  const d = document.createElement('div');
  d.textContent = str;
  return d.innerHTML;
}

// ── INIT ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initTagLookup();
  animateGrowthBars();

  // Active nav item highlight from URL
  const path = window.location.pathname;
  document.querySelectorAll('.sb-item[href]').forEach(link => {
    if (link.getAttribute('href') === path) {
      link.classList.add('active');
    }
  });
});
