<!-- Modal Edit Barang -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditBarang" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_barang">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_barang" name="nama_barang" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="edit_id_kategori">Kategori Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_id_kategori" name="id_kategori" required>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_satuan">Satuan Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_satuan" name="satuan" required>
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
                        <label for="edit_stok_awal">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_stok_awal" name="stok_awal" required min="0">
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
