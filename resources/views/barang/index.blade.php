@extends('adminlte::page')

@section('title', 'Barang - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 26px;"><i class="fas fa-box-open mr-2"></i>Barang</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Master barang &amp; stok real-time (pembelian diterima – pengeluaran)</p>
        </div>
        @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
            <div class="mt-md-0 mt-3">
                <button class="btn btn-primary font-weight-bold px-3 py-2 shadow-sm" data-toggle="modal" data-target="#modalTambah" style="border-radius: 8px; font-size: 14px;">
                    + Tambah Barang
                </button>
            </div>
        @endif
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert" style="border-radius: 8px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert" style="border-radius: 8px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 mb-3 shadow-sm" role="alert" style="border-radius: 8px;">
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

    <!-- Filter Bar -->
    <div class="d-flex align-items-center mb-4">
        <label for="filter_subkategori" class="font-weight-bold text-secondary mr-3 mb-0" style="font-size: 14px;">Tampilkan Kategori:</label>
        <form action="{{ route('barang.index') }}" method="GET" class="form-inline">
            <select class="form-control bg-white border-0 shadow-sm px-3" id="filter_subkategori" name="id_subkategori" onchange="this.form.submit()" style="border-radius: 8px; font-size: 14px; min-width: 250px;">
                <option value="all" {{ request('id_subkategori') == 'all' || !request('id_subkategori') ? 'selected' : '' }}>— Semua Barang —</option>
                @foreach($subKategoris as $sub)
                    <option value="{{ $sub->id_subkategori }}" {{ request('id_subkategori') == $sub->id_subkategori ? 'selected' : '' }}>
                        {{ $sub->kategori ? $sub->kategori->nama_kategori . ' / ' : '' }}{{ $sub->nama_subkategori }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Tabel Data Barang -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            @if($barangs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p class="mb-0">Belum ada data barang.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                            <tr>
                                <th style="width: 15%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="pl-4 py-3">KODE</th>
                                <th style="width: 30%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">NAMA BARANG</th>
                                <th style="width: 30%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">KATEGORI</th>
                                <th style="width: 15%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">SATUAN</th>
                                <th style="width: 10%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">STOK</th>
                                @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
                                    <th style="width: 10%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="text-right pr-4 py-3">AKSI</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangs as $b)
                            <tr>
                                <td class="pl-4">{{ $b->kode_barang ?? '-' }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>
                                    @if($b->subKategori)
                                        {{ $b->subKategori->kategori ? $b->subKategori->kategori->nama_kategori . ' / ' : '' }}{{ $b->subKategori->nama_subkategori }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $b->satuan ? $b->satuan->nama_satuan : '-' }}
                                </td>
                                <td>
                                    {{ number_format($b->stok, 0, ',', '.') }}
                                </td>
                                @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
                                <td class="text-right pr-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-warning btn-sm text-white btn-edit mr-1" 
                                                data-id="{{ $b->id_barang }}"
                                                data-kode="{{ $b->kode_barang }}"
                                                data-nama="{{ $b->nama_barang }}"
                                                data-subkategori="{{ $b->id_subkategori }}"
                                                data-satuan="{{ $b->id_satuan }}"
                                                data-stok_minimum="{{ $b->stok_minimum }}"
                                                data-toggle="modal" 
                                                data-target="#modalEdit"
                                                title="Edit Barang">
                                            <i class="fas fa-pencil-alt text-white"></i> Edit
                                        </button>
                                        <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');" class="d-inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Barang"><i class="fas fa-trash"></i> Hapus</button>
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

    @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
        @include('barang.create')
        @include('barang.edit')
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
            // Auto-fill Prefix Kode Barang saat pilih Sub-Kategori pada modal Tambah Barang
            const selectCreateSubKategori = document.getElementById('id_subkategori');
            const inputCreateKode = document.getElementById('kode_barang');

            if (selectCreateSubKategori && inputCreateKode) {
                selectCreateSubKategori.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const kodeSub = selectedOption ? selectedOption.getAttribute('data-kode') : null;
                    if (kodeSub && kodeSub.trim() !== '') {
                        const prefix = kodeSub.trim() + '.';
                        inputCreateKode.value = prefix;
                        inputCreateKode.focus();
                    }
                });
            }

            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEditBarang');
            const inputKode = document.getElementById('edit_kode_barang');
            const inputNama = document.getElementById('edit_nama_barang');
            const selectSubKategori = document.getElementById('edit_id_subkategori');
            const selectSatuan = document.getElementById('edit_id_satuan');
            const inputStokMinimum = document.getElementById('edit_stok_minimum');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const kode = this.getAttribute('data-kode');
                    const nama = this.getAttribute('data-nama');
                    const subKategori = this.getAttribute('data-subkategori');
                    const satuan = this.getAttribute('data-satuan');
                    const stokMin = this.getAttribute('data-stok_minimum');

                    formEdit.setAttribute('action', `/barang/${id}`);
                    inputKode.value = kode ?? '';
                    inputNama.value = nama ?? '';
                    selectSubKategori.value = subKategori;
                    selectSatuan.value = satuan;
                    inputStokMinimum.value = stokMin ?? 0;
                });
            });
            @endif
        });
    </script>
@endsection
