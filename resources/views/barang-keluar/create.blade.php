<!-- MODAL CATAT PENGELUARAN BARU -->
<div class="modal fade" id="modalTambahPengeluaran" tabindex="-1" role="dialog" aria-labelledby="modalTambahPengeluaranLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title font-weight-bold text-dark" id="modalTambahPengeluaranLabel">Catat Pengeluaran Barang Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('barang-keluar.store') }}" method="POST" id="formTambahPengeluaran">
                @csrf
                <div class="modal-body p-4">
                    <!-- Header Form: Tanggal Pengeluaran -->
                    <div class="form-group mb-3">
                        <label for="tgl_pengeluaran" class="font-weight-bold text-secondary" style="font-size: 13px;">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_pengeluaran" name="tgl_pengeluaran" value="{{ date('Y-m-d') }}" required style="border-radius: 8px;">
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
                            <tbody id="containerBarisItem">
                                <!-- Baris Pertama Default -->
                                <tr class="baris-item">
                                    <td style="padding: 3px 4px;">
                                        <select class="form-control select-barang" name="items[0][id_barang]" required style="border-radius: 8px;">
                                            <option value="" disabled selected>— Pilih barang —</option>
                                            @foreach($barangs as $b)
                                                <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan->nama_satuan ?? '' }}">
                                                    {{ $b->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center align-middle" style="padding: 3px 4px;">
                                        <span class="badge badge-light border info-stok-item py-2 px-2 d-block text-center font-weight-normal text-secondary" style="border-radius: 8px; font-size: 13px;">-</span>
                                    </td>
                                    <td style="padding: 3px 4px;">
                                        <input type="text" class="form-control input-qty" name="items[0][qty]" placeholder="1.000" required style="border-radius: 8px;" inputmode="numeric" autocomplete="off">
                                        <small class="text-danger warning-stok-exceeded d-none font-weight-bold mt-1">Stok tidak mencukupi!</small>
                                    </td>
                                    <td class="text-center align-middle" style="padding: 3px 4px;">
                                        <button type="button" class="btn btn-link text-muted p-0 btn-hapus-baris" title="Hapus Baris" style="font-size: 16px;">&times;</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Tambah Baris -->
                    <div>
                        <button type="button" class="btn btn-light border text-dark font-weight-bold btn-sm px-3" id="btnTambahBarisItem" style="border-radius: 8px; font-size: 13px;">
                            + Tambah baris barang
                        </button>
                    </div>
                </div>

                <!-- Footer Form Modal -->
                <div class="modal-footer border-0 pt-0 pr-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary font-weight-bold px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4" id="btnSubmitPengeluaran" style="border-radius: 8px;">
                        Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
