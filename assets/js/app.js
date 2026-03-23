(() => {
  const LOADING_ID = "app-loading";
  const NAV_DELAY_MS = 1300; // keep loading visible "a bit longer" before navigation
  const MIN_VISIBLE_MS = 1300; // for bfcache/back nav: keep at least a bit

  const getLoadingEl = () => document.getElementById(LOADING_ID);

  let visibleSince = null;

  const showLoadingNow = () => {
    const el = getLoadingEl();
    if (!el) return;
    if (el.style.display === "flex") return;
    visibleSince = Date.now();
    el.style.display = "flex";
  };

  const requestShowLoading = () => showLoadingNow();

  const setElementLoadingState = (el) => {
    if (!(el instanceof Element)) return;
    if (el.getAttribute("data-no-loading") === "true") return;
    if (el.getAttribute("data-loading-applied") === "true") return;

    const loadingText = el.getAttribute("data-loading-text") || "Loading...";

    // Persist original content to restore on back navigation (bfcache) or if needed
    if (!el.getAttribute("data-loading-original")) {
      if (el instanceof HTMLInputElement) {
        el.setAttribute("data-loading-original", el.value || "");
      } else {
        el.setAttribute("data-loading-original", el.textContent || "");
      }
    }

    el.setAttribute("data-loading-applied", "true");
    el.setAttribute("aria-busy", "true");

    // Disable interactions
    if (el instanceof HTMLButtonElement) {
      el.disabled = true;
      el.dataset.loadingDisabled = "true";
    } else if (el instanceof HTMLAnchorElement) {
      el.setAttribute("aria-disabled", "true");
      el.style.pointerEvents = "none";
      el.style.cursor = "default";
    } else if (el instanceof HTMLInputElement) {
      el.disabled = true;
      el.setAttribute("data-loading-disabled", "true");
    } else {
      el.style.pointerEvents = "none";
      el.style.cursor = "default";
    }

    // Swap label
    if (el instanceof HTMLInputElement) {
      el.value = loadingText;
    } else {
      el.textContent = loadingText;
    }
  };

  const restoreLoadingStateElements = () => {
    document.querySelectorAll("[data-loading-applied='true']").forEach((el) => {
      if (!(el instanceof Element)) return;
      const original = el.getAttribute("data-loading-original");

      el.removeAttribute("data-loading-applied");
      el.removeAttribute("aria-busy");

      if (el instanceof HTMLButtonElement) {
        if (el.dataset.loadingDisabled === "true") el.disabled = false;
        el.removeAttribute("data-loading-disabled");
        if (original != null) el.textContent = original;
      } else if (el instanceof HTMLAnchorElement) {
        el.removeAttribute("aria-disabled");
        el.style.pointerEvents = "";
        el.style.cursor = "";
        if (original != null) el.textContent = original;
      } else if (el instanceof HTMLInputElement) {
        el.disabled = false;
        el.removeAttribute("data-loading-disabled");
        if (original != null) el.value = original;
      } else {
        el.style.pointerEvents = "";
        el.style.cursor = "";
        if (original != null) el.textContent = original;
      }
    });
  };

  const hideLoadingSoon = () => {
    const el = getLoadingEl();
    if (!el) return;
    if (el.style.display !== "flex") return;
    const elapsed = visibleSince ? Date.now() - visibleSince : MIN_VISIBLE_MS;
    const wait = Math.max(0, MIN_VISIBLE_MS - elapsed);
    window.setTimeout(() => {
      el.style.display = "none";
      visibleSince = null;
    }, wait);
  };

  const isModifiedClick = (e) => e.metaKey || e.ctrlKey || e.shiftKey || e.altKey;

  const shouldHandleLink = (a, e) => {
    if (!a) return false;
    if (a.hasAttribute("download")) return false;
    if (a.getAttribute("target") && a.getAttribute("target") !== "_self") return false;
    if (isModifiedClick(e)) return false;
    const href = a.getAttribute("href") || "";
    if (!href || href.startsWith("#") || href.startsWith("javascript:")) return false;
    return true;
  };

  document.addEventListener("submit", (e) => {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;
    if (form.dataset.noLoading === "true") return;

    // prevent immediate navigation so the user can see loading
    e.preventDefault();

    // prevent accidental double submit + show "Loading..." on the submit button
    const submitter = e.submitter;
    if (submitter) setElementLoadingState(submitter);
    requestShowLoading();
    window.setTimeout(() => {
      try {
        form.submit();
      } catch (_) {
        // ignore
      }
    }, NAV_DELAY_MS);
  });

  document.addEventListener("click", (e) => {
    const a = e.target instanceof Element ? e.target.closest("a") : null;
    if (!a) return;
    if (a.dataset.noLoading === "true") return;
    if (!shouldHandleLink(a, e)) return;

    const href = a.getAttribute("href") || "";
    // allow browser defaults for external protocols
    if (/^(mailto:|tel:)/i.test(href)) return;

    // delay navigation so loading is visible
    e.preventDefault();
    setElementLoadingState(a);
    requestShowLoading();
    window.setTimeout(() => {
      window.location.href = href;
    }, NAV_DELAY_MS);
  });

  window.addEventListener("pageshow", () => {
    hideLoadingSoon();
    // restore button/link labels if user navigated back
    restoreLoadingStateElements();
  });

  window.appShowLoading = requestShowLoading;
})();

