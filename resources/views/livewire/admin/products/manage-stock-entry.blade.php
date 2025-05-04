<div>
    <livewire:admin.partials.page-heading 
        title="Tambah Stok Masuk" 
        :breadcrumbs="[['label' => 'Produk', 'href' => '/admin/products'], ['label' => 'Stok Masuk']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form>
                    {{-- received_at, source, quantity, note --}}
                    <div class="form-group">
                        <label for="received_at">Tanggal Diterima</label>
                        <input wire:model.defer="received_at" id="received_at" type="date" class="form-control @error('received_at') is-invalid @enderror">
                        @error('received_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="source">Sumber</label>
                        {{-- select option --}}
                        <select wire:model.defer="source" id="source" class="form-control @error('source') is-invalid @enderror">
                            <option value="">-- Pilih Sumber --</option>
                            <option value="purchase">Pembelian</option>
                            <option value="return">Retur</option>
                            <option value="other">Lainnya</option>
                        </select>
                        @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <a href="{{ route('admin.products.stock-entries.index', ['product' => $product->id]) }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
