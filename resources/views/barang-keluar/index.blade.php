@extends('adminlte::page')

@section('title', 'Pengeluaran Barang - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 26px;">Pengeluaran Barang</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Transaksi pengeluaran / pemakaian bahan baku dapur</p>
        </div>
        @if(!in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg']))
            <div class="mt-md-0 mt-3">
                <button class="btn btn-primary font-weight-bold px-3 py-2 shadow-sm" data-toggle="modal" data-target="#modalTambahPengeluaran" style="border-radius: 6px;">
                    + Catat Pengeluaran
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

    <!-- Form Filter Tgl Pengeluaran -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            <form action="{{ route('barang-keluar.index') }}" method="GET" class="form-inline flex-wrap align-items-center">
                <span class="font-weight-bold text-secondary mr-2" style="font-size: 14px;">Filter Tgl Pengeluaran:</span>
                
                <input type="date" class="form-control bg-white mr-2 my-1" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggalAwal }}" placeholder="dd/mm/yyyy" style="border-radius: 6px; font-size: 14px;">
                
                <span class="text-secondary mr-2 my-1">s.d.</span>
                
                <input type="date" class="form-control bg-white mr-2 my-1" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}" placeholder="dd/mm/yyyy" style="border-radius: 6px; font-size: 14px;">
                
                <button type="submit" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 mr-2 my-1" style="border-radius: 6px;">Terapkan</button>
                <a href="{{ route('barang-keluar.index') }}" class="btn btn-light border btn-sm text-secondary px-3 my-1" style="border-radius: 6px;">Reset</a>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Transaksi Pengeluaran -->
    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <div class="card-body p-0">
            @if($transaksis->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p class="mb-0">Belum ada riwayat transaksi pengeluaran barang.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                            <tr>
                                <th style="width: 10%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="pl-4 py-3">NO</th>
                                <th style="width: 25%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">TGL PENGELUARAN</th>
                                <th style="width: 30%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">PETUGAS</th>
                                <th style="width: 15%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3 text-center">TOTAL ITEM</th>
                                <th style="width: 20%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="text-right pr-4 py-3">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $index => $t)
                            <tr>
                                <td class="pl-4">{{ $index + 1 }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($t->tgl_pengeluaran)->format('d M Y') }}
                                </td>
                                <td>
                                    <i class="fas fa-user-circle mr-1 text-secondary"></i> {{ $t->user->nama ?? $t->user->name ?? 'User' }}
                                </td>
                                <td class="text-center font-weight-bold text-dark" style="font-size: 14px;">
                                    {{ $t->details->count() }} Item
                                </td>
                                <td class="text-right pr-4">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <!-- Tombol Detail -->
                                        <button type="button" class="btn btn-info btn-sm font-weight-bold btn-detail-pengeluaran mr-1" data-id="{{ $t->id_pengeluaran }}" title="Lihat Detail">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>

                                        @if(!in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg']))
                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-warning btn-sm font-weight-bold text-white btn-edit-pengeluaran mr-1" data-id="{{ $t->id_pengeluaran }}" title="Edit Transaksi">
                                                <i class="fas fa-pencil-alt mr-1"></i> Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('barang-keluar.destroy', $t->id_pengeluaran) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi pengeluaran ini? Stok barang akan otomatis dikembalikan.');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm font-weight-bold" title="Hapus Transaksi">
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
        @include('barang-keluar.create')
        @include('barang-keluar.edit')
    @endif

    @include('barang-keluar.show')
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barangOptionsHtml = `
                <option value="" disabled selected>— Pilih barang —</option>
                @foreach($barangs as $b)
                    <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan->nama_satuan ?? '' }}">
                        {{ $b->nama_barang }} (Stok: {{ number_format($b->stok, 0, ',', '.') }} {{ $b->satuan->nama_satuan ?? '' }})
                    </option>
                @endforeach
            `;

            let itemIndex = 1;

            const btnTambahBaris = document.getElementById('btnTambahBarisItem');
            const containerBaris = document.getElementById('containerBarisItem');
            const btnSubmitPengeluaran = document.getElementById('btnSubmitPengeluaran');

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
                            <input type="number" step="any" min="0.01" class="form-control input-qty" name="items[${itemIndex}][qty]" placeholder="0" required style="border-radius: 8px;" inputmode="decimal">
                            <small class="text-danger warning-stok-exceeded d-none font-weight-bold mt-1">Stok tidak mencukupi!</small>
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-link text-muted p-0 btn-hapus-baris" title="Hapus Baris" style="font-size: 16px;">&times;</button>
                        </td>
                    `;
                    containerBaris.appendChild(tr);
                    itemIndex++;
                    bindRowEvents(tr);
                });

                const initialRow = containerBaris.querySelector('.baris-item');
                if (initialRow) {
                    bindRowEvents(initialRow);
                }
            }

            function bindRowEvents(row) {
                const selectBarang = row.querySelector('.select-barang');
                const inputQty = row.querySelector('.input-qty');
                const infoSatuan = row.querySelector('.info-satuan-item');
                const warningStok = row.querySelector('.warning-stok-exceeded');
                const btnHapus = row.querySelector('.btn-hapus-baris');

                // DILARANG MASUKKAN HURUF / Mencegah pengetikan selain angka & desimal
                inputQty.addEventListener('keydown', function(e) {
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

                inputQty.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                    validasiStokBaris();
                });

                selectBarang.addEventListener('change', function () {
                    validasiStokBaris();
                });

                btnHapus.addEventListener('click', function () {
                    const totalRows = containerBaris.querySelectorAll('.baris-item').length;
                    if (totalRows > 1) {
                        row.remove();
                        checkAllStokValid();
                    } else {
                        alert('Minimal 1 barang harus ada dalam pengeluaran!');
                    }
                });

                function validasiStokBaris() {
                    const opt = selectBarang.options[selectBarang.selectedIndex];
                    if (opt && opt.value !== '') {
                        const stok = parseFloat(opt.getAttribute('data-stok')) || 0;
                        const satuan = opt.getAttribute('data-satuan') || '';
                        const qty = parseFloat(inputQty.value) || 0;

                        infoSatuan.textContent = `Stok tersedia: ${stok} ${satuan}`;

                        if (qty > stok) {
                            warningStok.textContent = `Stok tidak cukup! (Stok: ${stok} ${satuan})`;
                            warningStok.classList.remove('d-none');
                        } else {
                            warningStok.classList.add('d-none');
                        }
                    }
                    checkAllStokValid();
                }
            }

            function checkAllStokValid() {
                let isValid = true;
                const rows = containerBaris.querySelectorAll('.baris-item');
                rows.forEach(row => {
                    const select = row.querySelector('.select-barang');
                    const input = row.querySelector('.input-qty');
                    const warning = row.querySelector('.warning-stok-exceeded');

                    const opt = select.options[select.selectedIndex];
                    if (opt && opt.value !== '') {
                        const stok = parseFloat(opt.getAttribute('data-stok')) || 0;
                        const qty = parseFloat(input.value) || 0;
                        if (qty > stok || qty <= 0) {
                            isValid = false;
                        }
                    }
                });

                if (btnSubmitPengeluaran) {
                    btnSubmitPengeluaran.disabled = !isValid;
                }
            }

            // Handler Modal Detail Pengeluaran
            const detailButtons = document.querySelectorAll('.btn-detail-pengeluaran');
            detailButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    fetch(`/barang-keluar/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('detail_tgl_pengeluaran').textContent = data.tgl_pengeluaran;
                            document.getElementById('detail_nama_petugas').textContent = (data.user ? (data.user.nama || data.user.name) : '-');

                            const containerItems = document.getElementById('detail_container_items');
                            containerItems.innerHTML = '';

                            if (data.details && data.details.length > 0) {
                                data.details.forEach((item, idx) => {
                                    const namaBarang = item.barang ? item.barang.nama_barang : 'Barang Terhapus';
                                    const satuan = (item.barang && item.barang.satuan) ? item.barang.satuan.nama_satuan : '';
                                    const qty = parseFloat(item.qty) || 0;

                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td>${idx + 1}</td>
                                        <td class="font-weight-bold text-dark">${namaBarang}</td>
                                        <td class="text-right font-weight-bold text-danger">-${qty.toLocaleString('id-ID')} ${satuan}</td>
                                    `;
                                    containerItems.appendChild(tr);
                                });
                            } else {
                                containerItems.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada detail item</td></tr>';
                            }

                            $('#modalDetailPengeluaran').modal('show');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal memuat detail pengeluaran.');
                        });
                });
            });

            // Handler Modal Edit Pengeluaran
            const editButtons = document.querySelectorAll('.btn-edit-pengeluaran');
            const editContainerBaris = document.getElementById('editContainerBarisItem');
            const editBtnTambahBaris = document.getElementById('editBtnTambahBarisItem');
            const editBtnSubmitPengeluaran = document.getElementById('editBtnSubmitPengeluaran');
            let editItemIndex = 0;
            let editCurrentDetails = {};

            if (editBtnTambahBaris && editContainerBaris) {
                editBtnTambahBaris.addEventListener('click', function () {
                    tambahBarisEdit();
                });
            }

            function tambahBarisEdit(selectedBarangId = '', qty = '') {
                if (!editContainerBaris) return;

                const valQty = (qty !== '' && qty !== null && qty !== undefined) ? parseFloat(qty) : '';

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
                        <small class="text-danger edit-warning-stok-exceeded d-none font-weight-bold mt-1">Stok tidak mencukupi!</small>
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
                const infoSatuan = row.querySelector('.edit-info-satuan-item');
                const warningStok = row.querySelector('.edit-warning-stok-exceeded');
                const btnHapus = row.querySelector('.edit-btn-hapus-baris');

                inputQty.addEventListener('keydown', function(e) {
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

                inputQty.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                    validasiEditStokBaris();
                });

                selectBarang.addEventListener('change', function () {
                    validasiEditStokBaris();
                });

                btnHapus.addEventListener('click', function () {
                    const totalRows = editContainerBaris.querySelectorAll('.edit-baris-item').length;
                    if (totalRows > 1) {
                        row.remove();
                        checkAllEditStokValid();
                    } else {
                        alert('Minimal 1 barang harus ada dalam pengeluaran!');
                    }
                });

                function validasiEditStokBaris() {
                    const opt = selectBarang.options[selectBarang.selectedIndex];
                    if (opt && opt.value !== '') {
                        const barangId = opt.value;
                        const originalStok = parseFloat(opt.getAttribute('data-stok')) || 0;
                        const satuan = opt.getAttribute('data-satuan') || '';
                        const qty = parseFloat(inputQty.value) || 0;

                        const returnQty = editCurrentDetails[barangId] || 0;
                        const availableStok = originalStok + returnQty;

                        infoSatuan.textContent = `Stok tersedia: ${availableStok} ${satuan}`;

                        if (qty > availableStok) {
                            warningStok.textContent = `Stok tidak cukup! (Stok tersedia: ${availableStok} ${satuan})`;
                            warningStok.classList.remove('d-none');
                        } else {
                            warningStok.classList.add('d-none');
                        }
                    }
                    checkAllEditStokValid();
                }

                validasiEditStokBaris();
            }

            function checkAllEditStokValid() {
                if (!editContainerBaris) return;
                let isValid = true;
                const rows = editContainerBaris.querySelectorAll('.edit-baris-item');
                rows.forEach(row => {
                    const select = row.querySelector('.edit-select-barang');
                    const input = row.querySelector('.edit-input-qty');

                    const opt = select.options[select.selectedIndex];
                    if (opt && opt.value !== '') {
                        const barangId = opt.value;
                        const originalStok = parseFloat(opt.getAttribute('data-stok')) || 0;
                        const qty = parseFloat(input.value) || 0;
                        const returnQty = editCurrentDetails[barangId] || 0;
                        const availableStok = originalStok + returnQty;

                        if (qty > availableStok || qty <= 0) {
                            isValid = false;
                        }
                    }
                });

                if (editBtnSubmitPengeluaran) {
                    editBtnSubmitPengeluaran.disabled = !isValid;
                }
            }

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    fetch(`/barang-keluar/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('formEditPengeluaran').action = `/barang-keluar/${id}`;
                            document.getElementById('edit_tgl_pengeluaran').value = data.tgl_pengeluaran || '';

                            editCurrentDetails = {};
                            if (data.details && data.details.length > 0) {
                                data.details.forEach(item => {
                                    editCurrentDetails[item.id_barang] = (editCurrentDetails[item.id_barang] || 0) + (parseFloat(item.qty) || 0);
                                });
                            }

                            if (editContainerBaris) {
                                editContainerBaris.innerHTML = '';
                                editItemIndex = 0;

                                if (data.details && data.details.length > 0) {
                                    data.details.forEach(item => {
                                        tambahBarisEdit(item.id_barang, item.qty);
                                    });
                                } else {
                                    tambahBarisEdit();
                                }
                            }

                            $('#modalEditPengeluaran').modal('show');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal memuat data transaksi pengeluaran untuk diedit.');
                        });
                });
            });
        });
    </script>
@endsection
