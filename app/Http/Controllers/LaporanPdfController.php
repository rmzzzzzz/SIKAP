<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPdfController extends Controller
{
    public function generate($id)
    {
        $laporan = Laporan::with('kegiatan.opd')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.laporan-kehadiran', [
            'laporan' => $laporan,
        ]);

        return $pdf->stream('laporan-kehadiran.pdf');
    }
}
