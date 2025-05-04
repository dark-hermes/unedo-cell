// document.addEventListener('DOMContentLoaded', function () {

    // Swal biasa
    Livewire.on('swal', e => {
        const data = Array.isArray(e) ? e[0] : e;
        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.icon,
            confirmButtonColor: data.confirmButtonColor || '#f0c034',
            confirmButtonText: data.confirmButtonText || 'OK',
            cancelButtonText: data.cancelButtonText || 'Batal'
        })
    }
    );

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

// });
