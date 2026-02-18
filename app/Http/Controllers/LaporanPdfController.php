<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\user;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPdfController extends Controller
{
    public function generate($id)
    {
        $laporan = Laporan::with('kegiatan.opd', 'kegiatan.pegawai.user')->findOrFail($id);
        $pimpinan = User::where('role', 'pimpinan')
            ->whereHas(
                'pegawai',
                fn($q) =>
                $q->where('opd_id', $laporan->kegiatan->opd_id)
            )
            ->first();
        $pdf = Pdf::loadView('pdf.laporan-kehadiran', [
            'laporan' => $laporan,
            'pimpinan' => $pimpinan,    
        ]);

        return $pdf->stream('laporan-kehadiran.pdf');
    }
}
