<!-- Modal Edit Masuk -->
<div class="modal fade" id="modalEditMasuk" tabindex="-1" role="dialog" aria-labelledby="modalEditMasukLabel" aria-hidden="true">
    <div class="modal-dialog text-left" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title" id="modalEditMasukLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Transaksi Barang Masuk</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditMasuk" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Alert Peringatan edit masuk -->
                    <div class="alert alert-danger border-0 shadow-sm d-none" id="edit-masuk-warning-alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="edit-masuk-warning-msg">Pengurangan barang masuk ini akan membuat stok menjadi negatif!</span>
                    </div>

                    <div class="form-group">
                        <label for="edit_id_barang_masuk">Pilih Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_id_barang_masuk" name="id_barang" required>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Stok Saat Ini: {{ $b->stok }} {{ $b->satuan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_supplier_masuk">Pilih Supplier <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_id_supplier_masuk" name="id_supplier" required>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_jumlah_masuk">Jumlah Masuk <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_jumlah_masuk" name="jumlah" required min="1">
                            <small class="text-muted d-block mt-1" id="edit-info-satuan-masuk"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_harga_masuk">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_harga_masuk" name="harga" required min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="edit_tanggal_masuk" name="tanggal_masuk" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white" id="btnSubmitEditMasuk"><i class="fas fa-save mr-1"></i> Perbarui Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Keluar -->
<div class="modal fade" id="modalEditKeluar" tabindex="-1" role="dialog" aria-labelledby="modalEditKeluarLabel" aria-hidden="true">
    <div class="modal-dialog text-left" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title" id="modalEditKeluarLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Transaksi Barang Keluar</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditKeluar" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Alert Peringatan Stok Kritis/Kurang -->
                    <div class="alert alert-danger border-0 shadow-sm d-none" id="edit-keluar-warning-alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="edit-keluar-warning-msg">Jumlah pengeluaran melebihi stok yang tersedia saat ini!</span>
                    </div>

                    <div class="form-group">
                        <label for="edit_id_barang_keluar">Pilih Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_id_barang_keluar" name="id_barang" required>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Stok Saat Ini: {{ $b->stok }} {{ $b->satuan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jumlah_keluar">Jumlah Keluar <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_jumlah_keluar" name="jumlah" required min="1">
                        <small class="text-muted d-block mt-1" id="edit-info-satuan-keluar"></small>
                    </div>
                    <div class="form-group">
                        <label for="edit_tanggal_keluar">Tanggal Keluar <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="edit_tanggal_keluar" name="tanggal_keluar" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white" id="btnSubmitEditKeluar"><i class="fas fa-save mr-1"></i> Perbarui Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
