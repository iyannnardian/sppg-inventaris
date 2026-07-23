<!-- MODAL CATAT PEMBELIAN BARU -->
<div class="modal fade" id="modalTambahPembelian" tabindex="-1" role="dialog" aria-labelledby="modalTambahPembelianLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title font-weight-bold text-dark" id="modalTambahPembelianLabel">Catat Pembelian Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('barang-masuk.store') }}" method="POST" id="formTambahPembelian">
                @csrf
                <!-- Status default saat user menginput pertama kali adalah Draft -->
                <input type="hidden" name="status" value="Draft">

                <div class="modal-body p-4">
                    <!-- Header Form: No Faktur & Supplier -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="no_faktur" class="font-weight-bold text-secondary" style="font-size: 13px;">No. Faktur (unik)</label>
                            <input type="text" class="form-control" id="no_faktur" name="no_faktur" value="{{ old('no_faktur') }}" placeholder="Masukkan No. Faktur" required style="border-radius: 8px;">

                        </div>
                        <div class="form-group col-md-6">
                            <label for="id_supplier_create" class="font-weight-bold text-secondary" style="font-size: 13px;">Supplier</label>
                            <select class="form-control" id="id_supplier_create" name="id_supplier" required style="border-radius: 8px;">
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
                            <label for="tgl_faktur" class="font-weight-bold text-secondary" style="font-size: 13px;">Tanggal Faktur</label>
                            <input type="date" class="form-control" id="tgl_faktur" name="tgl_faktur" value="{{ date('Y-m-d') }}" required style="border-radius: 8px;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tgl_terima" class="font-weight-bold text-secondary" style="font-size: 13px;">Tanggal Terima (rencana)</label>
                            <input type="date" class="form-control" id="tgl_terima" name="tgl_terima" value="{{ date('Y-m-d') }}" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- Section Multi-Item Barang (Pembelian_Detail) -->
                    <label class="font-weight-bold text-secondary mt-2 mb-2" style="font-size: 13px;">Rincian Barang (Pembelian_Detail)</label>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm align-middle mb-2">
                            <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                                <tr>
                                    <th style="width: 34%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;">BARANG</th>
                                    <th style="width: 12%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;" class="text-center">SATUAN</th>
                                    <th style="width: 16%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;">QTY</th>
                                    <th style="width: 18%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;">HARGA SATUAN</th>
                                    <th style="width: 16%; font-size: 11px; text-transform: uppercase; color: #8c8c8c; padding: 6px 5px;" class="text-right">SUBTOTAL</th>
                                    <th style="width: 4%; padding: 6px 5px;"></th>
                                </tr>
                            </thead>
                            <tbody id="containerBarisItem">
                                <!-- Baris Pertama Default -->
                                <tr class="baris-item">
                                    <td style="padding: 3px 4px;">
                                        <select class="form-control select-barang" name="items[0][id_barang]" required style="border-radius: 8px;">
                                            <option value="" disabled selected>— Pilih barang —</option>
                                            @foreach($barangs as $b)
                                                <option value="{{ $b->id_barang }}" data-satuan="{{ $b->satuan->nama_satuan ?? '' }}">
                                                    {{ $b->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center align-middle" style="padding: 3px 4px;">
                                        <span class="badge badge-light border info-satuan-item py-2 px-2 d-block text-center font-weight-normal text-secondary" style="border-radius: 8px; font-size: 13px;">-</span>
                                    </td>
                                    <td style="padding: 3px 4px;">
                                        <input type="text" class="form-control input-qty" name="items[0][qty]" placeholder="1.000" required style="border-radius: 8px;" inputmode="numeric" autocomplete="off">
                                    </td>
                                    <td style="padding: 3px 4px;">
                                        <input type="text" class="form-control input-harga" name="items[0][harga]" placeholder="12.000" required style="border-radius: 8px;" inputmode="numeric" autocomplete="off">
                                    </td>
                                    <td class="text-right align-middle" style="padding: 3px 4px;">
                                        <span class="font-weight-bold text-dark input-subtotal-text">Rp 0</span>
                                    </td>
                                    <td class="text-center align-middle" style="padding: 3px 4px;">
                                        <button type="button" class="btn btn-link text-muted p-0 btn-hapus-baris" title="Hapus Baris" style="font-size: 16px;">&times;</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Total Belanja -->
                    <div class="d-flex justify-content-end align-items-center py-2 mb-3 pr-3" style="background-color: #fafafa; border-radius: 8px;">
                        <span class="font-weight-bold text-secondary mr-4" style="font-size: 14px;">Total Belanja</span>
                        <span class="font-weight-bold text-dark" style="font-size: 18px;" id="grandTotalBelanja">Rp 0</span>
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
                    <button type="submit" class="btn btn-primary font-weight-bold px-4" style="border-radius: 8px;">
                        Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
