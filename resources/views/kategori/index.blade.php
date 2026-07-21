@extends('adminlte::page')

@section('title', 'Kategori & Sub-Kategori - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 26px;"><i class="fas fa-tags mr-2"></i>Kategori &amp; Sub-Kategori</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Master data Kategori &amp; Sub-Kategori </p>
        </div>
        @if(Auth::user()->role !== 'kepala dapur')
            <div class="mt-md-0 mt-3 d-flex align-items-center">
                <button class="btn btn-outline-secondary font-weight-bold bg-white text-dark border mr-2 px-3 py-2" data-toggle="modal" data-target="#modalTambahKategori" style="border-radius: 8px; font-size: 14px;">
                    + Kategori Utama
                </button>
                <button class="btn btn-primary font-weight-bold px-3 py-2" data-toggle="modal" data-target="#modalTambahSubKategori" style="border-radius: 8px; font-size: 14px;">
                    + Sub-Kategori
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

    <!-- 1. TABEL KATEGORI -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-light border-0 py-3 px-4">
            <h3 class="card-title font-weight-bold text-uppercase text-secondary mb-0" style="font-size: 14px; letter-spacing: 0.5px;">Tabel KATEGORI</h3>
        </div>
        <div class="card-body p-0">
            @if($kategoris->isEmpty())
                <div class="text-center py-4 text-muted">
                    <p class="mb-0 small">Belum ada data Kategori Utama.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                            <tr>
                                <th style="width: 25%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="pl-4 py-3">KODE KATEGORI</th>
                                <th style="width: 50%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">NAMA KATEGORI</th>
                                @if(Auth::user()->role !== 'kepala dapur')
                                    <th style="width: 25%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="text-right pr-4 py-3">AKSI</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategoris as $k)
                            <tr>
                                <td class="pl-4">{{ $k->kode_kategori ?? '-' }}</td>
                                <td>{{ $k->nama_kategori }}</td>
                                @if(Auth::user()->role !== 'kepala dapur')
                                <td class="text-right pr-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-warning btn-sm text-white btn-edit-utama mr-1" 
                                                data-id="{{ $k->id_kategori }}"
                                                data-kode="{{ $k->kode_kategori }}"
                                                data-nama="{{ $k->nama_kategori }}"
                                                data-toggle="modal" 
                                                data-target="#modalEditKategoriUtama"
                                                title="Edit Kategori">
                                            <i class="fas fa-pencil-alt text-white"></i> Edit
                                        </button>
                                        <form action="{{ route('kategori.destroy', $k->id_kategori) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori utama ini?');" class="d-inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Kategori"><i class="fas fa-trash"></i> Hapus</button>
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

    <!-- 2. TABEL SUB_KATEGORI -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header border-0 py-3 px-4 bg-light">
            <h3 class="card-title font-weight-bold text-uppercase text-secondary mb-0" style="font-size: 14px; letter-spacing: 0.5px;">Tabel SUB_KATEGORI</h3>
        </div>
        <div class="card-body p-0">
            @if($subKategoris->isEmpty())
                <div class="text-center py-4 text-muted">
                    <p class="mb-0 small">Belum ada data Sub-Kategori.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                            <tr>
                                <th style="width: 25%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="pl-4 py-3">KODE SUB-KATEGORI</th>
                                <th style="width: 35%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">NAMA SUB-KATEGORI</th>
                                <th style="width: 25%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">KATEGORI INDUK (FK)</th>
                                @if(Auth::user()->role !== 'kepala dapur')
                                    <th style="width: 15%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="text-right pr-4 py-3">AKSI</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subKategoris as $sub)
                            <tr>
                                <td class="pl-4">{{ $sub->kode_subkategori ?? '-' }}</td>
                                <td>{{ $sub->nama_subkategori }}</td>
                                <td>{{ $sub->kategori ? $sub->kategori->nama_kategori : '-' }}</td>
                                @if(Auth::user()->role !== 'kepala dapur')
                                <td class="text-right pr-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-warning btn-sm text-white btn-edit-sub mr-1" 
                                                data-id="{{ $sub->id_subkategori }}"
                                                data-kategori="{{ $sub->id_kategori }}"
                                                data-kode="{{ $sub->kode_subkategori }}"
                                                data-nama="{{ $sub->nama_subkategori }}"
                                                data-toggle="modal" 
                                                data-target="#modalEditSubKategori"
                                                title="Edit Sub-Kategori">
                                            <i class="fas fa-pencil-alt text-white"></i> Edit
                                        </button>
                                        <form action="{{ route('sub-kategori.destroy', $sub->id_subkategori) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sub-kategori ini?');" class="d-inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Sub-Kategori"><i class="fas fa-trash"></i> Hapus</button>
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
        @include('kategori.create_utama')
        @include('kategori.create_sub')
        @include('kategori.edit_utama')
        @include('kategori.edit_sub')
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(Auth::user()->role !== 'kepala dapur')
            // Script Edit Kategori Utama
            const editUtamaButtons = document.querySelectorAll('.btn-edit-utama');
            const formEditUtama = document.getElementById('formEditKategoriUtama');
            const inputKodeUtama = document.getElementById('edit_kode_kategori');
            const inputNamaUtama = document.getElementById('edit_nama_kategori');

            editUtamaButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const kode = this.getAttribute('data-kode');
                    const nama = this.getAttribute('data-nama');

                    formEditUtama.setAttribute('action', `/kategori/${id}`);
                    inputKodeUtama.value = kode ?? '';
                    inputNamaUtama.value = nama ?? '';
                });
            });

            // Script Edit Sub-Kategori
            const editSubButtons = document.querySelectorAll('.btn-edit-sub');
            const formEditSub = document.getElementById('formEditSubKategori');
            const selectKategoriInduk = document.getElementById('edit_id_kategori_induk');
            const inputKodeSub = document.getElementById('edit_kode_subkategori');
            const inputNamaSub = document.getElementById('edit_nama_subkategori');

            editSubButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const idKategori = this.getAttribute('data-kategori');
                    const kode = this.getAttribute('data-kode');
                    const nama = this.getAttribute('data-nama');

                    formEditSub.setAttribute('action', `/sub-kategori/${id}`);
                    selectKategoriInduk.value = idKategori;
                    inputKodeSub.value = kode ?? '';
                    inputNamaSub.value = nama ?? '';
                });
            });
            @endif
        });
    </script>
@endsection
