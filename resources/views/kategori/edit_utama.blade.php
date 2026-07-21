<!-- Modal Edit Kategori Utama -->
<div class="modal fade" id="modalEditKategoriUtama" tabindex="-1" role="dialog" aria-labelledby="modalEditKategoriUtamaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="modalEditKategoriUtamaLabel" style="font-size: 20px;">Edit Kategori Utama</h5>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditKategoriUtama" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="edit_kode_kategori" class="small font-weight-bold text-secondary">Kode Kategori</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" id="edit_kode_kategori" name="kode_kategori" placeholder="Contoh: KH" autocomplete="off" style="border-radius: 8px; font-size: 14px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="edit_nama_kategori" class="small font-weight-bold text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" id="edit_nama_kategori" name="nama_kategori" placeholder="Contoh: KARBOHIDRAT" required autocomplete="off" style="border-radius: 8px; font-size: 14px;">
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
