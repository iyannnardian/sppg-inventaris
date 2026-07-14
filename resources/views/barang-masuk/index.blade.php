@extends('adminlte::page')

@section('title', 'Barang Masuk - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-plus-circle text-success mr-2"></i>Barang Masuk</h1>
            <p class="text-muted mb-0">Kelola daftar transaksi barang masuk / supply</p>
        </div>
        @if(Auth::user()->role !== 'kepala dapur')
            <div>
                <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#modalTambahMasuk">
                    <i class="fas fa-plus mr-1"></i> Barang Masuk
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
            <form action="{{ route('barang-masuk.index') }}" method="GET" class="row align-items-end">
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
                    <a href="{{ route('barang-masuk.index') }}" class="btn btn-default border w-100 ml-2 text-center">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Riwayat transaksi</h3>
        </div>
        <div class="card-body p-0">
            @if($transaksis->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-arrow-down fa-3x mb-3 text-success" style="opacity: 0.5;"></i>
                    <h5>Belum ada riwayat transaksi masuk</h5>
                    <p>Silakan catat barang masuk menggunakan tombol di atas.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 20%">Supplier</th>
                                <th style="width: 20%">Nama Barang</th>
                                <th style="width: 10%">Volume</th>
                                <th style="width: 10%">Satuan</th>
                                <th style="width: 10%">Harga Beli</th>
                                @if(Auth::user()->role !== 'kepala dapur')
                                    <th style="width: 10%" class="text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $index => $t)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_masuk)->format('d M Y') }}</td>
                                <td class="text-secondary"><i class="fas fa-truck mr-1 text-xs"></i> {{ $t->supplier->nama_supplier ?? 'Supplier Terhapus' }}</td>
                                <td class="font-weight-bold text-dark">{{ $t->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                                <td class="text-success font-weight-bold">+{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                                <td class="text-muted">{{ $t->barang->satuan ?? '' }}</td>
                                <td>Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                                @if(Auth::user()->role !== 'kepala dapur')
                                <td class="text-right">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-outline-warning btn-sm btn-edit-masuk" 
                                                data-id="{{ $t->id_masuk }}"
                                                data-barang="{{ $t->id_barang }}"
                                                data-supplier="{{ $t->id_supplier }}"
                                                data-jumlah="{{ $t->jumlah }}"
                                                data-harga="{{ $t->harga }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($t->tanggal_masuk)->format('Y-m-d') }}"
                                                data-toggle="modal" 
                                                data-target="#modalEditMasuk"
                                                title="Edit Transaksi">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <form action="{{ route('barang-masuk.destroy', $t->id_masuk) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi masuk ini? Penghapusan akan mengembalikan stok.');" class="d-inline ml-1">
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
        <div class="modal fade" id="modalTambahMasuk" tabindex="-1" role="dialog" aria-labelledby="modalTambahMasukLabel" aria-hidden="true">
            <div class="modal-dialog text-left" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title" id="modalTambahMasukLabel"><i class="fas fa-arrow-down mr-2"></i>Catat Transaksi Barang Masuk</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('barang-masuk.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="id_barang_masuk">Pilih Barang <span class="text-danger">*</span></label>
                                <select class="form-control" id="id_barang_masuk" name="id_barang" required>
                                    <option value="" disabled selected>-- Pilih Barang --</option>
                                    @foreach($barangs as $b)
                                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Tersedia: {{ $b->stok }} {{ $b->satuan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_supplier_masuk">Pilih Supplier <span class="text-danger">*</span></label>
                                <select class="form-control" id="id_supplier_masuk" name="id_supplier" required>
                                    <option value="" disabled selected>-- Pilih Supplier --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="jumlah_masuk">Jumlah Masuk <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="jumlah_masuk" name="jumlah" placeholder="Masukkan jumlah qty" required min="1">
                                    <small class="text-muted d-block mt-1" id="info-satuan-masuk"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="harga_masuk">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="harga_masuk" name="harga" placeholder="Contoh: 15000" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditMasuk" tabindex="-1" role="dialog" aria-labelledby="modalEditMasukLabel" aria-hidden="true">
            <div class="modal-dialog text-left" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-warning text-white border-0">
                        <h5 class="modal-title" id="modalEditMasukLabel"><i class="fas fa-pencil-alt mr-2 text-white"></i>Edit Transaksi Barang Masuk</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formEditMasuk" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Alert Peringatan edit masuk -->
                            <div class="alert alert-danger border-0 shadow-sm d-none" id="edit-masuk-warning-alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span id="edit-masuk-warning-msg">Pengurangan barang masuk ini akan membuat stok menjadi negatif!</span>
                            </div>

                            <div class="form-group">
                                <label for="edit_id_barang_masuk">Pilih Barang <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_id_barang_masuk" name="id_barang" required>
                                    @foreach($barangs as $b)
                                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">{{ $b->nama_barang }} (Stok Saat Ini: {{ $b->stok }} {{ $b->satuan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_id_supplier_masuk">Pilih Supplier <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_id_supplier_masuk" name="id_supplier" required>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="edit_jumlah_masuk">Jumlah Masuk <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_jumlah_masuk" name="jumlah" required min="1">
                                    <small class="text-muted d-block mt-1" id="edit-info-satuan-masuk"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="edit_harga_masuk">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_harga_masuk" name="harga" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_tanggal_masuk" name="tanggal_masuk" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-white" id="btnSubmitEditMasuk"><i class="fas fa-save mr-1"></i> Perbarui Transaksi</button>
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
            // ==================== 1. TAMBAH MASUK ====================
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

            // ==================== 2. EDIT MASUK ====================
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

                    formEditMasuk.setAttribute('action', `/barang-masuk/${id}`);
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
                    const minQty = origJumlahMasuk - currentStok;
                    if (qty < minQty) {
                        editMasukWarningMsg.textContent = `Gagal! Pengurangan jumlah barang masuk terlalu banyak. Stok akhir akan bernilai negatif jika jumlah kurang dari ${minQty} ${satuan}.`;
                        editMasukWarningAlert.classList.remove('d-none');
                        btnSubmitEditMasuk.disabled = true;
                    } else {
                        editMasukWarningAlert.classList.add('d-none');
                        btnSubmitEditMasuk.disabled = false;
                    }
                } else {
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
                            editMasukWarningMsg.textContent = `Gagal! Stok barang asal tidak mencukupi untuk dikurangi (${origStok} < ${origJumlahMasuk} ${satuan}). Hapus atau ubah transaksi lain terlebih dahulu.`;
                            editMasukWarningAlert.classList.remove('d-none');
                            btnSubmitEditMasuk.disabled = true;
                            return;
                        }
                    }

                    editMasukWarningAlert.classList.add('d-none');
                    btnSubmitEditMasuk.disabled = false;
                }
            }

            editIdBarangMasuk.addEventListener('change', validasiStokEditMasuk);
            editJumlahMasuk.addEventListener('input', validasiStokEditMasuk);
            @endif
        });
    </script>
@endsection
