@extends('adminlte::page')

@section('title', 'Laporan Stok - StockFlow')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 26px;"><i class="fas fa-file-invoice mr-2"></i>Laporan Stok Barang</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Laporan mutasi stok dan nilai aset inventaris dapur berdasarkan periode</p>
        </div>
        <a href="{{ route('laporan.export', ['tanggal_awal' => $tglAwalFormatted, 'tanggal_akhir' => $tglAkhirFormatted]) }}" class="btn btn-success font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 6px;"><i class="fas fa-file-excel mr-1"></i> Unduh Excel</a>
    </div>
@endsection

@section('content')
    <!-- Form Filter Periode Laporan -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-4 form-group mb-md-0">
                    <label for="tanggal_awal" class="font-weight-bold text-secondary" style="font-size: 14px;">Dari Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tglAwalFormatted }}" required style="border-radius: 6px; font-size: 14px;">
                </div>
                <div class="col-md-4 form-group mb-md-0">
                    <label for="tanggal_akhir" class="font-weight-bold text-secondary" style="font-size: 14px;">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tglAkhirFormatted }}" required style="border-radius: 6px; font-size: 14px;">
                </div>
                <div class="col-md-4 d-flex mb-md-0">
                    <button type="submit" class="btn btn-primary font-weight-bold w-100" style="border-radius: 6px; font-size: 14px;"><i class="fas fa-sync-alt mr-1"></i> Tampilkan Laporan</button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-light border text-secondary w-100 ml-1 text-center font-weight-bold" style="border-radius: 6px; font-size: 14px;">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px; overflow: hidden;">
        <div class="card-body p-4 bg-white">
            <!-- Header Laporan SPPG -->
            <div class="text-center mb-4 border-bottom pb-3">
                <h2 class="font-weight-bold text-uppercase mb-1" style="color: #000000; font-size: 24px; letter-spacing: 1px; font-family: 'Arial', sans-serif;">SPPG PUNGGUR BESAR</h2>
                <h3 class="font-weight-bold mb-2" style="color: #000000; font-size: 20px; font-family: 'Arial', sans-serif;">LAPORAN STOCK BARANG (DETIL)</h3>
                <p class="text-dark mb-0 font-weight-bold" style="font-size: 15px;">
                    Periode : {{ \Carbon\Carbon::parse($tglAwalFormatted)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($tglAkhirFormatted)->format('d-m-Y') }}
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center" style="font-family: 'Arial', sans-serif; font-size: 14px; border: 1px solid #000000;">
                    <thead>
                        <tr style="background-color: #9bc2e6; color: #000000; font-weight: bold; font-size: 15px; border-bottom: 2px solid #000000;">
                            <th style="vertical-align: middle; width: 10%; border: 1px solid #000000; padding: 12px 8px;">Kode Brg</th>
                            <th style="vertical-align: middle; width: 28%; border: 1px solid #000000; padding: 12px 8px;" class="text-left">Nama Barang</th>
                            <th style="vertical-align: middle; width: 8%; border: 1px solid #000000; padding: 12px 8px;">Satuan</th>
                            <th style="vertical-align: middle; width: 9%; border: 1px solid #000000; padding: 12px 8px;">Saldo Awal</th>
                            <th style="vertical-align: middle; width: 9%; border: 1px solid #000000; padding: 12px 8px;">Masuk</th>
                            <th style="vertical-align: middle; width: 9%; border: 1px solid #000000; padding: 12px 8px;">Keluar</th>
                            <th style="vertical-align: middle; width: 9%; border: 1px solid #000000; padding: 12px 8px;">Saldo Akhir</th>
                            <th style="vertical-align: middle; width: 9%; border: 1px solid #000000; padding: 12px 8px;">Harga Beli Akhir</th>
                            <th style="vertical-align: middle; width: 9%; border: 1px solid #000000; padding: 12px 8px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalKeseluruhanRupiah = 0;
                        @endphp
                        @foreach($barangsGrouped as $kategoriUtama => $subKategoris)
                            @php
                                $katParts = explode(' | ', $kategoriUtama, 2);
                                $katKode = count($katParts) == 2 ? $katParts[0] : '';
                                $katNama = count($katParts) == 2 ? $katParts[1] : $kategoriUtama;
                            @endphp
                            <!-- Baris Kategori Utama (Contoh: KH | KARBOHIDRAT) -->
                            <tr style="background-color: #d9d9d9; font-weight: bold; font-size: 15px; color: #000000;">
                                <td class="text-left" style="padding: 10px; border: 1px solid #000000;">{{ $katKode }}</td>
                                <td class="text-left" style="padding: 10px; border: 1px solid #000000;">{{ strtoupper($katNama) }}</td>
                                <td style="border: 1px solid #000000;"></td>
                                <td style="border: 1px solid #000000;"></td>
                                <td style="border: 1px solid #000000;"></td>
                                <td style="border: 1px solid #000000;"></td>
                                <td style="border: 1px solid #000000;"></td>
                                <td style="border: 1px solid #000000;"></td>
                                <td style="border: 1px solid #000000;"></td>
                            </tr>

                            @foreach($subKategoris as $subKategori => $barangsInSub)
                                @if($subKategori !== 'TANPA SUB KATEGORI')
                                    @php
                                        $subParts = explode(' | ', $subKategori, 2);
                                        $subKode = count($subParts) == 2 ? $subParts[0] : '';
                                        $subNama = count($subParts) == 2 ? $subParts[1] : $subKategori;
                                    @endphp
                                    <!-- Baris Sub Kategori (Contoh: KH.01 | BERAS DAN OLAHAN PADI) -->
                                    <tr style="background-color: #e9e9e9; font-weight: bold; font-size: 14px; color: #000000;">
                                        <td class="text-left" style="padding: 10px; border: 1px solid #000000;">{{ $subKode }}</td>
                                        <td class="text-left" style="padding: 10px; border: 1px solid #000000;">{{ strtoupper($subNama) }}</td>
                                        <td style="border: 1px solid #000000;"></td>
                                        <td style="border: 1px solid #000000;"></td>
                                        <td style="border: 1px solid #000000;"></td>
                                        <td style="border: 1px solid #000000;"></td>
                                        <td style="border: 1px solid #000000;"></td>
                                        <td style="border: 1px solid #000000;"></td>
                                        <td style="border: 1px solid #000000;"></td>
                                    </tr>
                                @endif
                                
                                @foreach($barangsInSub as $b)
                                    @php
                                        $totalKeseluruhanRupiah += $b->jumlah_rupiah;
                                    @endphp
                                    <tr style="background-color: #ffffff; color: #000000; font-size: 14px;">
                                        <td class="text-left font-weight-normal" style="padding: 10px; border: 1px solid #000000;">
                                            {{ $b->kode_barang ?? '-' }}
                                        </td>
                                        <td class="text-left font-weight-normal" style="padding: 10px; border: 1px solid #000000;">{{ $b->nama_barang }}</td>
                                        <td style="padding: 10px; border: 1px solid #000000;">{{ $b->nama_satuan_text }}</td>
                                        <td style="padding: 10px; border: 1px solid #000000;">
                                            {{ $b->saldo_awal > 0 ? number_format($b->saldo_awal, 0, ',', '.') : '-' }}
                                        </td>
                                        <td style="padding: 10px; border: 1px solid #000000;">
                                            {{ $b->masuk > 0 ? number_format($b->masuk, 0, ',', '.') : '-' }}
                                        </td>
                                        <td style="padding: 10px; border: 1px solid #000000;">
                                            {{ $b->keluar > 0 ? number_format($b->keluar, 0, ',', '.') : '-' }}
                                        </td>
                                        <td style="padding: 10px; border: 1px solid #000000;">
                                            {{ $b->saldo_akhir > 0 ? number_format($b->saldo_akhir, 0, ',', '.') : '-' }}
                                        </td>
                                        <td style="padding: 10px; border: 1px solid #000000;">
                                            {{ $b->harga_beli_akhir > 0 ? 'Rp ' . number_format($b->harga_beli_akhir, 0, ',', '.') : '-' }}
                                        </td>
                                        <td style="padding: 10px; border: 1px solid #000000;">
                                            {{ $b->jumlah_rupiah > 0 ? 'Rp ' . number_format($b->jumlah_rupiah, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                        
                        <!-- Baris Total Keseluruhan -->
                        <tr style="background-color: #ffffff; color: #000000; font-weight: bold; font-size: 14px;">
                            <td style="padding: 10px; border: 1px solid #000000;">-</td>
                            <td style="padding: 10px; border: 1px solid #000000;">-</td>
                            <td style="padding: 10px; border: 1px solid #000000;">-</td>
                            <td style="padding: 10px; border: 1px solid #000000;">-</td>
                            <td style="padding: 10px; border: 1px solid #000000;">-</td>
                            <td style="padding: 10px; border: 1px solid #000000;">-</td>
                            <td style="padding: 10px; border: 1px solid #000000;">-</td>
                            <td style="padding: 10px; border: 1px solid #000000; background-color: #f2f2f2;">Jumlah Total</td>
                            <td style="padding: 10px; border: 1px solid #000000;">
                                {{ $totalKeseluruhanRupiah > 0 ? 'Rp ' . number_format($totalKeseluruhanRupiah, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
