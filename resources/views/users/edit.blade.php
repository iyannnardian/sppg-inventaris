<!-- Modal Edit Pengguna -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Data Pengguna</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditUser" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label for="edit_name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="edit_email">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="edit_role">Pilih Peran / Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_role" name="role" required>
                            <option value="admin">Admin (Akses Penuh)</option>
                            <option value="ahli gizi">Ahli Gizi (Akses Dapur & Transaksi)</option>
                            <option value="kepala dapur">Kepala Dapur (Akses Monitoring & Laporan)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_password">Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah kata sandi">
                        <small class="text-muted mt-1 d-block">Minimal terdiri dari 8 karakter jika ingin diubah.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save mr-1"></i> Perbarui Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
