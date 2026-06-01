<!-- Modal Lihat Barang di Kategori -->
<div class="modal fade" id="modalViewBarang" tabindex="-1" role="dialog" aria-labelledby="modalViewBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalViewBarangLabel"><i class="fas fa-box mr-2"></i>Daftar Barang Kategori: <span id="view_nama_kategori" class="font-weight-bold"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="table-kategori-barang">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 10%">No</th>
                                <th style="width: 60%">Nama Barang</th>
                                <th style="width: 30%">Stok Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody id="list-barang-kategori">
                            <!-- Isi dinamis lewat JS -->
                        </tbody>
                    </table>
                </div>
                <div id="empty-barang-state" class="text-center py-4 d-none text-muted">
                    <i class="fas fa-box-open fa-2x mb-2 text-secondary"></i>
                    <p class="mb-0">Belum ada barang yang didaftarkan pada kategori ini.</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
