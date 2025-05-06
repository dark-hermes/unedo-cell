<div class="bg0 p-t-15 p-b-85">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-8 m-lr-auto m-b-50">
                <div class="bor10 p-lr-50 p-t-30 p-b-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 cl2 p-b-30 text-center">
                        Formulir reparasi
                    </h4>

                    @if (session('message'))
                        <div class="alert alert-success mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submit">
                        <div class="flex-w flex-t bor12 p-b-13">
                            <div class="size-208">
                                <label class="stext-110 cl2">Nama barang:</label>
                            </div>
                            <div class="size-209">
                                <input type="text" wire:model="item_name"
                                    class="bor8 bg0 stext-111 cl2 p-lr-20 p-tb-10 w-100"
                                    placeholder="Masukkan nama barang">
                                @error('item_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                            <div class="size-208 w-full-ssm m-t-9">
                                <label class="stext-110 cl2">Jenis barang:</label>
                            </div>
                            <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                <div class="m-b-12">
                                    <select wire:model="item_type"
                                        class="form-select bor8 bg0 stext-111 cl2 p-lr-20 p-tb-10 w-100">
                                        <option value="">Pilih jenis barang</option>
                                        <option value="phone">Ponsel</option>
                                        <option value="laptop">Laptop</option>
                                        <option value="tablet">Tablet</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>
                                @error('item_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex-w flex-t bor12 p-b-13 p-t-15">
                            <div class="size-208">
                                <label class="stext-110 cl2">Merek barang (opsional):</label>
                            </div>
                            <div class="size-209">
                                <input type="text" wire:model="item_brand"
                                    class="bor8 bg0 stext-111 cl2 p-lr-20 p-tb-10 w-100"
                                    placeholder="Contoh: Samsung, Apple, dll">
                            </div>
                        </div>

                        <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                            <div class="size-208 w-full-ssm m-t-9">
                                <label class="stext-110 cl2">Detail kerusakan:</label>
                            </div>
                            <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                <textarea wire:model="description" rows="4" class="bor8 bg0 stext-111 cl2 p-lr-20 p-tb-10 w-100"
                                    placeholder="Jelaskan kerusakan yang terjadi"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                            <div class="size-208 w-full-ssm m-t-9">
                                <label class="stext-110 cl2">Bukti gambar/video:</label>
                            </div>
                            <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                <input type="file" wire:model="files" multiple class="form-control">
                                <small class="text-muted">Unggah foto atau video kerusakan (maks 10MB per file)</small>
                                @error('files.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                @if ($files)
                                    <div class="mt-3">
                                        @foreach ($files as $index => $file)
                                            <div class="mb-2">
                                                <span>{{ $file->getClientOriginalName() }}</span>
                                                <button type="button" wire:click="removeFile({{ $index }})"
                                                    class="btn btn-sm btn-danger ml-2">Hapus</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-c-m p-t-20">
                            <button type="submit"
                                class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer"
                                style="max-width: 300px; width: 100%;"
                                wire:loading.attr="disabled"
                                wire:target="submit">
                                <span wire:loading.remove wire:target="submit">Kirim formulir</span>
                                <span wire:loading wire:target="submit">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            // Initialize select2
            $('.js-select2').select2({
                minimumResultsForSearch: 20,
                dropdownParent: $('.dropDownSelect2')
            });

            // Listen for select2 changes
            $('.js-select2').on('change', function(e) {
                @this.set('item_type', $(this).val());
            });
        });
    </script>
@endpush
