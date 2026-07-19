// MD Goatco Farm — global loading indicator
//
// This app has no SPA framework — every action (link click, form submit) is
// a full page navigation. On a slow or flaky connection that can leave the
// UI looking frozen with no feedback. This module adds:
//   1. A top progress bar that starts on any internal link click or form
//      submit, giving instant feedback that something is happening.
//   2. A spinner + disabled state on the button that triggered a form
//      submit, so it can't be double-clicked while the request is in flight.
//   3. A minimum hold time before a form's real submission fires, so on a
//      fast connection the loading state is actually visible for a moment
//      instead of being cut short by an near-instant redirect.
//   4. A safety timeout that releases the UI if navigation never completes
//      (e.g. the connection drops), so the user isn't stuck and can retry
//      instead of the page just looking permanently broken.
//
// Opt out of any of this on a specific element with data-no-loader.
(function () {
  "use strict";

  var SAFETY_TIMEOUT_MS = 20000;
  var MIN_HOLD_MS = 600; // how long the loading state stays visible before a form really submits

  var bar = document.createElement("div");
  bar.id = "global-loader-bar";
  (document.body || document.documentElement).appendChild(bar);

  var safetyTimer = null;
  var releasedButtons = [];

  function show() {
    bar.classList.remove("done");
    // Force a reflow so re-triggering "active" restarts the width transition
    // even if a previous click already started it.
    void bar.offsetWidth; // eslint-disable-line no-unused-expressions
    bar.classList.add("active");

    clearTimeout(safetyTimer);
    safetyTimer = setTimeout(release, SAFETY_TIMEOUT_MS);
  }

  function release() {
    clearTimeout(safetyTimer);
    bar.classList.remove("active", "done");
    releasedButtons.forEach(function (btn) {
      btn.disabled = false;
      btn.classList.remove("is-loading");
      if (btn.dataset.loaderOriginalText !== undefined) {
        btn.textContent = btn.dataset.loaderOriginalText;
      }
    });
    releasedButtons = [];
  }

  function isDownloadLink(href) {
    return href.indexOf("/export") !== -1 || href.indexOf("/download") !== -1;
  }

  // ── Forms ────────────────────────────────────────────────────────────
  // Holds the real submission for MIN_HOLD_MS so the loading state is
  // actually perceptible, then re-submits for real via requestSubmit()
  // (which — unlike form.submit() — preserves which button triggered it,
  // including any name/value pair, and re-runs native validation).
  document.addEventListener(
    "submit",
    function (e) {
      var form = e.target;
      if (!(form instanceof HTMLFormElement)) return;
      if (form.hasAttribute("data-no-loader")) return;

      if (form.dataset.loaderState === "releasing") {
        // This is our own delayed resubmit — let it through for real.
        delete form.dataset.loaderState;
        return;
      }
      if (form.dataset.loaderState === "pending") {
        // Already queued (repeat click/Enter while holding) — ignore it.
        e.preventDefault();
        return;
      }

      show();

      var submitBtn = e.submitter || form.querySelector('[type="submit"]');
      if (submitBtn) {
        submitBtn.dataset.loaderOriginalText = submitBtn.textContent;
        submitBtn.classList.add("is-loading");
        submitBtn.disabled = true;
        releasedButtons.push(submitBtn);
      }

      e.preventDefault();
      form.dataset.loaderState = "pending";
      setTimeout(function () {
        form.dataset.loaderState = "releasing";
        if (form.requestSubmit) {
          form.requestSubmit(submitBtn && submitBtn.disabled ? null : submitBtn);
        } else {
          form.submit();
        }
      }, MIN_HOLD_MS);
    },
    true,
  );

  // ── Links ────────────────────────────────────────────────────────────
  document.addEventListener("click", function (e) {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

    var link = e.target.closest("a[href]");
    if (!link || link.hasAttribute("data-no-loader")) return;
    if (link.target && link.target !== "_self") return;

    var href = link.getAttribute("href");
    if (!href || /^(#|mailto:|tel:|javascript:)/i.test(href)) return;
    if (isDownloadLink(href)) return; // file downloads don't navigate away

    show();
  });

  // If the page is restored from the browser's back/forward cache, any
  // loader left showing from before navigating away would otherwise be
  // stuck visible.
  window.addEventListener("pageshow", release);
})();
