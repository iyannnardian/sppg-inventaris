<!-- MODAL EDIT PENGELUARAN -->
<div class="modal fade" id="modalEditPengeluaran" tabindex="-1" role="dialog" aria-labelledby="modalEditPengeluaranLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title font-weight-bold text-dark" id="modalEditPengeluaranLabel">Edit Transaksi Pengeluaran Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditPengeluaran" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <!-- Header Form: Tanggal Pengeluaran -->
                    <div class="form-group mb-3">
                        <label for="edit_tgl_pengeluaran" class="font-weight-bold text-secondary" style="font-size: 13px;">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="edit_tgl_pengeluaran" name="tgl_pengeluaran" required style="border-radius: 8px;">
                    </div>

                    <!-- Section Multi-Item Barang (Pengeluaran_Detail) -->
                    <label class="font-weight-bold text-secondary mt-2 mb-2" style="font-size: 13px;">Rincian Barang Keluar (Pengeluaran_Detail)</label>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm align-middle mb-2">
                            <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                                <tr>
                                    <th style="width: 42%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;">BARANG</th>
                                    <th style="width: 20%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;" class="text-center">STOK TERSEDIA</th>
                                    <th style="width: 33%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;">QTY (JUMLAH KELUAR)</th>
                                    <th style="width: 5%; padding: 6px 5px;"></th>
                                </tr>
                            </thead>
                            <tbody id="editContainerBarisItem">
                            </tbody>
                        </table>
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
                    <button type="submit" class="btn btn-primary font-weight-bold px-4" id="editBtnSubmitPengeluaran" style="border-radius: 8px;">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
