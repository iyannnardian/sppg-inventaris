@extends('adminlte::page')

@section('title', 'Pembelian & Barang Masuk - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 26px;">Pembelian</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Transaksi pembelian dari supplier — status Draft / Diterima / Batal</p>
        </div>
        @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
            <div class="mt-md-0 mt-3">
                <button class="btn btn-primary font-weight-bold px-3 py-2 shadow-sm" data-toggle="modal" data-target="#modalTambahPembelian" style="border-radius: 6px;">
                    + Catat Pembelian
                </button>
            </div>
        @endif
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert" style="border-radius: 6px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert" style="border-radius: 6px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert" style="border-radius: 6px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Gagal memproses data:</strong>
            <ul class="mb-0 mt-1 pl-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Form Filter Tgl Faktur -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            <form action="{{ route('barang-masuk.index') }}" method="GET" class="form-inline flex-wrap align-items-center">
                <span class="font-weight-bold text-secondary mr-2" style="font-size: 14px;">Filter Tgl Faktur:</span>
                
                <input type="date" class="form-control bg-white mr-2 my-1" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggalAwal }}" placeholder="dd/mm/yyyy" style="border-radius: 6px; font-size: 14px;">
                
                <span class="text-secondary mr-2 my-1">s.d.</span>
                
                <input type="date" class="form-control bg-white mr-2 my-1" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}" placeholder="dd/mm/yyyy" style="border-radius: 6px; font-size: 14px;">
                
                <button type="submit" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 mr-2 my-1" style="border-radius: 6px;">Terapkan</button>
                <a href="{{ route('barang-masuk.index') }}" class="btn btn-light border btn-sm text-secondary px-3 my-1" style="border-radius: 6px;">Reset</a>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Transaksi Pembelian -->
    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <div class="card-body p-0">
            @if($transaksis->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p class="mb-0">Belum ada riwayat transaksi pembelian.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                            <tr>
                                <th style="width: 15%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="pl-4 py-3">NO. FAKTUR</th>
                                <th style="width: 12%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">TGL FAKTUR</th>
                                <th style="width: 12%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">TGL TERIMA</th>
                                <th style="width: 20%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">SUPPLIER</th>
                                <th style="width: 15%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">TOTAL</th>
                                <th style="width: 12%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3 text-center">STATUS</th>
                                <th style="width: 14%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="text-right pr-4 py-3">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $t)
                            <tr>
                                <td class="pl-4 font-weight-bold text-dark">{{ $t->no_faktur }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($t->tgl_faktur)->format('d M Y') }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($t->tgl_terima)->format('d M Y') }}
                                </td>
                                <td>
                                    {{ $t->supplier->nama_supplier ?? '-' }}
                                </td>
                                <td class="font-weight-bold">
                                    Rp {{ number_format($t->total_belanja, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($t->status === 'Diterima')
                                        <span class="badge badge-success px-2 py-1 font-weight-bold">Diterima</span>
                                    @elseif($t->status === 'Draft')
                                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="background-color: #fce8cd; color: #a86200;">Draft</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1 font-weight-bold">Batal</span>
                                    @endif
                                </td>
                                <td class="text-right pr-4">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <!-- Tombol Detail -->
                                        <button type="button" class="btn btn-info btn-sm font-weight-bold btn-detail-pembelian mr-1" data-id="{{ $t->id_pembelian }}" title="Lihat Detail">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>

                                        @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
                                            <!-- Tombol Edit (hanya saat status Draft) -->
                                            @if($t->status === 'Draft')
                                                <button type="button" class="btn btn-primary btn-sm font-weight-bold btn-edit-pembelian mr-1" data-id="{{ $t->id_pembelian }}" title="Edit Faktur Draft">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </button>
                                            @endif

                                            <!-- Tombol Verifikasi & Terima (hanya saat status Draft) -->
                                            @if($t->status === 'Draft')
                                                <button type="button" class="btn btn-success btn-sm font-weight-bold btn-verifikasi-terima mr-1" data-id="{{ $t->id_pembelian }}" title="Verifikasi & Terima Barang">
                                                    <i class="fas fa-check-circle mr-1"></i> Terima
                                                </button>
                                            @endif

                                            <!-- Tombol Batalkan (hanya saat status Draft) -->
                                            @if($t->status === 'Draft')
                                                <form action="{{ route('barang-masuk.update-status', $t->id_pembelian) }}" method="POST" class="d-inline mr-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Batal">
                                                    <button type="submit" class="btn btn-warning btn-sm text-white font-weight-bold" onclick="return confirm('Batalkan transaksi faktur ini?');" title="Batalkan Faktur">
                                                        <i class="fas fa-times mr-1"></i> Batal
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('barang-masuk.destroy', $t->id_pembelian) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi faktur ini?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm font-weight-bold" title="Hapus Faktur">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>




                                                
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
        @include('barang-masuk.create')

        <!-- MODAL VERIFIKASI & PENERIMAAN BARANG -->
        <div class="modal fade" id="modalVerifikasiTerima" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiTerimaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h4 class="modal-title font-weight-bold text-dark" id="modalVerifikasiTerimaLabel">Verifikasi & Penerimaan Barang</h4>
                            <p class="text-muted mb-0" style="font-size: 13px;">Verifikasi jumlah barang fisik yang diterima dengan pesanan faktur <strong id="verifikasi_no_faktur" class="text-dark"></strong></p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formVerifikasiTerima" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Diterima">

                        <div class="modal-body p-4">
                            <div class="card border-0 bg-light mb-3" style="border-radius: 8px;">
                                <div class="card-body py-2 px-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Supplier:</small>
                                            <strong class="text-dark" id="verifikasi_supplier"></strong>
                                        </div>
                                        <div class="col-md-6 text-md-right mt-2 mt-md-0">
                                            <small class="text-muted d-block">Tanggal Terima (Rencana):</small>
                                            <strong class="text-dark" id="verifikasi_tgl_terima"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="font-weight-bold text-secondary mb-2" style="font-size: 13px;">
                                Rincian Barang (Qty Pesan vs Qty Diterima):
                            </label>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-2">
                                    <thead style="background-color: #f8f9fa;">
                                        <tr>
                                            <th style="width: 40%; font-size: 11px; text-transform: uppercase; color: #6c757d;">BARANG</th>
                                            <th style="width: 30%; font-size: 11px; text-transform: uppercase; color: #6c757d;" class="text-center">QTY PESAN</th>
                                            <th style="width: 30%; font-size: 11px; text-transform: uppercase; color: #6c757d;" class="text-center">QTY DITERIMA</th>
                                        </tr>
                                    </thead>
                                    <tbody id="verifikasi_container_items">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-0 pr-4 pb-4">
                            <button type="button" class="btn btn-outline-secondary font-weight-bold px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                            <button type="submit" class="btn btn-success font-weight-bold px-4" style="border-radius: 8px;">
                                <i class="fas fa-check-circle mr-1"></i> Konfirmasi Terima & Update Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('barang-masuk.edit')
    @endif

@include('barang-masuk.show')
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown HTML template untuk barang
            const barangOptionsHtml = `
                <option value="" disabled selected>— Pilih barang —</option>
                @foreach($barangs as $b)
                    <option value="{{ $b->id_barang }}" data-satuan="{{ $b->satuan->nama_satuan ?? '' }}">
                        {{ $b->nama_barang }}
                    </option>
                @endforeach
            `;

            let itemIndex = 1;

            const btnTambahBaris = document.getElementById('btnTambahBarisItem');
            const containerBaris = document.getElementById('containerBarisItem');

            if (btnTambahBaris && containerBaris) {
                btnTambahBaris.addEventListener('click', function () {
                    const tr = document.createElement('tr');
                    tr.className = 'baris-item';
                    tr.innerHTML = `
                        <td>
                            <select class="form-control select-barang" name="items[${itemIndex}][id_barang]" required style="border-radius: 8px;">
                                ${barangOptionsHtml}
                            </select>
                            <small class="text-muted info-satuan-item d-block mt-1"></small>
                        </td>
                        <td>
                            <input type="number" step="any" min="1" class="form-control input-qty" name="items[${itemIndex}][qty]" placeholder="0" required style="border-radius: 8px;" inputmode="decimal">
                        </td>
                        <td>
                            <input type="number" step="1" min="0" class="form-control input-harga" name="items[${itemIndex}][harga]" placeholder="0" required style="border-radius: 8px;" inputmode="numeric">
                        </td>
                        <td class="text-right align-middle">
                            <span class="font-weight-bold text-dark input-subtotal-text">Rp 0</span>
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-link text-muted p-0 btn-hapus-baris" title="Hapus Baris" style="font-size: 16px;">&times;</button>
                        </td>
                    `;
                    containerBaris.appendChild(tr);
                    itemIndex++;
                    bindRowEvents(tr);
                });

                // Bind baris awal
                const initialRow = containerBaris.querySelector('.baris-item');
                if (initialRow) {
                    bindRowEvents(initialRow);
                }
            }

            function bindRowEvents(row) {
                const selectBarang = row.querySelector('.select-barang');
                const inputQty = row.querySelector('.input-qty');
                const inputHarga = row.querySelector('.input-harga');
                const inputSubtotalText = row.querySelector('.input-subtotal-text');
                const btnHapus = row.querySelector('.btn-hapus-baris');
                const infoSatuan = row.querySelector('.info-satuan-item');

                // DILARANG MASUKKAN HURUF / Mencegah pengetikan selain angka & desimal
                [inputQty, inputHarga].forEach(input => {
                    input.addEventListener('keydown', function(e) {
                        if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                            (e.keyCode === 65 && e.ctrlKey === true) ||
                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                            return;
                        }
                        if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                            e.preventDefault();
                        }
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });

                    input.addEventListener('input', function() {
                        if (input.classList.contains('input-harga')) {
                            this.value = this.value.replace(/[^0-9]/g, '');
                        } else {
                            this.value = this.value.replace(/[^0-9.]/g, '');
                        }
                        hitungSubtotal();
                    });
                });

                selectBarang.addEventListener('change', function () {
                    const opt = this.options[this.selectedIndex];
                    if (opt && opt.value !== '') {
                        const satuan = opt.getAttribute('data-satuan') || '';
                        infoSatuan.textContent = satuan ? `Satuan: ${satuan}` : '';
                    }
                    hitungSubtotal();
                });

                btnHapus.addEventListener('click', function () {
                    const totalRows = containerBaris.querySelectorAll('.baris-item').length;
                    if (totalRows > 1) {
                        row.remove();
                        hitungGrandTotal();
                    } else {
                        alert('Minimal 1 barang harus ada dalam transaksi pembelian!');
                    }
                });

                function hitungSubtotal() {
                    const qty = parseFloat(inputQty.value) || 0;
                    const harga = parseFloat(inputHarga.value) || 0;
                    const subtotal = qty * harga;
                    if (inputSubtotalText) {
                        inputSubtotalText.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                    }
                    hitungGrandTotal();
                }
            }

            function hitungGrandTotal() {
                let grandTotal = 0;
                const rows = containerBaris.querySelectorAll('.baris-item');
                rows.forEach(row => {
                    const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
                    const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
                    grandTotal += (qty * harga);
                });
                const grandTotalElem = document.getElementById('grandTotalBelanja');
                if (grandTotalElem) {
                    grandTotalElem.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
                }
            }

            // Handler Modal Verifikasi & Terima Barang
            const btnVerifikasiList = document.querySelectorAll('.btn-verifikasi-terima');
            btnVerifikasiList.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    fetch(`/barang-masuk/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('formVerifikasiTerima').action = `/barang-masuk/${id}/status`;
                            document.getElementById('verifikasi_no_faktur').textContent = data.no_faktur;
                            document.getElementById('verifikasi_supplier').textContent = data.supplier ? data.supplier.nama_supplier : '-';
                            document.getElementById('verifikasi_tgl_terima').textContent = data.tgl_terima;

                            const containerItems = document.getElementById('verifikasi_container_items');
                            containerItems.innerHTML = '';

                            if (data.details && data.details.length > 0) {
                                data.details.forEach(item => {
                                    const namaBarang = item.barang ? item.barang.nama_barang : 'Barang Terhapus';
                                    const satuan = (item.barang && item.barang.satuan) ? item.barang.satuan.nama_satuan : '';
                                    const qtyPesan = parseFloat(item.qty) || 0;
                                    const qtyTerima = item.qty_terima !== null ? parseFloat(item.qty_terima) : qtyPesan;

                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td>
                                            <strong class="text-dark d-block">${namaBarang}</strong>
                                            ${satuan ? `<small class="text-muted">Satuan: ${satuan}</small>` : ''}
                                        </td>
                                        <td class="text-center align-middle font-weight-bold">
                                            <span class="badge badge-light border px-2 py-1">${qtyPesan.toLocaleString('id-ID')} ${satuan}</span>
                                        </td>
                                        <td class="align-middle">
                                            <input type="number" step="any" min="0" class="form-control form-control-sm text-center font-weight-bold input-verifikasi-qty" 
                                                name="items[${item.id_detail}][qty_terima]" 
                                                value="${qtyTerima}" 
                                                data-qty-pesan="${qtyPesan}" 
                                                data-satuan="${satuan}"
                                                required style="border-radius: 6px;">
                                            <small class="status-verifikasi-badge d-block mt-1 text-center font-weight-bold"></small>
                                        </td>
                                    `;
                                    containerItems.appendChild(tr);

                                    const inputQty = tr.querySelector('.input-verifikasi-qty');
                                    const statusBadge = tr.querySelector('.status-verifikasi-badge');

                                    function updateKesesuaianBadge() {
                                        const val = parseFloat(inputQty.value) || 0;
                                        if (val > qtyPesan) {
                                            const selisih = val - qtyPesan;
                                            statusBadge.className = 'status-verifikasi-badge d-block mt-1 text-center font-weight-bold text-warning';
                                            statusBadge.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i> Lebih +${selisih.toLocaleString('id-ID')} ${satuan}`;
                                        } else if (val < qtyPesan) {
                                            const selisih = qtyPesan - val;
                                            statusBadge.className = 'status-verifikasi-badge d-block mt-1 text-center font-weight-bold text-info';
                                            statusBadge.innerHTML = `<i class="fas fa-info-circle mr-1"></i> Kurang -${selisih.toLocaleString('id-ID')} ${satuan}`;
                                        } else {
                                            statusBadge.className = 'status-verifikasi-badge d-block mt-1 text-center font-weight-bold text-success';
                                            statusBadge.innerHTML = `<i class="fas fa-check-circle mr-1"></i> Sesuai pesanan`;
                                        }
                                    }

                                    inputQty.addEventListener('input', updateKesesuaianBadge);
                                    updateKesesuaianBadge();
                                });
                            }

                            $('#modalVerifikasiTerima').modal('show');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal memuat data transaksi untuk verifikasi.');
                        });
                });
            });

            // Handler Modal Detail Pembelian
            const detailButtons = document.querySelectorAll('.btn-detail-pembelian');
            detailButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    fetch(`/barang-masuk/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('detail_no_faktur').textContent = data.no_faktur;
                            document.getElementById('detail_supplier').textContent = data.supplier ? data.supplier.nama_supplier : '-';
                            document.getElementById('detail_tgl_faktur').textContent = data.tgl_faktur;
                            document.getElementById('detail_tgl_terima').textContent = data.tgl_terima;
                            document.getElementById('detail_total_belanja').textContent = 'Rp ' + (parseFloat(data.total_belanja) || 0).toLocaleString('id-ID');

                            let statusBadge = '';
                            if (data.status === 'Diterima') {
                                statusBadge = '<span class="badge badge-success px-2 py-1">Diterima</span>';
                            } else if (data.status === 'Draft') {
                                statusBadge = '<span class="badge badge-warning text-dark px-2 py-1">Draft</span>';
                            } else {
                                statusBadge = '<span class="badge badge-danger px-2 py-1">Batal</span>';
                            }
                            document.getElementById('detail_status').innerHTML = statusBadge;

                            const containerItems = document.getElementById('detail_container_items');
                            containerItems.innerHTML = '';

                            if (data.details && data.details.length > 0) {
                                data.details.forEach((item, idx) => {
                                    const namaBarang = item.barang ? item.barang.nama_barang : 'Barang Terhapus';
                                    const satuan = (item.barang && item.barang.satuan) ? item.barang.satuan.nama_satuan : '';
                                    const qtyPesan = parseFloat(item.qty) || 0;
                                    const qtyTerima = item.qty_terima !== null ? parseFloat(item.qty_terima) : qtyPesan;
                                    const harga = parseFloat(item.harga) || 0;
                                    const subtotal = parseFloat(item.subtotal) || (qtyTerima * harga);

                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td>${idx + 1}</td>
                                        <td>
                                            <strong class="text-dark d-block">${namaBarang}</strong>
                                        </td>
                                        <td class="text-center font-weight-bold">${qtyPesan.toLocaleString('id-ID')} ${satuan}</td>
                                        <td class="text-center font-weight-bold text-primary">${data.status === 'Diterima' ? qtyTerima.toLocaleString('id-ID') + ' ' + satuan : '-'}</td>
                                        <td class="text-right">Rp ${harga.toLocaleString('id-ID')}</td>
                                        <td class="text-right font-weight-bold">Rp ${subtotal.toLocaleString('id-ID')}</td>
                                    `;
                                    containerItems.appendChild(tr);
                                });
                            } else {
                                containerItems.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Tidak ada detail item</td></tr>';
                            }

                            $('#modalDetailPembelian').modal('show');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal memuat detail faktur pembelian.');
                        });
                });
            });

            // Handler Modal Edit Pembelian (Draft)
            const editButtons = document.querySelectorAll('.btn-edit-pembelian');
            const editContainerBaris = document.getElementById('editContainerBarisItem');
            const editBtnTambahBaris = document.getElementById('editBtnTambahBarisItem');
            let editItemIndex = 0;

            if (editBtnTambahBaris && editContainerBaris) {
                editBtnTambahBaris.addEventListener('click', function () {
                    tambahBarisEdit();
                });
            }

            function tambahBarisEdit(selectedBarangId = '', qty = '', harga = '') {
                if (!editContainerBaris) return;

                const valQty = (qty !== '' && qty !== null && qty !== undefined) ? parseFloat(qty) : '';
                const valHarga = (harga !== '' && harga !== null && harga !== undefined) ? parseFloat(harga) : '';

                const tr = document.createElement('tr');
                tr.className = 'edit-baris-item';
                tr.innerHTML = `
                    <td>
                        <select class="form-control edit-select-barang" name="items[${editItemIndex}][id_barang]" required style="border-radius: 8px;">
                            ${barangOptionsHtml}
                        </select>
                        <small class="text-muted edit-info-satuan-item d-block mt-1"></small>
                    </td>
                    <td>
                        <input type="number" step="any" min="0.01" class="form-control edit-input-qty" name="items[${editItemIndex}][qty]" value="${valQty}" placeholder="0" required style="border-radius: 8px;" inputmode="decimal">
                    </td>
                    <td>
                        <input type="number" step="1" min="0" class="form-control edit-input-harga" name="items[${editItemIndex}][harga]" value="${valHarga}" placeholder="0" required style="border-radius: 8px;" inputmode="numeric">
                    </td>
                    <td class="text-right align-middle">
                        <span class="font-weight-bold text-dark edit-input-subtotal-text">Rp 0</span>
                    </td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-link text-muted p-0 edit-btn-hapus-baris" title="Hapus Baris" style="font-size: 16px;">&times;</button>
                    </td>
                `;
                editContainerBaris.appendChild(tr);

                const selectBarang = tr.querySelector('.edit-select-barang');
                if (selectedBarangId) {
                    selectBarang.value = selectedBarangId;
                }

                bindEditRowEvents(tr);
                editItemIndex++;
            }

            function bindEditRowEvents(row) {
                const selectBarang = row.querySelector('.edit-select-barang');
                const inputQty = row.querySelector('.edit-input-qty');
                const inputHarga = row.querySelector('.edit-input-harga');
                const inputSubtotalText = row.querySelector('.edit-input-subtotal-text');
                const btnHapus = row.querySelector('.edit-btn-hapus-baris');
                const infoSatuan = row.querySelector('.edit-info-satuan-item');

                [inputQty, inputHarga].forEach(input => {
                    input.addEventListener('keydown', function(e) {
                        if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                            (e.keyCode === 65 && e.ctrlKey === true) ||
                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                            return;
                        }
                        if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                            e.preventDefault();
                        }
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });

                    input.addEventListener('input', function() {
                        if (input.classList.contains('edit-input-harga')) {
                            this.value = this.value.replace(/[^0-9]/g, '');
                        } else {
                            this.value = this.value.replace(/[^0-9.]/g, '');
                        }
                        hitungEditSubtotal();
                    });
                });

                selectBarang.addEventListener('change', function () {
                    const opt = this.options[this.selectedIndex];
                    if (opt && opt.value !== '') {
                        const satuan = opt.getAttribute('data-satuan') || '';
                        infoSatuan.textContent = satuan ? `Satuan: ${satuan}` : '';
                    }
                    hitungEditSubtotal();
                });

                if (selectBarang.value) {
                    const opt = selectBarang.options[selectBarang.selectedIndex];
                    if (opt && opt.value !== '') {
                        const satuan = opt.getAttribute('data-satuan') || '';
                        infoSatuan.textContent = satuan ? `Satuan: ${satuan}` : '';
                    }
                }

                btnHapus.addEventListener('click', function () {
                    const totalRows = editContainerBaris.querySelectorAll('.edit-baris-item').length;
                    if (totalRows > 1) {
                        row.remove();
                        hitungEditGrandTotal();
                    } else {
                        alert('Minimal 1 barang harus ada dalam transaksi pembelian!');
                    }
                });

                function hitungEditSubtotal() {
                    const qty = parseFloat(inputQty.value) || 0;
                    const harga = parseFloat(inputHarga.value) || 0;
                    const subtotal = qty * harga;
                    if (inputSubtotalText) {
                        inputSubtotalText.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                    }
                    hitungEditGrandTotal();
                }

                hitungEditSubtotal();
            }

            function hitungEditGrandTotal() {
                if (!editContainerBaris) return;
                let grandTotal = 0;
                const rows = editContainerBaris.querySelectorAll('.edit-baris-item');
                rows.forEach(row => {
                    const qty = parseFloat(row.querySelector('.edit-input-qty').value) || 0;
                    const harga = parseFloat(row.querySelector('.edit-input-harga').value) || 0;
                    grandTotal += (qty * harga);
                });
                const grandTotalElem = document.getElementById('editGrandTotalBelanja');
                if (grandTotalElem) {
                    grandTotalElem.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
                }
            }

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    fetch(`/barang-masuk/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('formEditPembelian').action = `/barang-masuk/${id}`;
                            document.getElementById('edit_no_faktur').value = data.no_faktur || '';
                            document.getElementById('edit_id_supplier').value = data.id_supplier || '';
                            document.getElementById('edit_tgl_faktur').value = data.tgl_faktur || '';
                            document.getElementById('edit_tgl_terima').value = data.tgl_terima || '';
                            document.getElementById('edit_status').value = data.status || 'Draft';

                            if (editContainerBaris) {
                                editContainerBaris.innerHTML = '';
                                editItemIndex = 0;

                                if (data.details && data.details.length > 0) {
                                    data.details.forEach(item => {
                                        tambahBarisEdit(item.id_barang, item.qty, item.harga);
                                    });
                                } else {
                                    tambahBarisEdit();
                                }
                            }

                            $('#modalEditPembelian').modal('show');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal memuat data faktur untuk diedit.');
                        });
                });
            });
        });
    </script>
@endsection
