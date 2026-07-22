<!-- MODAL EDIT PEMBELIAN DRAFT -->
<div class="modal fade" id="modalEditPembelian" tabindex="-1" role="dialog" aria-labelledby="modalEditPembelianLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title font-weight-bold text-dark" id="modalEditPembelianLabel">Edit Faktur Pembelian (Draft)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditPembelian" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="edit_status" value="Draft">

                <div class="modal-body p-4">
                    <!-- Header Form: No Faktur & Supplier -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_no_faktur" class="font-weight-bold text-secondary" style="font-size: 13px;">No. Faktur (unik)</label>
                            <input type="text" class="form-control" id="edit_no_faktur" name="no_faktur" placeholder="Masukkan No. Faktur" required style="border-radius: 8px;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_id_supplier" class="font-weight-bold text-secondary" style="font-size: 13px;">Supplier</label>
                            <select class="form-control" id="edit_id_supplier" name="id_supplier" required style="border-radius: 8px;">
                                <option value="" disabled selected>— Pilih supplier —</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Header Form: Tanggal Faktur & Tanggal Terima -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_tgl_faktur" class="font-weight-bold text-secondary" style="font-size: 13px;">Tanggal Faktur</label>
                            <input type="date" class="form-control" id="edit_tgl_faktur" name="tgl_faktur" required style="border-radius: 8px;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_tgl_terima" class="font-weight-bold text-secondary" style="font-size: 13px;">Tanggal Terima (rencana)</label>
                            <input type="date" class="form-control" id="edit_tgl_terima" name="tgl_terima" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- Section Multi-Item Barang -->
                    <label class="font-weight-bold text-secondary mt-2 mb-2" style="font-size: 13px;">Rincian Barang (Pembelian_Detail)</label>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-2">
                            <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                                <tr>
                                    <th style="width: 42%; font-size: 11px; text-transform: uppercase; color: #8c8c8c;">BARANG</th>
                                    <th style="width: 18%; font-size: 11px; text-transform: uppercase; color: #8c8c8c;">QTY</th>
                                    <th style="width: 22%; font-size: 11px; text-transform: uppercase; color: #8c8c8c;">HARGA SATUAN</th>
                                    <th style="width: 18%; font-size: 11px; text-transform: uppercase; color: #8c8c8c;" class="text-right">SUBTOTAL</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody id="editContainerBarisItem">
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Total Belanja -->
                    <div class="d-flex justify-content-end align-items-center py-2 mb-3 pr-3" style="background-color: #fafafa; border-radius: 8px;">
                        <span class="font-weight-bold text-secondary mr-4" style="font-size: 14px;">Total Belanja</span>
                        <span class="font-weight-bold text-dark" style="font-size: 18px;" id="editGrandTotalBelanja">Rp 0</span>
                    </div>

                    <!-- Tombol Tambah Baris -->
                    <div>
                        <button type="button" class="btn btn-light border text-dark font-weight-bold btn-sm px-3" id="editBtnTambahBarisItem" style="border-radius: 8px; font-size: 13px;">
                            + Tambah baris barang
                        </button>
                    </div>
                </div>

                <!-- Footer Form Modal -->
                <div class="modal-footer border-0 pt-0 pr-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary font-weight-bold px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4" style="border-radius: 8px;">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
