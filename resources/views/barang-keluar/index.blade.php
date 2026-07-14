@extends('adminlte::page')

@section('title', 'Barang Keluar - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-minus-circle text-danger mr-2"></i>Barang Keluar</h1>
            <p class="text-muted mb-0">Kelola daftar transaksi barang keluar / pemakaian</p>
        </div>
        @if(Auth::user()->role !== 'kepala dapur')
            <div>
                <button class="btn btn-danger shadow-sm" data-toggle="modal" data-target="#modalTambahKeluar">
                    <i class="fas fa-plus mr-1"></i> Barang Keluar
                </button>
            </div>
        @endif
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Gagal memproses data:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Form Filter -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('barang-keluar.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-4 form-group mb-md-0">
                    <label for="tanggal_awal" class="small font-weight-bold">Mulai tanggal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggalAwal }}">
                </div>
                <div class="col-md-4 form-group mb-md-0">
                    <label for="tanggal_akhir" class="small font-weight-bold">Hingga tanggal</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
                </div>
                <div class="col-md-4 d-flex mb-md-0">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter mr-1"></i> Filter</button>
                    <a href="{{ route('barang-keluar.index') }}" class="btn btn-default border w-100 ml-2 text-center">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="card card-outline card-danger shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Riwayat transaksi</h3>
        </div>
        <div class="card-body p-0">
            @if($transaksis->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-arrow-up fa-3x mb-3 text-danger" style="opacity: 0.5;"></i>
                    <h5>Belum ada riwayat transaksi keluar</h5>
                    <p>Silakan catat barang keluar menggunakan tombol di atas.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 20%">Petugas</th>
                                <th style="width: 25%">Nama Barang</th>
                                <th style="width: 15%">Volume</th>
                                <th style="width: 10%">Satuan</th>
                                @if(Auth::user()->role !== 'kepala dapur')
                                    <th style="width: 10%" class="text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $index => $t)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_keluar)->format('d M Y') }}</td>
                                <td><i class="fas fa-user mr-1 text-xs text-secondary"></i> {{ $t->user->name ?? 'User' }}</td>
                                <td class="font-weight-bold text-dark">{{ $t->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                                <td class="text-danger font-weight-bold">-{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                                <td class="text-muted">{{ $t->barang->satuan ?? '' }}</td>
                                @if(Auth::user()->role !== 'kepala dapur')
                                <td class="text-right">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-outline-warning btn-sm btn-edit-keluar" 
                                                data-id="{{ $t->id_keluar }}"
                                                data-barang="{{ $t->id_barang }}"
                                                data-jumlah="{{ $t->jumlah }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($t->tanggal_keluar)->format('Y-m-d') }}"
                                                data-toggle="modal" 
                                                data-target="#modalEditKeluar"
                                                title="Edit Transaksi">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <form action="{{ route('barang-keluar.destroy', $t->id_keluar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi keluar ini? Penghapusan akan mengembalikan stok.');" class="d-inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Transaksi"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if(Auth::user()->role !== 'kepala dapur')
        <!-- Modal Tambah -->
        <div class="modal fade" id="modalTambahKeluar" tabindex="-1" role="dialog" aria-labelledby="modalTambahKeluarLabel" aria-hidden="true">
            <div class="modal-dialog text-left" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white border-0">
                        <h5 class="modal-title" id="modalTambahKeluarLabel"><i class="fas fa-arrow-up mr-2 text-white"></i>Catat Transaksi Barang Keluar</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('barang-keluar.store') }}" method="POST" id="formTambahKeluar">
                        @csrf
                        <div class="modal-body">
                            <!-- Alert Peringatan Stok Kritis/Kurang -->
                            <div class="alert alert-danger border-0 shadow-sm d-none" id="tambah-keluar-warning-alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span id="tambah-keluar-warning-msg">Jumlah pengeluaran melebihi stok yang tersedia saat ini!</span>
                            </div>

                            <div class="form-group">
                                <label for="id_barang_keluar">Pilih Barang <span class="text-danger">*</span></label>
                                <select class="form-control" id="id_barang_keluar" name="id_barang" required>
                                    <option value="" disabled selected>-- Pilih Barang --</option>
                                    @foreach($barangs as $b)
                                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Tersedia: {{ $b->stok }} {{ $b->satuan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_keluar">Jumlah Keluar <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="jumlah_keluar" name="jumlah" placeholder="Masukkan jumlah qty" required min="1">
                                <small class="text-muted d-block mt-1" id="info-satuan-keluar"></small>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_keluar">Tanggal Keluar <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger" id="btnSubmitTambahKeluar"><i class="fas fa-save mr-1"></i> Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditKeluar" tabindex="-1" role="dialog" aria-labelledby="modalEditKeluarLabel" aria-hidden="true">
            <div class="modal-dialog text-left" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-warning text-white border-0">
                        <h5 class="modal-title" id="modalEditKeluarLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Transaksi Barang Keluar</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formEditKeluar" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Alert Peringatan Stok Kritis/Kurang -->
                            <div class="alert alert-danger border-0 shadow-sm d-none" id="edit-keluar-warning-alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span id="edit-keluar-warning-msg">Jumlah pengeluaran melebihi stok yang tersedia saat ini!</span>
                            </div>

                            <div class="form-group">
                                <label for="edit_id_barang_keluar">Pilih Barang <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_id_barang_keluar" name="id_barang" required>
                                    @foreach($barangs as $b)
                                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Stok Saat Ini: {{ $b->stok }} {{ $b->satuan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_jumlah_keluar">Jumlah Keluar <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_jumlah_keluar" name="jumlah" required min="1">
                                <small class="text-muted d-block mt-1" id="edit-info-satuan-keluar"></small>
                            </div>
                            <div class="form-group">
                                <label for="edit_tanggal_keluar">Tanggal Keluar <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_tanggal_keluar" name="tanggal_keluar" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-white" id="btnSubmitEditKeluar"><i class="fas fa-save mr-1"></i> Perbarui Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(Auth::user()->role !== 'kepala dapur')
            // ==================== 1. TAMBAH KELUAR ====================
            const idBarangKeluar = document.getElementById('id_barang_keluar');
            const jumlahKeluar = document.getElementById('jumlah_keluar');
            const btnSubmitTambahKeluar = document.getElementById('btnSubmitTambahKeluar');
            const warningAlertKeluar = document.getElementById('tambah-keluar-warning-alert');
            const warningMsgKeluar = document.getElementById('tambah-keluar-warning-msg');
            const infoSatuanKeluar = document.getElementById('info-satuan-keluar');

            function validasiStokTambahKeluar() {
                const selected = idBarangKeluar.options[idBarangKeluar.selectedIndex];
                if (!selected || selected.value === "") {
                    infoSatuanKeluar.textContent = '';
                    warningAlertKeluar.classList.add('d-none');
                    btnSubmitTambahKeluar.disabled = false;
                    return;
                }

                const stok = parseInt(selected.getAttribute('data-stok')) || 0;
                const satuan = selected.getAttribute('data-satuan') || '';
                const qty = parseInt(jumlahKeluar.value) || 0;

                infoSatuanKeluar.textContent = `Satuan barang: ${satuan}. Stok tersedia: ${stok} ${satuan}.`;

                if (qty > stok) {
                    warningMsgKeluar.textContent = `Stok tidak mencukupi! Pengeluaran (${qty} ${satuan}) melebihi stok yang tersedia (${stok} ${satuan}).`;
                    warningAlertKeluar.classList.remove('d-none');
                    btnSubmitTambahKeluar.disabled = true;
                } else {
                    warningAlertKeluar.classList.add('d-none');
                    btnSubmitTambahKeluar.disabled = false;
                }
            }

            idBarangKeluar.addEventListener('change', validasiStokTambahKeluar);
            jumlahKeluar.addEventListener('input', validasiStokTambahKeluar);

            // ==================== 2. EDIT KELUAR ====================
            const editKeluarButtons = document.querySelectorAll('.btn-edit-keluar');
            const formEditKeluar = document.getElementById('formEditKeluar');
            const editIdBarangKeluar = document.getElementById('edit_id_barang_keluar');
            const editJumlahKeluar = document.getElementById('edit_jumlah_keluar');
            const editTanggalKeluar = document.getElementById('edit_tanggal_keluar');
            const editInfoSatuanKeluar = document.getElementById('edit-info-satuan-keluar');
            const editKeluarWarningAlert = document.getElementById('edit-keluar-warning-alert');
            const editKeluarWarningMsg = document.getElementById('edit-keluar-warning-msg');
            const btnSubmitEditKeluar = document.getElementById('btnSubmitEditKeluar');

            let origBarangKeluar = '';
            let origJumlahKeluar = 0;

            editKeluarButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    origBarangKeluar = this.getAttribute('data-barang');
                    origJumlahKeluar = parseInt(this.getAttribute('data-jumlah')) || 0;
                    const tanggal = this.getAttribute('data-tanggal');

                    formEditKeluar.setAttribute('action', `/barang-keluar/${id}`);
                    editIdBarangKeluar.value = origBarangKeluar;
                    editJumlahKeluar.value = origJumlahKeluar;
                    editTanggalKeluar.value = tanggal;

                    validasiStokEditKeluar();
                });
            });

            function validasiStokEditKeluar() {
                const selected = editIdBarangKeluar.options[editIdBarangKeluar.selectedIndex];
                if (!selected || selected.value === "") {
                    editInfoSatuanKeluar.textContent = '';
                    editKeluarWarningAlert.classList.add('d-none');
                    btnSubmitEditKeluar.disabled = false;
                    return;
                }

                const currentStok = parseInt(selected.getAttribute('data-stok')) || 0;
                const satuan = selected.getAttribute('data-satuan') || '';
                const qty = parseInt(editJumlahKeluar.value) || 0;

                let availableStok = currentStok;
                if (selected.value === origBarangKeluar) {
                    availableStok = currentStok + origJumlahKeluar;
                    editInfoSatuanKeluar.textContent = `Satuan barang: ${satuan}. Stok tersedia (termasuk qty saat ini): ${availableStok} ${satuan}.`;
                } else {
                    editInfoSatuanKeluar.textContent = `Satuan barang: ${satuan}. Stok tersedia: ${availableStok} ${satuan}.`;
                }

                if (qty > availableStok) {
                    editKeluarWarningMsg.textContent = `Stok tidak mencukupi! Pengeluaran (${qty} ${satuan}) melebihi stok yang tersedia (${availableStok} ${satuan}).`;
                    editKeluarWarningAlert.classList.remove('d-none');
                    btnSubmitEditKeluar.disabled = true;
                } else {
                    editKeluarWarningAlert.classList.add('d-none');
                    btnSubmitEditKeluar.disabled = false;
                }
            }

            editIdBarangKeluar.addEventListener('change', validasiStokEditKeluar);
            editJumlahKeluar.addEventListener('input', validasiStokEditKeluar);
            @endif
        });
    </script>
@endsection
