window.addEventListener('show-toast', event => {
  Toastify({
    text: event.detail.message,
    duration: 3000,
    close: true,
    gravity: "top",
    position: "right",
    backgroundColor: "#4fbe87",
  }).showToast();
});
