<!-- Modal Tambah Supplier -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-plus-circle mr-2"></i>Tambah Supplier</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_supplier">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" placeholder="Contoh: PT. Sumber Protein, Toko Sayur Sehat" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Contoh: 081234567890" maxlength="13" minlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" autocomplete="off">
                        <small class="form-text text-muted">Format: 11 - 13 digit angka saja.</small>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat Supplier</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap supplier (opsional)"></textarea>
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
