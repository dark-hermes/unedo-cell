<div>
    <livewire:admin.partials.page-heading 
        title="Tambah Stok Keluar" 
        :breadcrumbs="[['label' => 'Produk', 'href' => '/admin/products'], ['label' => 'Stok Keluar']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="output_date">Tanggal Keluar</label>
                        <input wire:model.defer="output_date" id="output_date" type="date" class="form-control @error('output_date') is-invalid @enderror">
                        @error('output_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="reason">Alasan</label>
                        <select wire:model.defer="reason" id="reason" class="form-control @error('reason') is-invalid @enderror">
                            <option value="">-- Pilih Alasan --</option>
                            <option value="sale">Penjualan</option>
                            <option value="return">Retur</option>
                            <option value="broken">Rusak</option>
                            <option value="missing">Hilang</option>
                            <option value="other">Lainnya</option>
                        </select>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="quantity">Jumlah</label>
                        <input wire:model.defer="quantity" id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror">
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="note">Catatan</label>
                        <textarea wire:model.defer="note" id="note" class="form-control @error('note') is-invalid @enderror"></textarea>
                        @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="button-group mt-4">
                        <button type="button" class="btn btn-primary" wire:click.prevent="store">Simpan</button>
                        <a href="{{ route('admin.products.stock-outputs.index', ['product' => $product->id]) }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
