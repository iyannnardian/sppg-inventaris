<!-- Modal Edit Barang -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="modalEditLabel" style="font-size: 20px;">Edit Barang</h5>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditBarang" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_kode_barang" class="small font-weight-bold text-secondary">Kode Barang (unik)</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" id="edit_kode_barang" name="kode_barang" placeholder="BR-0005" autocomplete="off" style="border-radius: 8px; font-size: 14px;">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_nama_barang" class="small font-weight-bold text-secondary">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" id="edit_nama_barang" name="nama_barang" placeholder="Minyak Goreng" required autocomplete="off" style="border-radius: 8px; font-size: 14px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_id_subkategori" class="small font-weight-bold text-secondary">Sub-Kategori (Tabel: SUB_KATEGORI) <span class="text-danger">*</span></label>
                            <select class="form-control form-control-lg bg-light border-0" id="edit_id_subkategori" name="id_subkategori" required style="border-radius: 8px; font-size: 14px;">
                                <option value="" disabled selected>— Pilih Sub-Kategori —</option>
                                @foreach($subKategoris as $sub)
                                    <option value="{{ $sub->id_subkategori }}">
                                        {{ $sub->kategori ? $sub->kategori->nama_kategori . ' / ' : '' }}{{ $sub->nama_subkategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_id_satuan" class="small font-weight-bold text-secondary">Satuan <span class="text-danger">*</span></label>
                            <select class="form-control form-control-lg bg-light border-0" id="edit_id_satuan" name="id_satuan" required style="border-radius: 8px; font-size: 14px;">
                                <option value="" disabled selected>— Pilih satuan —</option>
                                @foreach($satuans as $s)
                                    <option value="{{ $s->id_satuan }}">{{ $s->nama_satuan }}{{ $s->keterangan ? ' / ' . $s->keterangan : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_stok_minimum" class="small font-weight-bold text-secondary">Stok Minimum</label>
                            <input type="number" class="form-control form-control-lg bg-light border-0" id="edit_stok_minimum" name="stok_minimum" value="0" min="0" step="any" style="border-radius: 8px; font-size: 14px;">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_harga_terakhir" class="small font-weight-bold text-secondary">Harga Terakhir (Rp)</label>
                            <input type="number" class="form-control form-control-lg bg-light border-0" id="edit_harga_terakhir" name="harga_terakhir" value="0" min="0" step="any" style="border-radius: 8px; font-size: 14px;">
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
