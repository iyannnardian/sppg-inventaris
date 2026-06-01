@extends('adminlte::page')

@section('title', 'Kategori Barang - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-tags mr-2"></i>Kategori Barang</h1>
            <p class="text-muted mb-0">Kelola kategori untuk pengelompokan barang</p>
        </div>
        @if(Auth::user()->role !== 'kepala dapur')
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Kategori</button>
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

    <div class="row">
        @forelse($kategoris as $k)
        <div class="col-md-4">
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title text-truncate font-weight-bold" style="max-width: 70%;">{{ $k->nama_kategori }}</h5>
                        <span class="badge badge-primary py-2 px-3">{{ $k->barangs_count }} Items</span>
                    </div>
                    <p class="text-muted small mb-3">Dibuat pada {{ $k->created_at->format('d M Y') }}</p>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                        <button class="btn btn-outline-primary btn-sm btn-view-items" 
                                data-id="{{ $k->id_kategori }}" 
                                data-nama="{{ $k->nama_kategori }}"
                                data-barangs="{{ $k->barangs->toJson() }}"
                                data-toggle="modal" 
                                data-target="#modalViewBarang">
                            <i class="fas fa-eye mr-1"></i> Lihat Barang
                        </button>
                        
                        @if(Auth::user()->role !== 'kepala dapur')
                        <div class="d-flex gap-2">
                            <button class="btn btn-warning btn-sm text-white btn-edit" 
                                    data-id="{{ $k->id_kategori }}" 
                                    data-nama="{{ $k->nama_kategori }}"
                                    data-toggle="modal" 
                                    data-target="#modalEdit"
                                    title="Edit Kategori">
                                <i class="fas fa-pencil-alt text-white"></i>
                            </button>
                            <form action="{{ route('kategori.destroy', $k->id_kategori) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="d-inline ml-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Kategori"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="fas fa-tags fa-3x mb-3 text-secondary"></i>
            <p>Belum ada kategori yang ditambahkan.</p>
            @if(Auth::user()->role !== 'kepala dapur')
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Kategori Pertama</button>
            @endif
        </div>
        @endforelse
    </div>

    @include('kategori.show')

    @if(Auth::user()->role !== 'kepala dapur')
        @include('kategori.create')
        @include('kategori.edit')
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Logika untuk menampilkan daftar barang dalam Kategori dinamis
            const viewBarangButtons = document.querySelectorAll('.btn-view-items');
            const viewNamaKategori = document.getElementById('view_nama_kategori');
            const listBarangKategori = document.getElementById('list-barang-kategori');
            const tableKategoriBarang = document.getElementById('table-kategori-barang');
            const emptyBarangState = document.getElementById('empty-barang-state');

            viewBarangButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const nama = this.getAttribute('data-nama');
                    const barangsJson = this.getAttribute('data-barangs');
                    const barangs = JSON.parse(barangsJson) || [];

                    viewNamaKategori.textContent = nama;
                    listBarangKategori.innerHTML = '';

                    if (barangs.length === 0) {
                        tableKategoriBarang.classList.add('d-none');
                        emptyBarangState.classList.remove('d-none');
                    } else {
                        tableKategoriBarang.classList.remove('d-none');
                        emptyBarangState.classList.add('d-none');

                        barangs.forEach((b, index) => {
                            const row = document.createElement('tr');
                            const stokBadge = b.stok <= 5
                                ? `<span class="badge badge-danger py-2 px-3"><i class="fas fa-exclamation-triangle mr-1"></i> ${b.stok} ${b.satuan} (Kritis)</span>`
                                : `<span class="badge badge-success py-2 px-3"><i class="fas fa-check-circle mr-1"></i> ${b.stok} ${b.satuan}</span>`;

                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td class="font-weight-bold">${b.nama_barang}</td>
                                <td>${stokBadge}</td>
                            `;
                            listBarangKategori.appendChild(row);
                        });
                    }
                });
            });

            // Logika untuk mengisi data pada Modal Edit Kategori secara dinamis
            @if(Auth::user()->role !== 'kepala dapur')
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEditKategori');
            const inputNama = document.getElementById('edit_nama_kategori');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');

                    formEdit.setAttribute('action', `/kategori/${id}`);
                    inputNama.value = nama;
                });
            });
            @endif
        });
    </script>
@endsection
