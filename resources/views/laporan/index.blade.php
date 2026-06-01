@extends('adminlte::page')

@section('title', 'Laporan Stok - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-file-invoice mr-2"></i>Laporan Stok Barang</h1>
            <p class="text-muted mb-0">Laporan mutasi stok dan nilai aset inventaris dapur berdasarkan periode</p>
        </div>
        <a href="{{ route('laporan.export', ['tanggal_awal' => $tglAwalFormatted, 'tanggal_akhir' => $tglAkhirFormatted]) }}" class="btn btn-success shadow-sm"><i class="fas fa-file-excel mr-1"></i> Unduh Excel</a>
    </div>
@endsection

@section('content')
    <!-- Form Filter Periode Laporan -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-4 form-group mb-md-0">
                    <label for="tanggal_awal" class="small font-weight-bold">Dari Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tglAwalFormatted }}" required>
                </div>
                <div class="col-md-4 form-group mb-md-0">
                    <label for="tanggal_akhir" class="small font-weight-bold">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tglAkhirFormatted }}" required>
                </div>
                <div class="col-md-4 d-flex mb-md-0">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sync-alt mr-1"></i> Tampilkan Laporan</button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-default border w-100 ml-1 text-center">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body p-4">
            <!-- Header Laporan SPPG -->
            <div class="text-center mb-4 border-bottom pb-3">
                <h3 class="font-weight-bold text-uppercase" style="color: #1f4e79; letter-spacing: 1px; font-family: 'Arial', sans-serif;">SPPG PUNGGUR BESAR</h3>
                <h4 class="font-weight-bold" style="color: #333333; font-family: 'Arial', sans-serif; font-size: 18px;">LAPORAN STOCK BARANG (DETIL)</h4>
                <p class="text-muted mb-0 font-weight-bold">
                    Periode : {{ \Carbon\Carbon::parse($tglAwalFormatted)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($tglAkhirFormatted)->format('d-m-Y') }}
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center" style="font-family: 'Arial', sans-serif; font-size: 14px; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #4f81bd; color: #ffffff; font-weight: bold;">
                            <th style="vertical-align: middle; width: 35%; border: 1px solid #dee2e6;">Nama Barang</th>
                            <th style="vertical-align: middle; width: 10%; border: 1px solid #dee2e6;">Satuan</th>
                            <th style="vertical-align: middle; width: 10%; border: 1px solid #dee2e6;">Saldo Awal</th>
                            <th style="vertical-align: middle; width: 10%; border: 1px solid #dee2e6;">Masuk</th>
                            <th style="vertical-align: middle; width: 10%; border: 1px solid #dee2e6;">Keluar</th>
                            <th style="vertical-align: middle; width: 10%; border: 1px solid #dee2e6;">Saldo Akhir</th>
                            <th style="vertical-align: middle; width: 15%; border: 1px solid #dee2e6;">Harga Beli Akhir</th>
                            <th style="vertical-align: middle; width: 15%; border: 1px solid #dee2e6;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangsGrouped as $categoryName => $group)
                            <!-- Baris Kategori -->
                            <tr style="background-color: #dce6f1; font-weight: bold; color: #1f4e79;">
                                <td colspan="8" class="text-left" style="padding: 10px; font-size: 14px; border: 1px solid #dee2e6;">
                                    ▶ {{ strtoupper($categoryName) }}
                                </td>
                            </tr>
                            @foreach($group as $b)
                                <tr>
                                    <td class="text-left font-weight-normal" style="padding: 10px; color: #333; border: 1px solid #dee2e6;">{{ $b->nama_barang }}</td>
                                    <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $b->satuan }}</td>
                                    <td style="padding: 10px; color: #555; border: 1px solid #dee2e6;">
                                        {{ $b->saldo_awal > 0 ? number_format($b->saldo_awal, 0, '.', ',') : '-' }}
                                    </td>
                                    <td class="text-success font-weight-bold" style="padding: 10px; border: 1px solid #dee2e6;">
                                        {{ $b->masuk > 0 ? number_format($b->masuk, 0, '.', ',') : '-' }}
                                    </td>
                                    <td class="text-danger font-weight-bold" style="padding: 10px; border: 1px solid #dee2e6;">
                                        {{ $b->keluar > 0 ? number_format($b->keluar, 0, '.', ',') : '-' }}
                                    </td>
                                    <td class="text-primary font-weight-bold" style="padding: 10px; border: 1px solid #dee2e6;">
                                        {{ $b->saldo_akhir > 0 ? number_format($b->saldo_akhir, 0, '.', ',') : '-' }}
                                    </td>
                                    <td style="padding: 10px; border: 1px solid #dee2e6; color: #333;">
                                        {{ $b->harga_beli_akhir > 0 ? 'Rp ' . number_format($b->harga_beli_akhir, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="font-weight-bold text-dark" style="padding: 10px; border: 1px solid #dee2e6;">
                                        {{ $b->jumlah_rupiah > 0 ? 'Rp ' . number_format($b->jumlah_rupiah, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
