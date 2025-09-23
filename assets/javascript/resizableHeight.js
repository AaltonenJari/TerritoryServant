// assets/javascript/resizableHeight.js
(function (global) {
  /**
   * Aktivoi korkeuden tallennuksen annetulle elementille.
   *
   * @param {string|HTMLElement} target - CSS-selektori tai elementti
   * @param {string} saveUrl - Url, johon korkeus POSTataan
   */
  function enableResizableSave(target, saveUrl) {
    const el = (typeof target === 'string') ? document.querySelector(target) : target;
    if (!el || !saveUrl) return;

    let lastHeight = el.offsetHeight;

    const observer = new ResizeObserver(entries => {
      for (const entry of entries) {
        const newHeight = Math.round(entry.contentRect.height);
        if (newHeight !== lastHeight) {
          lastHeight = newHeight;
          fetch(saveUrl, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "height=" + newHeight
          }).catch(console.error);
        }
      }
    });

    observer.observe(el);
  }

  // Viedään funktio globaaliin käyttöön
  global.enableResizableSave = enableResizableSave;
})(window);
