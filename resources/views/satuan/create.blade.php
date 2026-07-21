<!-- Modal Tambah Satuan -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-plus-circle mr-2"></i>Tambah Satuan Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('satuan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_satuan">Nama Satuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" placeholder="Contoh: kg, gram, liter, pcs, botol, dus" required autocomplete="off">
                        <small class="form-text text-muted">Misal: kg, pcs, liter, ikat, bungkus, dll.</small>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Opsional (misal: Kilogram, Pieces, Botol 500ml)" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
