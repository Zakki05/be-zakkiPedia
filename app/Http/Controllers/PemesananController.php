<?php

namespace App\Http\Controllers;

use App\Exports\LaporanBulananExport;
use App\Http\Resources\PemesananResource;
use App\Http\Resources\PagingResource;
use App\Models\Ongkir;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Exports\LaporanHarianExport;
use Maatwebsite\Excel\Facades\Excel;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $status = $request->status;

        $pemesanans = Pemesanan::where(function ($f) use ($q, $status) {
            if ($q) {
                $f->where('kode_pemesanan', 'LIKE', '%' . $q . '%');
            }
            if ($status) {
                $f->where('status', $status);
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $data['records'] = PemesananResource::collection($pemesanans);
        $data['paging'] = new PagingResource($pemesanans);
        return $this->success($data, 'Data berhasil di ambil');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ongkir = Ongkir::find($request->ongkir_id);
        $total_pemesanan = 0;
        $biaya = $ongkir->biaya;
        $subtotal = $total_pemesanan + $biaya;

        $pemesanan = new Pemesanan;
        $pemesanan->pelanggan_id = $request->pelanggan_id;
        $pemesanan->ongkir_id = $request->ongkir_id;
        $pemesanan->pembayaran_id = null;
        $pemesanan->tanggal = now();;
        $pemesanan->kecamatan = $request->kecamatan;
        $pemesanan->kelurahan = $request->kelurahan;
        $pemesanan->alamat = $request->alamat;
        $pemesanan->kode_pos = $request->kode_pos;
        $pemesanan->status = $request->status;
        $pemesanan->total_pemesanan = $total_pemesanan;
        $pemesanan->biaya = $biaya;
        $pemesanan->subtotal = $subtotal;
        $pemesanan->save();
        $data['record'] = new PemesananResource($pemesanan);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemesanan = Pemesanan:: findOrFail($id);
        $data['record'] = new PemesananResource($pemesanan);
        return $this -> success($data,'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemesanan $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->pelanggan_id = $request->pelanggan_id;
        $pemesanan->ongkir_id = $request->ongkir_id;
        $pemesanan->pembayaran_id = $request->pembayaran_id;
        $pemesanan->tanggal = $request->tanggal;
        $pemesanan->kecamatan = $request->kecamatan;
        $pemesanan->kelurahan = $request->kelurahan;
        $pemesanan->alamat = $request->alamat;
        $pemesanan->kode_pos = $request->kode_pos;
        $pemesanan->status = $request->status;
        $pemesanan->total_pemesanan = $request->total_pemesanan;
        $pemesanan->biaya = $request->biaya;
        $pemesanan->subtotal = $request->subtotal;
        $pemesanan->save();
        $data['record'] = new PemesananResource($pemesanan);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pemesanan::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }

    public function pembayaranAdmin(Request $request, string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        $pembayaran = new Pembayaran;
        $pembayaran->pelanggan_id = $pemesanan->pelanggan_id;
        $pembayaran->tanggal_pembayaran = now();
        $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        $pembayaran->pembayaran = $pemesanan->subtotal;
        $pembayaran->status = $request->status;
        $pembayaran->save();

        $pemesanan->pembayaran_id = $pembayaran->id;
        $pemesanan->save();

        $data['record'] = new PemesananResource($pemesanan);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    public function simpanPesanan(Request $request, string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        $pemesanan->status = $request->status;
        $pemesanan->save();

        $data['record'] = new PemesananResource($pemesanan);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    public function getPemesananSuperAdmin(Request $request)
    {
        $q = $request->q;
        $status = $request->status;

        $pemesanans = Pemesanan::where(function ($f) use ($q, $status) {
            if ($q) {
                $f->where('kode_pemesanan', 'LIKE', '%' . $q . '%');
            }
            if ($status) {
                $f->where('status', $status);
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $data['records'] = PemesananResource::collection($pemesanans);
        $data['paging'] = new PagingResource($pemesanans);
        return $this->success($data, 'Data berhasil di ambil');
    }

    public function exportLaporanHarian(Request $request)
    {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Ambil parameter dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Lakukan export
        return Excel::download(
            new LaporanHarianExport($startDate, $endDate),
            'laporan_pemesanan_' . $startDate . '_to_' . $endDate . '.xlsx'
        );
    }

    public function exportLaporanBulanan(Request $request)
    {
        // Validasi input
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:'.date('Y'),
        ]);

        // Ambil parameter dari request
        $month = $request->input('month');
        $year = $request->input('year');

        // Lakukan export
        return Excel::download(
            new LaporanBulananExport($month, $year),
            'laporan_bulanan_' . $year . '_' . $month . '.xlsx'
        );
    }
}
