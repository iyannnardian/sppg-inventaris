@extends('adminlte::page')

@section('title', 'Dashboard - StockFlow')

@section('content_header')
    @php
        $currentDate = \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY');
        $currentTime = \Carbon\Carbon::now('Asia/Jakarta')->format('H.i') . ' WIB';
    @endphp
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 26px;">
                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
            </h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Ringkasan kondisi gudang &amp; transaksi berjalan</p>
        </div>
        <div class="mt-md-0 mt-2 text-muted small font-weight-bold">
            <i class="far fa-calendar-alt mr-1"></i> {{ $currentDate }} | <i class="far fa-clock mr-1"></i> {{ $currentTime }}
        </div>
    </div>
@endsection

@section('content')
    @php
        $totalBarang = \App\Models\Barang::count();
        $kategoriBarang = \App\Models\Kategori::count();
        $totalSupplier = \App\Models\Supplier::count();

        $bulanIni = \Carbon\Carbon::now('Asia/Jakarta')->month;
        $tahunIni = \Carbon\Carbon::now('Asia/Jakarta')->year;

        $pembelianBulanIni = \App\Models\Pembelian::whereMonth('tgl_faktur', $bulanIni)
            ->whereYear('tgl_faktur', $tahunIni)
            ->count();

        $pengeluaranBulanIni = \App\Models\Pengeluaran::whereMonth('tgl_pengeluaran', $bulanIni)
            ->whereYear('tgl_pengeluaran', $tahunIni)
            ->count();

        $allBarang = \App\Models\Barang::with(['satuan', 'subKategori.kategori'])->get();

        $stokMinimumItems = $allBarang->filter(function($b) {
            return $b->stok <= $b->stok_minimum;
        });

        $stokDibawahMinimum = $stokMinimumItems->count();
    @endphp

    <!-- Stat Cards Row (Warna AdminLTE Small-Box) -->
    <div class="row">
        <!-- Total Barang Aktif -->
        <div class="col-md-4 mb-4">
            <div class="small-box bg-info shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="inner p-3">
                    <h3 class="font-weight-bold mb-1">{{ $totalBarang }}</h3>
                    <p class="font-weight-bold text-uppercase mb-0" style="font-size: 12px; letter-spacing: 0.5px;">TOTAL BARANG TERDAFTAR</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>

        <!-- Kategori Barang -->
        <div class="col-md-4 mb-4">
            <div class="small-box bg-purple shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="inner p-3">
                    <h3 class="font-weight-bold mb-1">{{ $kategoriBarang }}</h3>
                    <p class="font-weight-bold text-uppercase mb-0" style="font-size: 12px; letter-spacing: 0.5px;">KATEGORI BARANG</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>

        <!-- Supplier -->
        <div class="col-md-4 mb-4">
            <div class="small-box bg-teal shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="inner p-3">
                    <h3 class="font-weight-bold mb-1">{{ $totalSupplier }}</h3>
                    <p class="font-weight-bold text-uppercase mb-0" style="font-size: 12px; letter-spacing: 0.5px;">SUPPLIER</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
        </div>

        <!-- Pembelian Bulan Ini -->
        <div class="col-md-4 mb-4">
            <div class="small-box bg-success shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="inner p-3">
                    <h3 class="font-weight-bold mb-1">{{ $pembelianBulanIni }}</h3>
                    <p class="font-weight-bold text-uppercase mb-0" style="font-size: 12px; letter-spacing: 0.5px;">PEMBELIAN BULAN INI</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="col-md-4 mb-4">
            <div class="small-box bg-warning shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="inner p-3">
                    <h3 class="font-weight-bold mb-1">{{ $pengeluaranBulanIni }}</h3>
                    <p class="font-weight-bold text-uppercase mb-0" style="font-size: 12px; letter-spacing: 0.5px;">PENGELUARAN BULAN INI</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dolly"></i>
                </div>
            </div>
        </div>

        <!-- Stok di Bawah Minimum -->
        <div class="col-md-4 mb-4">
            <div class="small-box bg-danger shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="inner p-3">
                    <h3 class="font-weight-bold mb-1">{{ $stokDibawahMinimum }}</h3>
                    <p class="font-weight-bold text-uppercase mb-0" style="font-size: 12px; letter-spacing: 0.5px;">STOK DI BAWAH MINIMUM</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Peringatan Stok Minimum Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 18px;">Peringatan Stok Minimum</h3>
        </div>
        <div class="card-body p-0">
            @if($stokMinimumItems->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <p class="mb-0">Semua stok barang dalam kondisi aman (di atas stok minimum).</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #fafafa; border-bottom: 1px solid #f0f0f0;">
                            <tr>
                                <th style="width: 15%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="pl-4 py-3">KODE</th>
                                <th style="width: 30%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">NAMA BARANG</th>
                                <th style="width: 20%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">KATEGORI</th>
                                <th style="width: 12%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">STOK</th>
                                <th style="width: 12%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3">MIN.</th>
                                <th style="width: 11%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8c8c;" class="py-3 pr-4">SATUAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stokMinimumItems as $b)
                            <tr>
                                <td class="pl-4">{{ $b->kode_barang ?? '-' }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>
                                    @if($b->subKategori)
                                        {{ $b->subKategori->kategori ? $b->subKategori->kategori->nama_kategori : $b->subKategori->nama_subkategori }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="font-weight-bold text-danger">{{ number_format($b->stok, 0, ',', '.') }}</td>
                                <td>{{ number_format($b->stok_minimum, 0, ',', '.') }}</td>
                                <td class="pr-4">{{ $b->satuan->nama_satuan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
