<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = $request->filled('tanggal_awal') 
            ? Carbon::parse($request->tanggal_awal)->startOfDay() 
            : Carbon::now()->startOfMonth()->startOfDay();

        $tanggalAkhir = $request->filled('tanggal_akhir') 
            ? Carbon::parse($request->tanggal_akhir)->endOfDay() 
            : Carbon::now()->endOfDay();

        $barangs = Barang::with('kategori')->get()->map(function ($barang) use ($tanggalAwal, $tanggalAkhir) {
            $masukSebelum = $barang->barangMasuks()
                ->where('tanggal_masuk', '<', $tanggalAwal->format('Y-m-d'))
                ->sum('jumlah');

            $keluarSebelum = $barang->barangKeluars()
                ->where('tanggal_keluar', '<', $tanggalAwal->format('Y-m-d'))
                ->sum('jumlah');

            $barang->saldo_awal = $barang->stok_awal + $masukSebelum - $keluarSebelum;

            $barang->masuk = $barang->barangMasuks()
                ->whereBetween('tanggal_masuk', [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')])
                ->sum('jumlah');

            $barang->keluar = $barang->barangKeluars()
                ->whereBetween('tanggal_keluar', [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')])
                ->sum('jumlah');

            $barang->saldo_akhir = $barang->saldo_awal + $barang->masuk - $barang->keluar;

            $masuksUpToDate = $barang->barangMasuks()
                ->where('tanggal_masuk', '<=', $tanggalAkhir->format('Y-m-d'))
                ->get();

            $totalMasukQty = $masuksUpToDate->sum('jumlah');
            if ($totalMasukQty > 0) {
                $totalMasukValue = $masuksUpToDate->sum(function ($item) {
                    return $item->jumlah * $item->harga;
                });
                $hargaRataRata = $totalMasukValue / $totalMasukQty;
            } else {
                $hargaRataRata = 0;
            }

            $barang->harga_beli_akhir = $hargaRataRata;
            $barang->jumlah_rupiah = ($hargaRataRata > 0 && $barang->saldo_akhir > 0)
                ? $barang->saldo_akhir * $hargaRataRata
                : 0;

            return $barang;
        });

        $barangsGrouped = $barangs->groupBy(function ($barang) {
            return $barang->kategori->nama_kategori ?? 'BAHAN BAKU LAINNYA';
        });

        $tglAwalFormatted = $tanggalAwal->format('Y-m-d');
        $tglAkhirFormatted = $tanggalAkhir->format('Y-m-d');

        return view('laporan.index', compact('barangsGrouped', 'tglAwalFormatted', 'tglAkhirFormatted'));
    }

    public function exportExcel(Request $request)
    {
        $tanggalAwal = $request->filled('tanggal_awal') 
            ? Carbon::parse($request->tanggal_awal)->startOfDay() 
            : Carbon::now()->startOfMonth()->startOfDay();

        $tanggalAkhir = $request->filled('tanggal_akhir') 
            ? Carbon::parse($request->tanggal_akhir)->endOfDay() 
            : Carbon::now()->endOfDay();

        $barangs = Barang::with('kategori')->get()->map(function ($barang) use ($tanggalAwal, $tanggalAkhir) {
            $masukSebelum = $barang->barangMasuks()
                ->where('tanggal_masuk', '<', $tanggalAwal->format('Y-m-d'))
                ->sum('jumlah');

            $keluarSebelum = $barang->barangKeluars()
                ->where('tanggal_keluar', '<', $tanggalAwal->format('Y-m-d'))
                ->sum('jumlah');

            $barang->saldo_awal = $barang->stok_awal + $masukSebelum - $keluarSebelum;

            $barang->masuk = $barang->barangMasuks()
                ->whereBetween('tanggal_masuk', [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')])
                ->sum('jumlah');

            $barang->keluar = $barang->barangKeluars()
                ->whereBetween('tanggal_keluar', [$tanggalAwal->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')])
                ->sum('jumlah');

            $barang->saldo_akhir = $barang->saldo_awal + $barang->masuk - $barang->keluar;

            $masuksUpToDate = $barang->barangMasuks()
                ->where('tanggal_masuk', '<=', $tanggalAkhir->format('Y-m-d'))
                ->get();

            $totalMasukQty = $masuksUpToDate->sum('jumlah');
            if ($totalMasukQty > 0) {
                $totalMasukValue = $masuksUpToDate->sum(function ($item) {
                    return $item->jumlah * $item->harga;
                });
                $hargaRataRata = $totalMasukValue / $totalMasukQty;
            } else {
                $hargaRataRata = 0;
            }

            $barang->harga_beli_akhir = $hargaRataRata;
            $barang->jumlah_rupiah = ($hargaRataRata > 0 && $barang->saldo_akhir > 0)
                ? $barang->saldo_akhir * $hargaRataRata
                : 0;

            return $barang;
        });

        $barangsGrouped = $barangs->groupBy(function ($barang) {
            return $barang->kategori->nama_kategori ?? 'BAHAN BAKU LAINNYA';
        });

        $tglAwalFormatted = $tanggalAwal->format('d-m-Y');
        $tglAkhirFormatted = $tanggalAkhir->format('d-m-Y');
        $filename = 'Laporan_Stok_Barang_' . $tanggalAwal->format('Ymd') . '_to_' . $tanggalAkhir->format('Ymd') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<table border="1" style="border-collapse: collapse;">';
        echo '<tr><th colspan="8" style="font-size: 16px; font-weight: bold; text-align: center; color: #1f4e79; font-family: Arial; border: none;">SPPG PUNGGUR BESAR</th></tr>';
        echo '<tr><th colspan="8" style="font-size: 14px; font-weight: bold; text-align: center; font-family: Arial; border: none;">LAPORAN STOCK BARANG (DETIL)</th></tr>';
        echo '<tr><th colspan="8" style="font-size: 11px; text-align: center; font-family: Arial; font-weight: normal; border: none;">Periode : ' . $tglAwalFormatted . ' s.d. ' . $tglAkhirFormatted . '</th></tr>';
        echo '<tr><td colspan="8" style="border: none;"></td></tr>';
        echo '<tr style="color: #ffffff; font-weight: bold; font-family: Arial; text-align: center;">';
        echo '<th style="padding: 10px; width: 250px; background-color: #4f81bd;">Nama Barang</th>';
        echo '<th style="padding: 10px; width: 80px; background-color: #4f81bd;">Satuan</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #4f81bd;">Saldo Awal</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #4f81bd;">Masuk</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #4f81bd;">Keluar</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #4f81bd;">Saldo Akhir</th>';
        echo '<th style="padding: 10px; width: 130px; background-color: #4f81bd;">Harga Beli Akhir</th>';
        echo '<th style="padding: 10px; width: 160px; background-color: #4f81bd;">Jumlah</th>';
        echo '</tr>';

        foreach ($barangsGrouped as $categoryName => $group) {
            echo '<tr style="font-weight: bold; color: #1f4e79; font-family: Arial; font-size: 11px;">';
            echo '<td colspan="8" style="text-align: left; padding: 6px; background-color: #dce6f1;">▶ ' . strtoupper($categoryName) . '</td>';
            echo '</tr>';

            foreach ($group as $b) {
                $saldoAwalVal = $b->saldo_awal > 0 ? number_format($b->saldo_awal, 0, '.', ',') : '-';
                $masukVal = $b->masuk > 0 ? number_format($b->masuk, 0, '.', ',') : '-';
                $keluarVal = $b->keluar > 0 ? number_format($b->keluar, 0, '.', ',') : '-';
                $saldoAkhirVal = $b->saldo_akhir > 0 ? number_format($b->saldo_akhir, 0, '.', ',') : '-';
                $hargaBeliAkhirVal = $b->harga_beli_akhir > 0 ? 'Rp ' . number_format($b->harga_beli_akhir, 0, ',', '.') : '-';
                $jumlahRupiahVal = $b->jumlah_rupiah > 0 ? 'Rp ' . number_format($b->jumlah_rupiah, 0, ',', '.') : '-';

                echo '<tr style="font-family: Arial; font-size: 11px;">';
                echo '<td style="text-align: left; padding: 6px;">' . htmlspecialchars($b->nama_barang) . '</td>';
                echo '<td style="text-align: center; padding: 6px;">' . htmlspecialchars($b->satuan) . '</td>';
                echo '<td style="text-align: center; padding: 6px; color: #555555;">' . $saldoAwalVal . '</td>';
                echo '<td style="text-align: center; padding: 6px; color: #2e7d32; font-weight: bold;">' . $masukVal . '</td>';
                echo '<td style="text-align: center; padding: 6px; color: #c62828; font-weight: bold;">' . $keluarVal . '</td>';
                echo '<td style="text-align: center; padding: 6px; color: #1565c0; font-weight: bold;">' . $saldoAkhirVal . '</td>';
                echo '<td style="text-align: center; padding: 6px;">' . $hargaBeliAkhirVal . '</td>';
                echo '<td style="text-align: center; padding: 6px; font-weight: bold;">' . $jumlahRupiahVal . '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
        exit;
    }
}
