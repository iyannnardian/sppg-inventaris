<!-- Modal Edit Supplier -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Supplier</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditSupplier" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_supplier">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_supplier" name="nama_supplier" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="edit_alamat">Alamat Supplier</label>
                        <textarea class="form-control" id="edit_alamat" name="alamat" rows="3"></textarea>
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
