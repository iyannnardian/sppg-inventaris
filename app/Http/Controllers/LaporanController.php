<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\PembelianDetail;
use App\Models\PengeluaranDetail;
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

        $barangsGrouped = $this->getReportData($tanggalAwal, $tanggalAkhir);

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

        $barangsGrouped = $this->getReportData($tanggalAwal, $tanggalAkhir);

        $tglAwalFormatted = $tanggalAwal->format('d-m-Y');
        $tglAkhirFormatted = $tanggalAkhir->format('d-m-Y');
        $filename = 'Laporan_Stok_Barang_' . $tanggalAwal->format('Ymd') . '_to_' . $tanggalAkhir->format('Ymd') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<table border="1" style="border-collapse: collapse;">';
        echo '<tr><th colspan="9" style="font-size: 20px; font-weight: bold; text-align: center; color: #000000; font-family: Arial; border: none;">SPPG PUNGGUR BESAR</th></tr>';
        echo '<tr><th colspan="9" style="font-size: 16px; font-weight: bold; text-align: center; color: #000000; font-family: Arial; border: none;">LAPORAN STOCK BARANG (DETIL)</th></tr>';
        echo '<tr><th colspan="9" style="font-size: 13px; text-align: center; font-family: Arial; font-weight: bold; border: none;">Periode : ' . $tglAwalFormatted . ' s.d. ' . $tglAkhirFormatted . '</th></tr>';
        echo '<tr><td colspan="9" style="border: none;"></td></tr>';
        echo '<tr style="color: #000000; font-weight: bold; font-family: Arial; text-align: center; font-size: 13px;">';
        echo '<th style="padding: 10px; width: 100px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Kode Brg</th>';
        echo '<th style="padding: 10px; width: 250px; background-color: #9bc2e6; border: 1px solid #000000; text-align: left; font-size: 13px;">Nama Barang</th>';
        echo '<th style="padding: 10px; width: 80px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Satuan</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Saldo Awal</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Masuk</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Keluar</th>';
        echo '<th style="padding: 10px; width: 100px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Saldo Akhir</th>';
        echo '<th style="padding: 10px; width: 130px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Harga Beli Akhir</th>';
        echo '<th style="padding: 10px; width: 160px; background-color: #9bc2e6; border: 1px solid #000000; font-size: 13px;">Jumlah</th>';
        echo '</tr>';

        foreach ($barangsGrouped as $kategoriUtama => $subKategoris) {
            $katParts = explode(' | ', $kategoriUtama, 2);
            $katKode = count($katParts) == 2 ? $katParts[0] : '';
            $katNama = count($katParts) == 2 ? $katParts[1] : $kategoriUtama;

            echo '<tr style="font-weight: bold; color: #000000; font-family: Arial; font-size: 11px;">';
            echo '<td style="text-align: left; padding: 6px; border: 1px solid #000000; background-color: #d9d9d9;">' . htmlspecialchars($katKode) . '</td>';
            echo '<td style="text-align: left; padding: 6px; border: 1px solid #000000; background-color: #d9d9d9;">' . htmlspecialchars(strtoupper($katNama)) . '</td>';
            echo '<td style="border: 1px solid #000000; background-color: #d9d9d9;"></td>';
            echo '<td style="border: 1px solid #000000; background-color: #d9d9d9;"></td>';
            echo '<td style="border: 1px solid #000000; background-color: #d9d9d9;"></td>';
            echo '<td style="border: 1px solid #000000; background-color: #d9d9d9;"></td>';
            echo '<td style="border: 1px solid #000000; background-color: #d9d9d9;"></td>';
            echo '<td style="border: 1px solid #000000; background-color: #d9d9d9;"></td>';
            echo '<td style="border: 1px solid #000000; background-color: #d9d9d9;"></td>';
            echo '</tr>';

            foreach ($subKategoris as $subKategori => $barangsInSub) {
                if ($subKategori !== 'TANPA SUB KATEGORI') {
                    $subParts = explode(' | ', $subKategori, 2);
                    $subKode = count($subParts) == 2 ? $subParts[0] : '';
                    $subNama = count($subParts) == 2 ? $subParts[1] : $subKategori;

                    echo '<tr style="font-weight: bold; color: #000000; font-family: Arial; font-size: 11px;">';
                    echo '<td style="text-align: left; padding: 6px; border: 1px solid #000000; background-color: #e9e9e9;">' . htmlspecialchars($subKode) . '</td>';
                    echo '<td style="text-align: left; padding: 6px; border: 1px solid #000000; background-color: #e9e9e9;">' . htmlspecialchars(strtoupper($subNama)) . '</td>';
                    echo '<td style="border: 1px solid #000000; background-color: #e9e9e9;"></td>';
                    echo '<td style="border: 1px solid #000000; background-color: #e9e9e9;"></td>';
                    echo '<td style="border: 1px solid #000000; background-color: #e9e9e9;"></td>';
                    echo '<td style="border: 1px solid #000000; background-color: #e9e9e9;"></td>';
                    echo '<td style="border: 1px solid #000000; background-color: #e9e9e9;"></td>';
                    echo '<td style="border: 1px solid #000000; background-color: #e9e9e9;"></td>';
                    echo '<td style="border: 1px solid #000000; background-color: #e9e9e9;"></td>';
                    echo '</tr>';
                }

                foreach ($barangsInSub as $b) {
                    $saldoAwalVal = $b->saldo_awal > 0 ? number_format($b->saldo_awal, 0, '.', ',') : '-';
                    $masukVal = $b->masuk > 0 ? number_format($b->masuk, 0, '.', ',') : '-';
                    $keluarVal = $b->keluar > 0 ? number_format($b->keluar, 0, '.', ',') : '-';
                    $saldoAkhirVal = $b->saldo_akhir > 0 ? number_format($b->saldo_akhir, 0, '.', ',') : '-';
                    $hargaBeliAkhirVal = $b->harga_beli_akhir > 0 ? 'Rp ' . number_format($b->harga_beli_akhir, 0, ',', '.') : '-';
                    $jumlahRupiahVal = $b->jumlah_rupiah > 0 ? 'Rp ' . number_format($b->jumlah_rupiah, 0, ',', '.') : '-';

                    echo '<tr style="font-family: Arial; font-size: 11px;">';
                    echo '<td style="text-align: left; padding: 6px; border: 1px solid #000000;">' . htmlspecialchars($b->kode_barang ?? '-') . '</td>';
                    echo '<td style="text-align: left; padding: 6px; border: 1px solid #000000;">' . htmlspecialchars($b->nama_barang) . '</td>';
                    echo '<td style="text-align: center; padding: 6px; border: 1px solid #000000;">' . htmlspecialchars($b->nama_satuan_text) . '</td>';
                    echo '<td style="text-align: center; padding: 6px; border: 1px solid #000000;">' . $saldoAwalVal . '</td>';
                    echo '<td style="text-align: center; padding: 6px; border: 1px solid #000000;">' . $masukVal . '</td>';
                    echo '<td style="text-align: center; padding: 6px; border: 1px solid #000000;">' . $keluarVal . '</td>';
                    echo '<td style="text-align: center; padding: 6px; border: 1px solid #000000;">' . $saldoAkhirVal . '</td>';
                    echo '<td style="text-align: center; padding: 6px; border: 1px solid #000000;">' . $hargaBeliAkhirVal . '</td>';
                    echo '<td style="text-align: center; padding: 6px; border: 1px solid #000000;">' . $jumlahRupiahVal . '</td>';
                    echo '</tr>';
                }
            }
        }

        echo '</table>';
        exit;
    }

    private function getReportData($tanggalAwal, $tanggalAkhir)
    {
        $tglAwalStr = $tanggalAwal->format('Y-m-d');
        $tglAkhirStr = $tanggalAkhir->format('Y-m-d');

        $barangs = Barang::with(['subKategori.kategori', 'satuan'])->get()->map(function ($barang) use ($tglAwalStr, $tglAkhirStr) {
            // Purchases (Barang Masuk with status Diterima)
            $masuk = PembelianDetail::where('id_barang', $barang->id_barang)
                ->whereHas('pembelian', function ($q) use ($tglAwalStr, $tglAkhirStr) {
                    $q->where('status', 'Diterima')
                      ->whereBetween('tgl_terima', [$tglAwalStr, $tglAkhirStr]);
                })->sum('qty');

            $masukSetelah = PembelianDetail::where('id_barang', $barang->id_barang)
                ->whereHas('pembelian', function ($q) use ($tglAkhirStr) {
                    $q->where('status', 'Diterima')
                      ->where('tgl_terima', '>', $tglAkhirStr);
                })->sum('qty');

            // Expenses (Pengeluaran)
            $keluar = PengeluaranDetail::where('id_barang', $barang->id_barang)
                ->whereHas('pengeluaran', function ($q) use ($tglAwalStr, $tglAkhirStr) {
                    $q->whereBetween('tgl_pengeluaran', [$tglAwalStr, $tglAkhirStr]);
                })->sum('qty');

            $keluarSetelah = PengeluaranDetail::where('id_barang', $barang->id_barang)
                ->whereHas('pengeluaran', function ($q) use ($tglAkhirStr) {
                    $q->where('tgl_pengeluaran', '>', $tglAkhirStr);
                })->sum('qty');

            // Calculate historical stock balance for requested period
            $saldo_akhir = max(0, $barang->stok - $masukSetelah + $keluarSetelah);
            $saldo_awal = max(0, $saldo_akhir - $masuk + $keluar);

            // Last purchase unit price
            $lastPurchaseDetail = PembelianDetail::where('id_barang', $barang->id_barang)
                ->whereHas('pembelian', function ($q) {
                    $q->where('status', 'Diterima');
                })
                ->orderBy('id_detail', 'desc')
                ->first();

            $harga_beli_akhir = $lastPurchaseDetail ? $lastPurchaseDetail->harga : ($barang->harga_terakhir ?? 0);

            $barang->saldo_awal = $saldo_awal;
            $barang->masuk = $masuk;
            $barang->keluar = $keluar;
            $barang->saldo_akhir = $saldo_akhir;
            $barang->harga_beli_akhir = $harga_beli_akhir;
            $barang->jumlah_rupiah = ($harga_beli_akhir > 0 && $saldo_akhir > 0) ? ($saldo_akhir * $harga_beli_akhir) : 0;
            $barang->nama_satuan_text = $barang->satuan->nama_satuan ?? '-';

            return $barang;
        });

        return $barangs->groupBy(function ($barang) {
            $kat = $barang->subKategori->kategori ?? null;
            if ($kat) {
                return ($kat->kode_kategori ? $kat->kode_kategori . ' | ' : '') . $kat->nama_kategori;
            }
            return 'BAHAN BAKU LAINNYA';
        })->map(function ($group) {
            return $group->groupBy(function ($barang) {
                $sub = $barang->subKategori ?? null;
                if ($sub) {
                    return ($sub->kode_subkategori ? $sub->kode_subkategori . ' | ' : '') . $sub->nama_subkategori;
                }
                return 'TANPA SUB KATEGORI';
            });
        });
    }
}
