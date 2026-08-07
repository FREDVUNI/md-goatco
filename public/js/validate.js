// MD Goatco Farm — client-side form validation
//
// Upgrades plain HTML5 `required`/`type=email`/etc. validation (which only
// shows an inconsistent browser tooltip, and silently does nothing useful
// for fields hidden inside a multi-step form) into inline, styled error
// messages with real-time feedback as the user fixes each field.
//
// Server-side validation remains authoritative — this is UX only.
//
// Opt a whole form out with data-no-validate. Supported per-field extras:
//   data-match="otherFieldId"        — value must equal another field's value
//   data-match-message="..."         — custom message for the above
//   data-max-size="5242880"          — max file size in bytes (file inputs)
//   data-pattern-message="..."       — custom message for a pattern mismatch
//
// Loads before loader.js so its capture-phase "submit" listener runs first
// and can stop propagation to block the loader when a form is invalid.
(function () {
  "use strict";

  var MESSAGES = {
    valueMissing: function (field) {
      if (field.type === "checkbox") return "Please check this box to continue.";
      if (field.type === "file") return "Please choose a file to upload.";
      if (field.tagName === "SELECT") return "Please select an option.";
      return "This field is required.";
    },
    typeMismatch: function (field) {
      if (field.type === "email") return "Enter a valid email address (e.g. name@example.com).";
      if (field.type === "url") return "Enter a valid URL.";
      return "Enter a valid value.";
    },
    tooShort: function (field) {
      return "Must be at least " + field.minLength + " characters.";
    },
    tooLong: function (field) {
      return "Must be no more than " + field.maxLength + " characters.";
    },
    rangeUnderflow: function (field) {
      return "Must be at least " + field.min + ".";
    },
    rangeOverflow: function (field) {
      return "Must be no more than " + field.max + ".";
    },
    stepMismatch: function () {
      return "Enter a valid value.";
    },
    patternMismatch: function (field) {
      return field.dataset.patternMessage || field.title || "Please match the requested format.";
    },
    badInput: function (field) {
      return field.type === "number" ? "Enter a valid number." : "Enter a valid value.";
    },
  };

  var VALIDITY_ORDER = [
    "valueMissing", "typeMismatch", "tooShort", "tooLong",
    "rangeUnderflow", "rangeOverflow", "stepMismatch", "patternMismatch", "badInput",
  ];

  function isValidatable(field) {
    if (!field.name || field.disabled) return false;
    var t = field.type;
    return t !== "hidden" && t !== "submit" && t !== "button" && t !== "reset";
  }

  function fieldContainer(field) {
    return field.closest(".field") || field.closest(".checkbox-label") || field.closest("label") || field.parentElement;
  }

  var counter = 0;
  function fieldKey(field) {
    if (!field.dataset.validateKey) field.dataset.validateKey = (field.name || field.id || "f") + "-" + counter++;
    return field.dataset.validateKey;
  }

  function clearError(field) {
    var container = fieldContainer(field);
    field.classList.remove("has-error");
    if (container) container.classList.remove("has-error");
    if (field.type === "file") {
      var lbl = field.previousElementSibling;
      if (lbl && lbl.classList && lbl.classList.contains("file-upload-label")) lbl.classList.remove("has-error");
    }
    var msg = document.getElementById("err-" + fieldKey(field));
    if (msg) msg.remove();
  }

  function showError(field, message) {
    clearError(field);
    var container = fieldContainer(field);
    field.classList.add("has-error");
    if (container) container.classList.add("has-error");

    var anchor = field;
    if (field.type === "file") {
      var preview = document.getElementById("preview-" + field.id);
      anchor = preview || field;
      var lbl = field.previousElementSibling;
      if (lbl && lbl.classList && lbl.classList.contains("file-upload-label")) lbl.classList.add("has-error");
    } else if (field.type === "checkbox" || field.type === "radio") {
      anchor = container || field;
    }

    var msg = document.createElement("div");
    msg.className = "field-error-msg";
    msg.id = "err-" + fieldKey(field);
    var icon = document.createElement("i");
    icon.className = "fas fa-exclamation-circle";
    var span = document.createElement("span");
    span.textContent = message;
    msg.appendChild(icon);
    msg.appendChild(span);
    anchor.insertAdjacentElement("afterend", msg);
  }

  function fileErrorMessage(field) {
    var file = field.files && field.files[0];
    if (!file) return null;
    var maxSize = parseInt(field.dataset.maxSize || "5242880", 10);
    if (file.size > maxSize) {
      return "File is too large (max " + Math.round(maxSize / 1024 / 1024) + " MB).";
    }
    var accept = field.getAttribute("accept");
    if (accept) {
      var accepts = accept.split(",").map(function (s) { return s.trim().toLowerCase(); });
      var name = file.name.toLowerCase();
      var type = (file.type || "").toLowerCase();
      var ok = accepts.some(function (a) {
        if (a.indexOf("/*") !== -1) return type.indexOf(a.replace("/*", "/")) === 0;
        if (a.indexOf(".") === 0) return name.slice(-a.length) === a;
        return type === a;
      });
      if (!ok) return "Unsupported file type.";
    }
    return null;
  }

  function validateField(field) {
    if (!isValidatable(field)) return true;

    if (field.type === "file") {
      if (field.files.length === 0) {
        if (field.hasAttribute("required")) { showError(field, MESSAGES.valueMissing(field)); return false; }
        clearError(field);
        return true;
      }
      var fileErr = fileErrorMessage(field);
      if (fileErr) { showError(field, fileErr); return false; }
    }

    if (field.dataset.match) {
      var other = document.getElementById(field.dataset.match);
      if (other && field.value !== other.value) {
        showError(field, field.dataset.matchMessage || "This does not match.");
        return false;
      }
    }

    // A malformed `pattern`/regex-based constraint can make checkValidity()
    // throw instead of just failing (browser engines vary here) — treat
    // that as "valid" rather than letting one bad field wedge every form
    // on the page silently.
    var isValid = true;
    try {
      isValid = typeof field.checkValidity !== "function" || field.checkValidity();
    } catch (err) {
      isValid = true;
    }
    if (! isValid) {
      var v = field.validity;
      var key = VALIDITY_ORDER.find(function (k) { return v[k]; });
      var message = (key && MESSAGES[key]) ? MESSAGES[key](field) : (field.validationMessage || "Enter a valid value.");
      showError(field, message);
      return false;
    }

    clearError(field);
    return true;
  }

  function validateFields(fields) {
    var ok = true;
    Array.prototype.forEach.call(fields, function (f) {
      if (isValidatable(f) && !validateField(f)) ok = false;
    });
    return ok;
  }

  function validateForm(form) {
    var fields = form.querySelectorAll("input, select, textarea");
    var firstInvalid = null;
    Array.prototype.forEach.call(fields, function (field) {
      if (!isValidatable(field)) return;
      var ok = validateField(field);
      if (!ok && !firstInvalid) firstInvalid = field;
    });
    return firstInvalid;
  }

  function attachLiveValidation(form) {
    form.querySelectorAll("input, select, textarea").forEach(function (field) {
      if (!isValidatable(field)) return;
      field.addEventListener("blur", function () {
        field.dataset.touched = "1";
        validateField(field);
      });
      field.addEventListener("input", function () {
        if (field.dataset.touched) validateField(field);
      });
      field.addEventListener("change", function () {
        if (field.type === "file" || field.tagName === "SELECT" || field.type === "checkbox") {
          field.dataset.touched = "1";
          validateField(field);
        }
      });
    });
    form.querySelectorAll("[data-match]").forEach(function (field) {
      var other = document.getElementById(field.dataset.match);
      if (other) {
        other.addEventListener("input", function () {
          if (field.dataset.touched) validateField(field);
        });
      }
    });
  }

  document.querySelectorAll("form").forEach(function (form) {
    if (form.hasAttribute("data-no-validate")) return;
    form.setAttribute("novalidate", "");
    attachLiveValidation(form);
  });

  document.addEventListener(
    "submit",
    function (e) {
      var form = e.target;
      if (!(form instanceof HTMLFormElement)) return;
      if (form.hasAttribute("data-no-validate")) return;
      // Our own delayed resubmit dispatched by loader.js — already validated
      // the first time around, let it through untouched.
      if (form.dataset.loaderState === "releasing") return;

      var firstInvalid = validateForm(form);
      if (firstInvalid) {
        e.preventDefault();
        e.stopImmediatePropagation(); // also blocks loader.js's handler
        form.dispatchEvent(new CustomEvent("validate:invalid", { detail: { field: firstInvalid }, bubbles: true }));
        if (firstInvalid.offsetParent !== null) {
          firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
          firstInvalid.focus({ preventScroll: true });
        }
      }
    },
    true,
  );

  window.MDValidate = { validateField: validateField, validateFields: validateFields };

  // ── Show/hide toggle for password fields ──────────────────────────
  // Wraps every <input type="password"> in a positioned container and
  // adds an eye/eye-slash button that flips the input's type. Runs on
  // every page that loads this script (both the auth and dashboard
  // layouts), so no per-view markup is needed.
  document.querySelectorAll('input[type="password"]').forEach(function (input) {
    if (input.closest(".pw-toggle-wrap")) return; // already wrapped

    // Password managers (LastPass, 1Password, Bitwarden, Dashlane) inject
    // their own icon inside password fields, which collides/overlaps with
    // our toggle button and makes it look like it "jumps". These attributes
    // are each manager's documented opt-out for a specific field.
    input.setAttribute("data-lpignore", "true");
    input.setAttribute("data-1p-ignore", "true");
    input.setAttribute("data-bwignore", "true");
    input.setAttribute("data-dashlane-ignore", "true");

    var wrap = document.createElement("div");
    wrap.className = "pw-toggle-wrap";
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);

    var btn = document.createElement("button");
    btn.type = "button";
    btn.className = "pw-toggle-btn";
    btn.setAttribute("aria-label", "Show password");
    btn.tabIndex = -1;
    btn.innerHTML = '<i class="fas fa-eye"></i>';
    wrap.appendChild(btn);

    btn.addEventListener("click", function () {
      var showing = input.type === "text";
      input.type = showing ? "password" : "text";
      btn.innerHTML = showing ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
      btn.setAttribute("aria-label", showing ? "Show password" : "Hide password");
    });
  });
})();
