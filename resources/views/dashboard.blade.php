@extends('adminlte::page')

@section('title', 'Dashboard - StockFlow')

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', 'Source Sans Pro', sans-serif !important;
            background-color: #f4f6f9 !important;
        }

        /* Header Card with sleek dark gradient */
        .dashboard-header-card {
            background: linear-gradient(135deg, #1d1b2e 0%, #2f253f 100%);
            border-radius: 16px;
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 24px;
            margin-bottom: 24px;
        }
        .dashboard-clock-pill {
            background-color: #14111d;
            border-radius: 12px;
            padding: 12px 24px;
            text-align: right;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
            min-width: 180px;
        }

        /* Stat Cards in Plum/Dark Violet background */
        .stat-card {
            background-color: #4a2c43 !important; /* Plum background color */
            border-radius: 16px;
            border: none;
            color: #ffffff !important;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .stat-card-label {
            color: #cda2c2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .stat-card-number {
            font-size: 42px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-card-subtext {
            color: #bfa6bb;
            font-size: 12px;
            font-weight: 500;
        }

        /* Chart Cards */
        .chart-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            background-color: #ffffff;
            padding: 24px;
            margin-bottom: 24px;
        }
        .chart-card-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e1e2d;
            margin-bottom: 4px;
        }
        .chart-card-subtitle {
            font-size: 13px;
            color: #7e8299;
            margin-bottom: 20px;
        }
        .chart-inner-placeholder {
            border: 2px dashed #dee2e6;
            background-color: #fafdff;
            border-radius: 12px;
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #7e8299;
            font-weight: 500;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    @php
        $totalBarang = \App\Models\Barang::count();
        $totalMasuk = \App\Models\BarangMasuk::count();
        $totalKeluar = \App\Models\BarangKeluar::count();
        
        // Format Indonesian Date
        $currentDate = \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY');
        $currentTime = \Carbon\Carbon::now('Asia/Jakarta')->format('H.i') . ' WIB';
    @endphp

    <!-- Header Card -->
    <div class="dashboard-header-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="font-weight-bold mb-1" style="font-size: 28px; letter-spacing: -0.5px;">Sistem Stok Barang</h2>
                <p class="mb-0 text-white-50" style="font-size: 15px;">Selamat datang {{ Auth::user()->name }}</p>
            </div>
            <div class="dashboard-clock-pill mt-md-0 mt-3">
                <div style="font-size: 11px; color: #a2a6be; font-weight: 500; text-transform: uppercase;">{{ $currentDate }}</div>
                <div class="font-weight-bold text-white mt-1" style="font-size: 20px; letter-spacing: 0.5px;">{{ $currentTime }}</div>
            </div>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-label">Total Barang</div>
                <div class="stat-card-number">{{ $totalBarang }}</div>
                <div class="stat-card-subtext">Item terdaftar</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-label">Barang Masuk</div>
                <div class="stat-card-number">{{ $totalMasuk }}</div>
                <div class="stat-card-subtext">Total transaksi masuk</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-label">Barang Keluar</div>
                <div class="stat-card-number">{{ $totalKeluar }}</div>
                <div class="stat-card-subtext">Total transaksi keluar</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="chart-card">
                <div class="chart-card-title">Grafik Stok Bulanan</div>
                <div class="chart-card-subtitle">Tren pergerakan barang masuk & keluar</div>
                <div class="chart-inner-placeholder">
                    <div class="mb-2" style="font-size: 32px; color: #a2a6be;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div><i class="fas fa-chart-area mr-1"></i> Area Grafik Garis Akan Ditampilkan Di Sini</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="chart-card">
                <div class="chart-card-title">Distribusi Kategori</div>
                <div class="chart-card-subtitle">Persentase per kategori</div>
                <div class="chart-inner-placeholder">
                    <div class="mb-2" style="font-size: 32px; color: #a2a6be;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div><i class="far fa-chart-bar mr-1"></i> Area Grafik Lingkaran Di Sini</div>
                </div>
            </div>
        </div>
    </div>
@endsection


