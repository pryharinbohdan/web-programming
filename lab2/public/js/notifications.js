document.querySelectorAll('[data-component="notification-center"]').forEach((root) => {
  const badge = root.querySelector("[data-notification-count]");
  const empty = root.querySelector("[data-notification-empty]");

  const refresh = () => {
    const unread = root.querySelectorAll(".notification-menu__item--unread").length;
    badge.textContent = unread;
    badge.style.display = unread ? "" : "none";
    badge.setAttribute("aria-label", unread + " нових сповіщень");
    empty.hidden = unread > 0;
  };

  root.addEventListener("click", (event) => {
    const button = event.target.closest("[data-action]");
    if (!button) return;

    if (button.dataset.action === "mark-read") {
      button.closest("[data-notification-id]").classList.remove("notification-menu__item--unread");
    }
    if (button.dataset.action === "mark-all-read") {
      root.querySelectorAll(".notification-menu__item--unread")
        .forEach((item) => item.classList.remove("notification-menu__item--unread"));
    }
    refresh();
  });

  refresh();
});
