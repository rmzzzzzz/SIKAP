<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Kegiatan;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use App\Models\Opd;

class KegiatanController extends Controller
{
    /**
     * Halaman Beranda (welcome)
     * Menampilkan kegiatan lintas OPD hari ini
     */
    public function index()
    {
        Carbon::setLocale('id');

        $kegiatan = Kegiatan::where('akses_kegiatan', 'lintas opd')

            ->whereDate('tanggal', Carbon::today())   
            ->orderBy('waktu_mulai', 'asc')            
            ->get();

        return view('welcome', compact('kegiatan'));
    }

    /**
     * Halaman Agenda Per OPD
     */
    public function agendaOpd()
    {
        Carbon::setLocale('id');

        $kegiatan = Kegiatan::with('opd')
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('waktu_mulai', 'asc')            // ✅ urut jam mulai

            ->get();

        $list_opd = Opd::orderBy('nama_opd', 'asc')->get();
        //memanggil 'agenda-opd'

        return view('agenda-opd', compact('kegiatan', 'list_opd'));
    }

    /**
     * Halaman Form Presensi
     */
    public function hadir($id)
    {
        $kegiatan = Kegiatan::with('opd')->findOrFail($id);
        $pegawai = Pegawai::all();

        return view('hadir', compact('kegiatan', 'pegawai'));
    }

    /**
     * Proses Simpan Presensi
     */
    public function storeHadir(Request $request, $id)
    {
        $request->validate([
            'status_pegawai'  => 'required|in:internal,eksternal',
            'tipe_pegawai'    => 'required',
            'tanda_tangan'    => 'required',
            'latitude_hadir'  => 'required',
            'longitude_hadir' => 'required',
            'nama'            => 'required_if:status_pegawai,eksternal',
        ], [
            'tanda_tangan.required' => 'Anda belum membubuhkan tanda tangan.',
            'latitude_hadir.required' => 'Koordinat lokasi tidak ditemukan.',
        ]);

        try {
            $pegawaiId = $request->pegawai_id;

            if ($request->status_pegawai == 'eksternal' && !$pegawaiId) {
                $pegawai = Pegawai::create([
                    'nama'       => $request->nama,
                    'nip'        => $request->nip,
                    'email'      => $request->email,
                    'telp'       => $request->telp,
                    'jabatan'    => $request->jabatan,
                    'unit_kerja' => $request->unit_kerja,
                    'opd_id'     => null
                ]);
                $pegawaiId = $pegawai->id_pegawai;
            }

            $cekDuplikasi = Kehadiran::where('pegawai_id', $pegawaiId)
                ->where('kegiatan_id', $id)
                ->first();

            if ($cekDuplikasi) {
                return back()->with('error', 'Anda sudah mengisi presensi untuk kegiatan ini sebelumnya.');
            }

            Kehadiran::create([
                'pegawai_id'      => $pegawaiId,
                'kegiatan_id'     => $id,
                'tipe_pegawai'    => $request->tipe_pegawai,
                'status_pegawai'  => $request->status_pegawai,
                'waktu_hadir'     => now(),
                'latitude_hadir'  => $request->latitude_hadir,
                'longitude_hadir' => $request->longitude_hadir,
                'tanda_tangan'    => $request->tanda_tangan,
            ]);

            return back()->with('success', 'Terima kasih, presensi Anda telah berhasil disimpan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}
