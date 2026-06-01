<!-- Modal Edit Kategori -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Kategori</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditKategori" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_kategori">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
