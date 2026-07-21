<!-- MODAL DETAIL PEMBELIAN -->
<div class="modal fade" id="modalDetailPembelian" tabindex="-1" role="dialog" aria-labelledby="modalDetailPembelianLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark" id="modalDetailPembelianLabel">Detail Faktur Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th style="width: 35%" class="text-secondary">No. Faktur</th>
                                <td>: <strong class="text-dark" id="detail_no_faktur"></strong></td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Supplier</th>
                                <td>: <span id="detail_supplier"></span></td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Status</th>
                                <td>: <span id="detail_status"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th style="width: 35%" class="text-secondary">Tgl Faktur</th>
                                <td>: <span id="detail_tgl_faktur"></span></td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Tgl Terima</th>
                                <td>: <span id="detail_tgl_terima"></span></td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Total Belanja</th>
                                <td>: <strong class="text-dark" style="font-size: 16px;" id="detail_total_belanja"></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6 class="font-weight-bold text-secondary mb-2" style="font-size: 13px;">Rincian Barang (Pembelian_Detail):</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                            <tr>
                                <th style="width: 5%; font-size: 11px; color: #8c8c8c;">NO</th>
                                <th style="width: 40%; font-size: 11px; color: #8c8c8c;">BARANG</th>
                                <th style="width: 15%; font-size: 11px; color: #8c8c8c;" class="text-center">QTY</th>
                                <th style="width: 20%; font-size: 11px; color: #8c8c8c;" class="text-right">HARGA SATUAN</th>
                                <th style="width: 20%; font-size: 11px; color: #8c8c8c;" class="text-right">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="detail_container_items">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pr-4 pb-4">
                <button type="button" class="btn btn-secondary font-weight-bold px-4" data-dismiss="modal" style="border-radius: 8px;">Tutup</button>
            </div>
        </div>
    </div>
</div>
