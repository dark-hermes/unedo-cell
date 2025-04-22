<div>
    <livewire:admin.partials.page-heading title="Buat Akun Admin" subtitle="" :breadcrumbs="[['label' => 'Admins', 'href' => '/admin/admins'], ['label' => 'Create']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="name" id="name" placeholder="Masukkan Nama" name="name" required
                                    wire:model.defer="name" class="form-control @error('name') is-invalid @enderror" >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" placeholder="Masukkan Email" name="email" required
                                    wire:model.defer="email" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">No HP</label>
                                <input id="phone" placeholder="Masukkan No. HP" name="phone"
                                    wire:model.defer="phone" class="form-control @error('phone') is-invalid @enderror"
                                    type="text" data-inputmask="'mask': ['9999-9999-9999']">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" placeholder="Masukkan Password" name="password" required
                                    wire:model.defer="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password-confirmation">Konfirmasi Password</label>
                                <input type="password" id="password-confirmation" placeholder="Masukkan Password" name="password_confirmation" required
                                    wire:model.defer="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="button-group mt-4">
                                <button type="button" wire:click="store" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
