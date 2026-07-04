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

  // ── Client-side table search ─────────────────────────────────────
  document.querySelectorAll('[data-search-table]').forEach(function(input){
    const tableId = input.dataset.searchTable;
    const table   = document.getElementById(tableId);
    if (!table) return;
    input.addEventListener('input', function(){
      const q = this.value.toLowerCase().trim();
      table.querySelectorAll('tbody tr').forEach(function(row){
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
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
})();
