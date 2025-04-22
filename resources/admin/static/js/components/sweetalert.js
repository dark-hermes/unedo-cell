document.addEventListener('DOMContentLoaded', function () {

    // Swal biasa
    Livewire.on('swal', ({
        title,
        text,
        icon
    }) => {
        Swal.fire({
            title,
            text,
            icon
        });
    });

    // Swal konfirmasi

    Livewire.on('swal:confirm', ([data]) => {
        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.icon,
            showCancelButton: true,
            confirmButtonText: data.confirmButtonText || 'OK',
            cancelButtonText: data.cancelButtonText || 'Batal'
        }).then((result) => {
            if (result.isConfirmed && data.method) {
                Livewire.dispatch(data.method, data.params ?? null);
            }
        });
    });




});
