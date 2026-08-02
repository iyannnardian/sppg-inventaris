<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-user-plus mr-2"></i>Daftarkan Pengguna Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Rian Nardian" required oninvalid="this.setCustomValidity('Nama lengkap wajib diisi.')" oninput="this.setCustomValidity('')" autocomplete="off" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="username">Username Login <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Contoh: rian_admin" required oninvalid="this.setCustomValidity('Username login wajib diisi.')" oninput="this.setCustomValidity('')" autocomplete="off" value="{{ old('username') }}">
                        <small class="form-text text-muted">Digunakan untuk masuk / login ke sistem.</small>
                    </div>

                    <div class="form-group">
                        <label for="role">Pilih Peran / Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="role" name="role" required oninvalid="this.setCustomValidity('Silakan pilih peran akses.')" oninput="this.setCustomValidity('')">
                            <option value="" disabled selected>-- Pilih Peran Akses --</option>
                            <option value="admin">Admin (Akses Penuh)</option>
                            <option value="ahli gizi">Ahli Gizi (Akses Dapur & Transaksi)</option>
                            <option value="kepala dapur">Kepala Dapur (Akses Monitoring & Laporan)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi Akun <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal terdiri dari 8 karakter" required oninvalid="this.setCustomValidity('Kata sandi akun wajib diisi.')" oninput="this.setCustomValidity('')">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
