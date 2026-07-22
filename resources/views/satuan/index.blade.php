@extends('adminlte::page')

@section('title', 'Kelola Satuan Barang - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-balance-scale mr-2"></i>Kelola Satuan Barang</h1>
            <p class="text-muted mb-0">Kelola daftar unit/satuan pengkuran bahan baku dapur</p>
        </div>
        @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Satuan</button>
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

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Daftar Satuan Barang</h3>
        </div>
        <div class="card-body p-0">
            @if($satuans->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-balance-scale fa-3x mb-3 text-secondary"></i>
                    <h5>Belum ada data satuan</h5>
                    <p>Silakan tambahkan satuan barang baru untuk mengelompokkan unit barang inventaris.</p>
                    @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
                        <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Satuan Pertama</button>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 8%">No</th>
                                <th style="width: 30%">Nama Satuan</th>
                                <th style="width: 45%">Keterangan</th>
                                @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
                                    <th style="width: 17%" class="text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($satuans as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge badge-info px-3 py-2 font-weight-bold" style="font-size: 14px;">
                                        {{ $s->nama_satuan }}
                                    </span>
                                </td>
                                <td>{{ $s->keterangan ?? '-' }}</td>
                                @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
                                <td class="text-right">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-warning btn-sm text-white btn-edit" 
                                                data-id="{{ $s->id_satuan }}"
                                                data-nama="{{ $s->nama_satuan }}"
                                                data-keterangan="{{ $s->keterangan }}"
                                                data-toggle="modal" 
                                                data-target="#modalEdit"
                                                title="Edit Satuan">
                                            <i class="fas fa-pencil-alt text-white"></i> Edit
                                        </button>
                                        <form action="{{ route('satuan.destroy', $s->id_satuan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus satuan {{ $s->nama_satuan }}?');" class="d-inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Satuan"><i class="fas fa-trash"></i> Hapus</button>
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
        @include('satuan.create')
        @include('satuan.edit')
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(strtolower(Auth::user()->role ?? '') !== 'kepala dapur')
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEditSatuan');
            const inputNama = document.getElementById('edit_nama_satuan');
            const inputKeterangan = document.getElementById('edit_keterangan');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const keterangan = this.getAttribute('data-keterangan');

                    formEdit.setAttribute('action', `/satuan/${id}`);
                    inputNama.value = nama;
                    inputKeterangan.value = keterangan ?? '';
                });
            });
            @endif
        });
    </script>
@endsection
