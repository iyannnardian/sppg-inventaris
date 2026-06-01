<!-- Modal Tambah Masuk -->
<div class="modal fade" id="modalTambahMasuk" tabindex="-1" role="dialog" aria-labelledby="modalTambahMasukLabel" aria-hidden="true">
    <div class="modal-dialog text-left" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="modalTambahMasukLabel"><i class="fas fa-arrow-down mr-2"></i>Catat Transaksi Barang Masuk</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('transaksi.storeMasuk') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_barang_masuk">Pilih Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_barang_masuk" name="id_barang" required>
                            <option value="" disabled selected>-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Tersedia: {{ $b->stok }} {{ $b->satuan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_supplier_masuk">Pilih Supplier <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_supplier_masuk" name="id_supplier" required>
                            <option value="" disabled selected>-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="jumlah_masuk">Jumlah Masuk <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_masuk" name="jumlah" placeholder="Masukkan jumlah qty" required min="1">
                            <small class="text-muted d-block mt-1" id="info-satuan-masuk"></small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="harga_masuk">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="harga_masuk" name="harga" placeholder="Contoh: 15000" required min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Keluar -->
<div class="modal fade" id="modalTambahKeluar" tabindex="-1" role="dialog" aria-labelledby="modalTambahKeluarLabel" aria-hidden="true">
    <div class="modal-dialog text-left" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="modalTambahKeluarLabel"><i class="fas fa-arrow-up mr-2 text-white"></i>Catat Transaksi Barang Keluar</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('transaksi.storeKeluar') }}" method="POST" id="formTambahKeluar">
                @csrf
                <div class="modal-body">
                    <!-- Alert Peringatan Stok Kritis/Kurang -->
                    <div class="alert alert-danger border-0 shadow-sm d-none" id="tambah-keluar-warning-alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="tambah-keluar-warning-msg">Jumlah pengeluaran melebihi stok yang tersedia saat ini!</span>
                    </div>

                    <div class="form-group">
                        <label for="id_barang_keluar">Pilih Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_barang_keluar" name="id_barang" required>
                            <option value="" disabled selected>-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Tersedia: {{ $b->stok }} {{ $b->satuan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_keluar">Jumlah Keluar <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="jumlah_keluar" name="jumlah" placeholder="Masukkan jumlah qty" required min="1">
                        <small class="text-muted d-block mt-1" id="info-satuan-keluar"></small>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_keluar">Tanggal Keluar <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" id="btnSubmitTambahKeluar"><i class="fas fa-save mr-1"></i> Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
