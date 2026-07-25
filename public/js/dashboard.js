// MD Goatco Farm — Dashboard JS
(function(){
  'use strict';

  // ── Sidebar toggle (mobile) ─────────────────────────────────────
  const sidebar      = document.getElementById('sidebar');
  const sidebarClose = document.getElementById('sidebarClose');
  const sidebarToggle= document.getElementById('sidebarToggle');
  const overlay      = document.getElementById('sbOverlay');

  function openSidebar(){
    sidebar?.classList.add('open');
    overlay?.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar(){
    sidebar?.classList.remove('open');
    overlay?.classList.remove('open');
    document.body.style.overflow = '';
  }

  sidebarToggle?.addEventListener('click', openSidebar);
  sidebarClose?.addEventListener('click', closeSidebar);
  overlay?.addEventListener('click', closeSidebar);

  // ── Confirm dialogs on buttons with data-confirm ─────────────────
  document.addEventListener('click', function(e){
    const btn = e.target.closest('[data-confirm]');
    if (!btn) return;
    if (!confirm(btn.dataset.confirm)) e.preventDefault();
  });

  // ── Auto-dismiss flash messages after 5 seconds ──────────────────
  document.querySelectorAll('.flash').forEach(function(el){
    setTimeout(function(){ el.style.opacity='0'; el.style.transition='opacity .4s'; setTimeout(function(){ el.remove(); }, 400); }, 5000);
  });

  // ── Active sidebar item from current URL ─────────────────────────
  const currentPath = window.location.pathname.replace(/^\//, '');
  document.querySelectorAll('.sb-item').forEach(function(link){
    const href = link.getAttribute('href') || '';
    const linkPath = href.replace(/^\//, '');
    if (linkPath && currentPath.startsWith(linkPath) && linkPath !== 'dashboard') {
      link.classList.add('active');
    } else if (linkPath === 'dashboard' && currentPath === 'dashboard') {
      link.classList.add('active');
    }
  });

  // ── User menu dropdown (topbar) ───────────────────────────────────
  const userMenu        = document.getElementById('userMenu');
  const userMenuTrigger  = document.getElementById('userMenuTrigger');

  function closeUserMenu(){
    userMenu?.classList.remove('open');
    userMenuTrigger?.setAttribute('aria-expanded', 'false');
  }

  userMenuTrigger?.addEventListener('click', function(e){
    e.stopPropagation();
    const isOpen = userMenu.classList.toggle('open');
    userMenuTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  // ── Notification bell dropdown (topbar) ────────────────────────────
  const notifBell        = document.getElementById('notifBell');
  const notifBellTrigger = document.getElementById('notifBellTrigger');

  function closeNotifBell(){
    notifBell?.classList.remove('open');
    notifBellTrigger?.setAttribute('aria-expanded', 'false');
  }

  notifBellTrigger?.addEventListener('click', function(e){
    e.stopPropagation();
    const isOpen = notifBell.classList.toggle('open');
    notifBellTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  document.addEventListener('click', function(e){
    if (userMenu && !userMenu.contains(e.target)) closeUserMenu();
    if (notifBell && !notifBell.contains(e.target)) closeNotifBell();
  });

  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') { closeUserMenu(); closeNotifBell(); }
  });

  // ── Import widget: close when clicking outside ────────────────────
  document.addEventListener('click', function(e){
    document.querySelectorAll('details.import-widget[open]').forEach(function(d){
      if (!d.contains(e.target)) d.removeAttribute('open');
    });
  });

  // ── Skeleton loading rows ───────────────────────────────────────────
  // The app has no SPA data layer — search/pagination/bulk actions all
  // trigger a real page reload. Swapping each table's rows for shimmering
  // placeholders the instant that reload is triggered (rather than leaving
  // the old rows sitting static) gives the same "something is happening"
  // signal loader.js's top bar gives, but localized to the content that's
  // actually about to change.
  function showTableSkeletons(scope){
    (scope || document).querySelectorAll('.table-scroll table').forEach(function(table){
      const colCount = table.querySelectorAll('thead th').length || 4;
      const tbody = table.querySelector('tbody');
      if (!tbody) return;
      let rows = '';
      for (let r = 0; r < 5; r++){
        rows += '<tr class="skeleton-row">';
        for (let c = 0; c < colCount; c++){
          rows += '<td><div class="skeleton-bar' + (c === colCount - 1 ? ' short' : '') + '"></div></td>';
        }
        rows += '</tr>';
      }
      tbody.innerHTML = rows;
    });
  }

  // Pagination links and bulk-action submits are the other two places a
  // table's contents are about to be replaced by a fresh page load. Checked
  // after the data-confirm handler above (registration order): if the user
  // cancelled that dialog, e.defaultPrevented is set and nothing should
  // navigate away, so skip the skeleton too — otherwise a cancelled bulk
  // action would leave the table stuck showing placeholder rows forever.
  document.addEventListener('click', function(e){
    if (e.defaultPrevented) return;

    const pagerLink = e.target.closest('.pager-link:not(.pager-disabled):not(.pager-current)');
    if (pagerLink) showTableSkeletons(pagerLink.closest('.card') || document);

    const bulkBtn = e.target.closest('[data-bulk-submit]');
    if (bulkBtn && !bulkBtn.disabled) showTableSkeletons(bulkBtn.closest('.card') || document);
  });

  // ── Real-time table search (debounced auto-submit) ────────────────
  // No explicit "Search" button — the GET form submits automatically a
  // moment after the user stops typing. Enter still submits immediately
  // (native browser behavior, independent of this).
  const SEARCH_DEBOUNCE_MS = 500;
  document.querySelectorAll('.search-input').forEach(function(input){
    let timer = null;
    input.addEventListener('input', function(){
      clearTimeout(timer);
      timer = setTimeout(function(){
        const form = input.closest('form');
        if (!form) return;
        showTableSkeletons(form.closest('.card') || document);
        form.requestSubmit ? form.requestSubmit() : form.submit();
      }, SEARCH_DEBOUNCE_MS);
    });
  });

  // ── Bulk action buttons: set the real form action on click ─────────
  // loader.js holds every submit for MIN_HOLD_MS then re-fires it via
  // form.requestSubmit(submitter) — but a submitter whose `form=""` points
  // at a form it isn't nested inside (our detached bulk form) doesn't
  // reliably carry its `formaction` through that delayed re-dispatch, so
  // two buttons sharing one form both end up posting to the form's default
  // action. Setting the form's actual `action` here, before any of that
  // happens, sidesteps the issue entirely.
  document.querySelectorAll('[data-bulk-submit]').forEach(function(btn){
    btn.addEventListener('click', function(){
      const form = document.getElementById(btn.getAttribute('form'));
      const url = btn.getAttribute('formaction');
      if (form && url) form.action = url;
    });
  });

  // ── Bulk selection (checkboxes + floating action bar) ──────────────
  // The bulk-bar and its table live inside the same `.card`, each marked
  // `[data-bulk-scope]`, but the checkboxes/buttons submit to a *detached*
  // <form> elsewhere via the HTML `form="..."` attribute (kept separate so
  // it never nests inside the per-row action forms in the table). Scoping
  // by `.card` (rather than `closest('form')`) is what lets that work.
  document.querySelectorAll('.bulk-select-all').forEach(function(master){
    const scope = master.closest('.card') || document;
    const bar = scope.querySelector('.bulk-bar');
    const countEl = scope.querySelector('.bulk-count');
    const actionBtns = bar ? bar.querySelectorAll('[data-bulk-submit]') : [];

    function boxes(){ return scope.querySelectorAll('.bulk-checkbox'); }

    function refresh(){
      const all = boxes();
      const checked = Array.prototype.filter.call(all, function(b){ return b.checked; });
      if (bar) bar.classList.toggle('visible', checked.length > 0);
      if (countEl) countEl.textContent = checked.length + ' selected';
      actionBtns.forEach(function(btn){ btn.disabled = checked.length === 0; });
      master.checked = all.length > 0 && checked.length === all.length;
      master.indeterminate = checked.length > 0 && checked.length < all.length;
    }

    master.addEventListener('change', function(){
      boxes().forEach(function(b){ b.checked = master.checked; });
      refresh();
    });
    scope.addEventListener('change', function(e){
      if (e.target.classList && e.target.classList.contains('bulk-checkbox')) refresh();
    });
    refresh();
  });

  // ── Responsive tables ─────────────────────────────────────────────
  // Wrap every table in a horizontally-scrollable container so wide
  // tables become swipeable on mobile instead of squishing illegibly.
  // Done here (once, globally) rather than per-view so every listing
  // page gets this automatically, including any added later.
  document.querySelectorAll('table').forEach(function(table){
    if (table.parentElement && table.parentElement.classList.contains('table-scroll')) return;
    const wrapper = document.createElement('div');
    wrapper.className = 'table-scroll';
    table.parentNode.insertBefore(wrapper, table);
    wrapper.appendChild(table);
  });
})();
