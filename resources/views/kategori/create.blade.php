<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-plus-circle mr-2"></i>Tambah Kategori</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_kategori">Kode Kategori</label>
                        <input type="text" class="form-control" id="kode_kategori" name="kode_kategori" placeholder="Opsional (misal: KH atau KH.01)" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Contoh: Bahan Pokok, Sayuran, Daging" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="parent_id">Induk Kategori</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="">-- Jadikan Kategori Utama --</option>
                            @foreach($kategoriUtama as $ku)
                                <option value="{{ $ku->id_kategori }}">{{ $ku->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kosongkan jika ini adalah Kategori Utama.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
