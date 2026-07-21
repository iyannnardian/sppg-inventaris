<!-- Modal Tambah Sub-Kategori -->
<div class="modal fade" id="modalTambahSubKategori" tabindex="-1" role="dialog" aria-labelledby="modalTambahSubKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="modalTambahSubKategoriLabel" style="font-size: 20px;">Tambah Sub-Kategori</h5>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('sub-kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="form-group mb-3">
                        <label for="id_kategori" class="small font-weight-bold text-secondary">Kategori Induk <span class="text-danger">*</span></label>
                        <select class="form-control form-control-lg bg-light border-0" id="id_kategori" name="id_kategori" required style="border-radius: 8px; font-size: 14px;">
                            <option value="" disabled selected>— Pilih Kategori Utama —</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="kode_subkategori" class="small font-weight-bold text-secondary">Kode Sub-Kategori</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" id="kode_subkategori" name="kode_subkategori" placeholder="Contoh: KH.01" autocomplete="off" style="border-radius: 8px; font-size: 14px;">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="nama_subkategori" class="small font-weight-bold text-secondary">Nama Sub-Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" id="nama_subkategori" name="nama_subkategori" placeholder="Contoh: OLAHAN PADI" required autocomplete="off" style="border-radius: 8px; font-size: 14px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 8px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
