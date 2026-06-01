@extends('adminlte::page')

@section('title', 'Daftar Barang - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-box mr-2"></i>Daftar Barang</h1>
            <p class="text-muted mb-0">Kelola daftar seluruh inventaris bahan baku dapur</p>
        </div>
        @if(Auth::user()->role !== 'kepala dapur')
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Barang</button>
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

    <!-- Form Pencarian & Filter -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('barang.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-5 form-group mb-md-0">
                    <label for="search" class="small font-weight-bold">Cari Nama Barang</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Ketik nama barang..." value="{{ request('search') }}" autocomplete="off">
                </div>
                <div class="col-md-5 form-group mb-md-0">
                    <label for="id_kategori" class="small font-weight-bold">Filter Kategori</label>
                    <select class="form-control" id="id_kategori" name="id_kategori">
                        <option value="all">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id_kategori }}" {{ request('id_kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex mb-md-0">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter mr-1"></i> Filter</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-default border w-100 ml-1 text-center">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Barang -->
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Data Inventaris</h3>
        </div>
        <div class="card-body p-0">
            @if($barangs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                    <h5>Tidak ada data barang</h5>
                    <p>Belum ada barang yang ditemukan atau ditambahkan ke sistem.</p>
                    @if(Auth::user()->role !== 'kepala dapur')
                        <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Barang Pertama</button>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Nama Barang</th>
                                <th style="width: 20%">Kategori</th>
                                <th style="width: 15%">Satuan</th>
                                <th style="width: 15%">Stok Saat Ini</th>
                                @if(Auth::user()->role !== 'kepala dapur')
                                    <th style="width: 20%" class="text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangs as $index => $b)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-weight-bold text-dark">{{ $b->nama_barang }}</td>
                                <td>
                                    <span class="badge badge-secondary py-1 px-2 font-weight-normal">{{ $b->kategori->nama_kategori ?? 'Tanpa Kategori' }}</span>
                                </td>
                                <td>{{ $b->satuan }}</td>
                                <td>
                                    @if($b->stok <= 5)
                                        <span class="badge badge-danger py-2 px-3"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $b->stok }} (Kritis)</span>
                                    @else
                                        <span class="badge badge-success py-2 px-3"><i class="fas fa-check-circle mr-1"></i> {{ $b->stok }}</span>
                                    @endif
                                </td>
                                @if(Auth::user()->role !== 'kepala dapur')
                                <td class="text-right">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-warning btn-sm text-white btn-edit" 
                                                data-id="{{ $b->id_barang }}"
                                                data-nama="{{ $b->nama_barang }}"
                                                data-satuan="{{ $b->satuan }}"
                                                data-kategori="{{ $b->id_kategori }}"
                                                data-stok_awal="{{ $b->stok_awal }}"
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

    @if(Auth::user()->role !== 'kepala dapur')
        @include('barang.create')
        @include('barang.edit')
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(Auth::user()->role !== 'kepala dapur')
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEditBarang');
            const inputNama = document.getElementById('edit_nama_barang');
            const selectKategori = document.getElementById('edit_id_kategori');
            const selectSatuan = document.getElementById('edit_satuan');
            const inputStokAwal = document.getElementById('edit_stok_awal');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const satuan = this.getAttribute('data-satuan');
                    const kategori = this.getAttribute('data-kategori');
                    const stokAwal = this.getAttribute('data-stok_awal');

                    formEdit.setAttribute('action', `/barang/${id}`);
                    inputNama.value = nama;
                    selectKategori.value = kategori;
                    selectSatuan.value = satuan;
                    inputStokAwal.value = stokAwal;
                });
            });
            @endif
        });
    </script>
@endsection
