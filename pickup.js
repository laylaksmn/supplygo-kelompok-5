document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const market_id = urlParams.get("market_id");
  window.changeQty = function (change) {
    const qtyEl = document.getElementById("qty");
    if (!qtyEl) {
      return;
    }
    let qty = parseInt(qtyEl.textContent);
    qty = qty + change;

    if (qty < 1) {
      qty = 1;
    }
    window.location.href = "?qty=" + qty + "&market_id=" + market_id;
  };

  const sendBtn = document.getElementById("sendBtn");

  if (sendBtn) {
    sendBtn.disabled = false;
  }

  if (sendBtn) {
    sendBtn.addEventListener("click", (e) => {
      const qtyEl = document.getElementById("qty");
      const qty = parseInt(qtyEl.textContent);

      window.location.href = "?qty=" + qty + "&market_id=" + market_id + "&send=true";
    });
  }
});