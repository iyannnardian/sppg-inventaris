@extends('adminlte::page')

@section('title', 'Pembelian & Barang Masuk - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 26px;">Pembelian</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Transaksi pembelian dari supplier — status Draft / Diterima / Batal</p>
        </div>
        @if(!in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg']))
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
                                <th style="width: 10%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3 text-center">STATUS</th>
                                <th style="width: 16%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="text-right pr-4 py-3">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $t)
                            <tr>
                                <td class="pl-4">{{ $t->no_faktur }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($t->tgl_faktur)->format('d M Y') }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($t->tgl_terima)->format('d M Y') }}
                                </td>
                                <td>
                                    {{ $t->supplier->nama_supplier ?? '-' }}
                                </td>
                                <td>
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

                                        @if(!in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg']))
                                            <!-- Tombol Terima (jika belum Diterima) -->
                                            @if($t->status !== 'Diterima')
                                                <form action="{{ route('barang-masuk.update-status', $t->id_pembelian) }}" method="POST" class="d-inline mr-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Diterima">
                                                    <button type="submit" class="btn btn-success btn-sm font-weight-bold" onclick="return confirm('Proses faktur ini menjadi DITERIMA? Barang akan langsung masuk ke stok persediaan.');" title="Terima Barang">
                                                        <i class="fas fa-check mr-1"></i> Terima
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Tombol Batalkan (jika belum Batal) -->
                                            @if($t->status !== 'Batal')
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

    @if(!in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg']))
        @include('barang-masuk.create')
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
                    <option value="{{ $b->id_barang }}" data-harga="{{ $b->harga_terakhir }}" data-satuan="{{ $b->satuan->nama_satuan ?? '' }}">
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
                        // Izinkan Backspace, Delete, Tab, Escape, Enter, Decimal (.), Arrow keys
                        if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                            (e.keyCode === 65 && e.ctrlKey === true) ||
                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                            return;
                        }
                        // Cegah e, E, +, -
                        if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                            e.preventDefault();
                        }
                        // Pastikan karakter adalah angka
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });

                    input.addEventListener('input', function() {
                        // Replaces non-numeric characters immediately
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
                        const harga = opt.getAttribute('data-harga') || 0;
                        const satuan = opt.getAttribute('data-satuan') || '';
                        if (!inputHarga.value || inputHarga.value == 0) {
                            inputHarga.value = harga;
                        }
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
                                    const qty = parseFloat(item.qty) || 0;
                                    const harga = parseFloat(item.harga) || 0;
                                    const subtotal = parseFloat(item.subtotal) || (qty * harga);

                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td>${idx + 1}</td>
                                        <td class="font-weight-bold text-dark">${namaBarang}</td>
                                        <td class="text-center font-weight-bold">${qty.toLocaleString('id-ID')}</td>
                                        <td class="text-right">Rp ${harga.toLocaleString('id-ID')}</td>
                                        <td class="text-right font-weight-bold">Rp ${subtotal.toLocaleString('id-ID')}</td>
                                    `;
                                    containerItems.appendChild(tr);
                                });
                            } else {
                                containerItems.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada detail item</td></tr>';
                            }

                            $('#modalDetailPembelian').modal('show');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal memuat detail faktur pembelian.');
                        });
                });
            });
        });
    </script>
@endsection
