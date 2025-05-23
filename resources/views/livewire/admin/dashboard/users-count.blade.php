<div class="card">
    <div class="card-body px-4 py-4-5">
        <div class="row align-items-center">
            <!-- Ikon -->
            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                <div class="stats-icon grey mb-2 d-flex justify-content-center align-items-center"
                    style="width: 50px; height: 50px; border-radius: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
            </div>

            <!-- Teks -->
            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                <h6 class="text-muted font-semibold">Jumlah Pengguna</h6>
                <h6 class="font-extrabold mb-0">{{ $usersCount }}</h6>
            </div>
        </div>
    </div>

    <style>
        .stats-icon.grey {
            background-color: #e0e0e0;
        }

        .stats-icon.grey svg {
            fill: white;
        }
    </style>
</div>
