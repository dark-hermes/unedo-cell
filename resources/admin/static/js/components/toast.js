Livewire.on('show-toast', e => {
  const data = Array.isArray(e) ? e[0] : e;

  Toastify({
      text: data.message ?? 'No message!',
      duration: 3000,
      close: true,
      gravity: 'top',
      position: 'right',
      backgroundColor: data.type === 'error' ? '#e74c3c' : '#4fbe87',
  }).showToast();
});
