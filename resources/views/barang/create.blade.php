<!-- Modal Tambah Barang -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-plus-circle mr-2"></i>Tambah Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Contoh: Beras Pandan Wangi, Wortel Lokal" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="id_kategori_tambah">Kategori Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_kategori_tambah" name="id_kategori" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="satuan">Satuan Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="satuan" name="satuan" required>
                            <option value="" disabled selected>-- Pilih Satuan --</option>
                            <option value="kg">kg (Kilogram)</option>
                            <option value="gram">gram</option>
                            <option value="liter">liter</option>
                            <option value="pcs">pcs (Pieces)</option>
                            <option value="ikat">ikat</option>
                            <option value="bungkus">bungkus</option>
                            <option value="botol">botol</option>
                            <option value="dus">dus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stok_awal">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stok_awal" name="stok_awal" placeholder="Masukkan stok awal saat ini (misal: 0)" required min="0" value="0">
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
