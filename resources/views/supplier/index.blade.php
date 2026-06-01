@extends('adminlte::page')

@section('title', 'Kelola Supplier - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-truck mr-2"></i>Kelola Supplier</h1>
            <p class="text-muted mb-0">Kelola daftar penyedia/supplier bahan baku dapur</p>
        </div>
        @if(Auth::user()->role !== 'kepala dapur')
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Supplier</button>
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

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Data Supplier</h3>
        </div>
        <div class="card-body p-0">
            @if($suppliers->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-truck fa-3x mb-3 text-secondary"></i>
                    <h5>Belum ada data supplier</h5>
                    <p>Silakan daftarkan supplier untuk mencatat transaksi barang masuk.</p>
                    @if(Auth::user()->role !== 'kepala dapur')
                        <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus-circle mr-1"></i> Tambah Supplier Pertama</button>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 35%">Nama Supplier</th>
                                <th style="width: 45%">Alamat</th>
                                @if(Auth::user()->role !== 'kepala dapur')
                                    <th style="width: 15%" class="text-right">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-weight-bold text-dark">{{ $s->nama_supplier }}</td>
                                <td>{{ $s->alamat ?? '-' }}</td>
                                @if(Auth::user()->role !== 'kepala dapur')
                                <td class="text-right">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-warning btn-sm text-white btn-edit" 
                                                data-id="{{ $s->id_supplier }}"
                                                data-nama="{{ $s->nama_supplier }}"
                                                data-alamat="{{ $s->alamat }}"
                                                data-toggle="modal" 
                                                data-target="#modalEdit"
                                                title="Edit Supplier">
                                            <i class="fas fa-pencil-alt text-white"></i> Edit
                                        </button>
                                        <form action="{{ route('supplier.destroy', $s->id_supplier) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');" class="d-inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Supplier"><i class="fas fa-trash"></i> Hapus</button>
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
        @include('supplier.create')
        @include('supplier.edit')
    @endif
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(Auth::user()->role !== 'kepala dapur')
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEditSupplier');
            const inputNama = document.getElementById('edit_nama_supplier');
            const inputAlamat = document.getElementById('edit_alamat');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const alamat = this.getAttribute('data-alamat');

                    formEdit.setAttribute('action', `/supplier/${id}`);
                    inputNama.value = nama;
                    inputAlamat.value = alamat ?? '';
                });
            });
            @endif
        });
    </script>
@endsection
