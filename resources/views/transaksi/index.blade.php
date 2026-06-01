@extends('adminlte::page')

@section('title', 'Transaksi Barang - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-exchange-alt mr-2"></i>Transaksi Barang</h1>
            <p class="text-muted mb-0">Catat dan pantau transaksi barang masuk & keluar dapur</p>
        </div>
        @if(Auth::user()->role !== 'kepala dapur')
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahMasuk"><i class="fas fa-arrow-down mr-1"></i> Barang Masuk</button>
                <button class="btn btn-danger ml-1" data-toggle="modal" data-target="#modalTambahKeluar"><i class="fas fa-arrow-up mr-1"></i> Barang Keluar</button>
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

    <!-- Form Filter Transaksi -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('transaksi.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-3 form-group mb-md-0">
                    <label for="tipe" class="small font-weight-bold">Tipe Transaksi</label>
                    <select class="form-control" id="tipe" name="tipe">
                        <option value="all" {{ $tipe == 'all' ? 'selected' : '' }}>Semua Tipe</option>
                        <option value="masuk" {{ $tipe == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="keluar" {{ $tipe == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                </div>
                <div class="col-md-3 form-group mb-md-0">
                    <label for="tanggal_awal" class="small font-weight-bold">Mulai Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggalAwal }}">
                </div>
                <div class="col-md-3 form-group mb-md-0">
                    <label for="tanggal_akhir" class="small font-weight-bold">Hingga Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
                </div>
                <div class="col-md-3 d-flex mb-md-0">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter mr-1"></i> Filter</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-default border w-100 ml-1 text-center">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Transaksi -->
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Riwayat Transaksi</h3>
        </div>
        <div class="card-body p-0">
            @if($transaksis->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-exchange-alt fa-3x mb-3 text-secondary"></i>
                    <h5>Belum ada riwayat transaksi</h5>
                    <p>Silakan catat transaksi masuk atau keluar menggunakan tombol di atas.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 10%">Tipe</th>
                                <th style="width: 18%">Barang</th>
                                <th style="width: 8%">Jumlah</th>
                                <th style="width: 12%">Harga Satuan</th>
                                <th style="width: 12%">Subtotal</th>
                                <th style="width: 10%">Tanggal</th>
                                <th style="width: 15%">Supplier / Detail</th>
                                <th style="width: 10%">Operator</th>
                                @if(Auth::user()->role !== 'kepala dapur')
                                    <th style="width: 10%" class="text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $index => $t)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($t->tipe == 'masuk')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-arrow-down mr-1"></i> Masuk</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-arrow-up mr-1"></i> Keluar</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold text-dark">{{ $t->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                                <td>
                                    @if($t->tipe == 'masuk')
                                        <span class="text-success font-weight-bold">+{{ $t->jumlah }}</span>
                                    @else
                                        <span class="text-danger font-weight-bold">-{{ $t->jumlah }}</span>
                                    @endif
                                    <small class="text-muted">{{ $t->barang->satuan ?? '' }}</small>
                                </td>
                                <td>
                                    @if($t->tipe == 'masuk')
                                        <span class="text-muted">Rp {{ number_format($t->harga, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->tipe == 'masuk')
                                        <span class="font-weight-bold text-dark">Rp {{ number_format($t->harga * $t->jumlah, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if($t->tipe == 'masuk')
                                        <span class="text-secondary"><i class="fas fa-truck mr-1"></i> {{ $t->supplier->nama_supplier ?? 'Supplier Terhapus' }}</span>
                                    @else
                                        <span class="text-muted"><i class="fas fa-home mr-1"></i> Keperluan Dapur</span>
                                    @endif
                                </td>
                                <td>
                                    <small><i class="fas fa-user mr-1"></i> {{ $t->user->name ?? 'User' }}</small>
                                </td>
                                @if(Auth::user()->role !== 'kepala dapur')
                                <td class="text-right">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if($t->tipe == 'masuk')
                                            <button class="btn btn-outline-warning btn-sm btn-edit-masuk" 
                                                    data-id="{{ $t->id_transaksi }}"
                                                    data-barang="{{ $t->id_barang }}"
                                                    data-supplier="{{ $t->id_supplier }}"
                                                    data-jumlah="{{ $t->jumlah }}"
                                                    data-harga="{{ $t->harga }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($t->tanggal)->format('Y-m-d') }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalEditMasuk"
                                                    title="Edit Transaksi Masuk">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('transaksi.destroyMasuk', $t->id_transaksi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi masuk ini? Penghapusan akan mengembalikan stok.');" class="d-inline ml-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Transaksi Masuk"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @else
                                            <button class="btn btn-outline-warning btn-sm btn-edit-keluar" 
                                                    data-id="{{ $t->id_transaksi }}"
                                                    data-barang="{{ $t->id_barang }}"
                                                    data-jumlah="{{ $t->jumlah }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($t->tanggal)->format('Y-m-d') }}"
                                                    data-toggle="modal" 
                                                    data-target="#modalEditKeluar"
                                                    title="Edit Transaksi Keluar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('transaksi.destroyKeluar', $t->id_transaksi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi keluar ini? Penghapusan akan mengembalikan stok.');" class="d-inline ml-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Transaksi Keluar"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
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
        @include('transaksi.create')
        @include('transaksi.edit')
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(Auth::user()->role !== 'kepala dapur')
            // ==================== 1. QUICK IN (TAMBAH MASUK) ====================
            const idBarangMasuk = document.getElementById('id_barang_masuk');
            const infoSatuanMasuk = document.getElementById('info-satuan-masuk');

            idBarangMasuk.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                if (selected && selected.value !== "") {
                    const satuan = selected.getAttribute('data-satuan');
                    infoSatuanMasuk.textContent = `Satuan barang: ${satuan}`;
                } else {
                    infoSatuanMasuk.textContent = '';
                }
            });

            // ==================== 2. QUICK OUT (TAMBAH KELUAR) ====================
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


            // ==================== 3. EDIT MASUK ====================
            const editMasukButtons = document.querySelectorAll('.btn-edit-masuk');
            const formEditMasuk = document.getElementById('formEditMasuk');
            const editIdBarangMasuk = document.getElementById('edit_id_barang_masuk');
            const editIdSupplierMasuk = document.getElementById('edit_id_supplier_masuk');
            const editJumlahMasuk = document.getElementById('edit_jumlah_masuk');
            const editHargaMasuk = document.getElementById('edit_harga_masuk');
            const editTanggalMasuk = document.getElementById('edit_tanggal_masuk');
            const editInfoSatuanMasuk = document.getElementById('edit-info-satuan-masuk');
            const editMasukWarningAlert = document.getElementById('edit-masuk-warning-alert');
            const editMasukWarningMsg = document.getElementById('edit-masuk-warning-msg');
            const btnSubmitEditMasuk = document.getElementById('btnSubmitEditMasuk');

            let origBarangMasuk = '';
            let origJumlahMasuk = 0;

            editMasukButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    origBarangMasuk = this.getAttribute('data-barang');
                    origJumlahMasuk = parseInt(this.getAttribute('data-jumlah')) || 0;
                    const supplier = this.getAttribute('data-supplier');
                    const harga = this.getAttribute('data-harga');
                    const tanggal = this.getAttribute('data-tanggal');

                    formEditMasuk.setAttribute('action', `/transaksi/masuk/${id}`);
                    editIdBarangMasuk.value = origBarangMasuk;
                    editIdSupplierMasuk.value = supplier;
                    editJumlahMasuk.value = origJumlahMasuk;
                    editHargaMasuk.value = harga;
                    editTanggalMasuk.value = tanggal;

                    validasiStokEditMasuk();
                });
            });

            function validasiStokEditMasuk() {
                const selected = editIdBarangMasuk.options[editIdBarangMasuk.selectedIndex];
                if (!selected || selected.value === "") {
                    editInfoSatuanMasuk.textContent = '';
                    editMasukWarningAlert.classList.add('d-none');
                    btnSubmitEditMasuk.disabled = false;
                    return;
                }

                const currentStok = parseInt(selected.getAttribute('data-stok')) || 0;
                const satuan = selected.getAttribute('data-satuan') || '';
                const qty = parseInt(editJumlahMasuk.value) || 0;

                editInfoSatuanMasuk.textContent = `Satuan barang: ${satuan}. Stok saat ini: ${currentStok} ${satuan}.`;

                if (selected.value === origBarangMasuk) {
                    // Jika barang sama, stok baru tidak boleh membuat total stok menjadi negatif
                    // Perubahan stok = new_qty - old_qty. Stok akhir = currentStok + (new_qty - old_qty) >= 0
                    // yang berarti new_qty >= old_qty - currentStok
                    const minQty = origJumlahMasuk - currentStok;
                    if (qty < minQty) {
                        editMasukWarningMsg.textContent = `Gagal! Pengurangan jumlah barang masuk terlalu banyak. Stok akhir ${selected.text.split('(')[0]} akan bernilai negatif jika jumlah kurang dari ${minQty} ${satuan}.`;
                        editMasukWarningAlert.classList.remove('d-none');
                        btnSubmitEditMasuk.disabled = true;
                    } else {
                        editMasukWarningAlert.classList.add('d-none');
                        btnSubmitEditMasuk.disabled = false;
                    }
                } else {
                    // Jika ganti barang, barang lama kehilangan 'origJumlahMasuk' dari stoknya.
                    // Ini butuh pengecekan apakah barang lama memiliki stok >= origJumlahMasuk.
                    // Untuk mendapatkan stok barang lama, kita cari option barang lama di select
                    let origOption = null;
                    for (let i = 0; i < editIdBarangMasuk.options.length; i++) {
                        if (editIdBarangMasuk.options[i].value === origBarangMasuk) {
                            origOption = editIdBarangMasuk.options[i];
                            break;
                        }
                    }

                    if (origOption) {
                        const origStok = parseInt(origOption.getAttribute('data-stok')) || 0;
                        if (origStok < origJumlahMasuk) {
                            editMasukWarningMsg.textContent = `Gagal! Stok barang asal (${origOption.text.split('(')[0]}) tidak mencukupi untuk dikurangi (${origStok} < ${origJumlahMasuk} ${satuan}). Hapus atau ubah transaksi lain terlebih dahulu.`;
                            editMasukWarningAlert.classList.remove('d-none');
                            btnSubmitEditMasuk.disabled = true;
                            return;
                        }
                    }

                    // Sementara untuk barang baru, ia menerima qty baru, jadi stoknya pasti naik (tidak akan negatif)
                    editMasukWarningAlert.classList.add('d-none');
                    btnSubmitEditMasuk.disabled = false;
                }
            }

            editIdBarangMasuk.addEventListener('change', validasiStokEditMasuk);
            editJumlahMasuk.addEventListener('input', validasiStokEditMasuk);


            // ==================== 4. EDIT KELUAR ====================
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

                    formEditKeluar.setAttribute('action', `/transaksi/keluar/${id}`);
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

                // Hitung stok tersedia sesungguhnya untuk barang terpilih
                let availableStok = currentStok;
                if (selected.value === origBarangKeluar) {
                    // Karena qty transaksi keluar lama sudah terpotong dari currentStok di DB,
                    // maka stok yang sesungguhnya tersedia untuk diedit adalah currentStok + origJumlahKeluar
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

