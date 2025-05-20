<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <div class="text-green-500 text-6xl mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h1>
        <p class="text-gray-600 mb-6">Terima kasih telah menyelesaikan pembayaran.</p>
        
        <div class="flex justify-center mb-6">
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-500 h-2.5 rounded-full animate-[progress_5s_linear_forwards]" 
                     style="width: 100%"></div>
            </div>
        </div>
        
        <p class="text-sm text-gray-500">
            Anda akan diarahkan ke halaman riwayat pesanan dalam <span id="countdown">5</span> detik...
        </p>
        
        <div class="mt-6">
            <a href="{{ route('orders.history') }}" 
               class="text-green-600 hover:text-green-800 font-medium">
                <i class="fas fa-arrow-right mr-1"></i> Klik di sini jika tidak otomatis redirect
            </a>
        </div>
    </div>

    <script>
        // Countdown timer
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = "{{ route('orders.history') }}";
            }
        }, 1000);
    </script>

    <style>
        @keyframes progress {
            to {
                width: 0%;
            }
        }
        .animate-\[progress_5s_linear_forwards\] {
            animation: progress 5s linear forwards;
        }
    </style>
</body>
</html>