<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kehadiran</title>
    <style>
        body { font-family: sans-serif; }
        .title { text-align: center; font-size: 20px; font-weight: bold; }
        .section { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        .ttd { margin-top: 60px; text-align: right; }
        img { width: 200px; }
    </style>
</head>
<body>

<div class="title">LAPORAN KEHADIRAN KEGIATAN</div>

<div class="section">
    <strong>Kegiatan:</strong> {{ $laporan->kegiatan->nama_kegiatan }}<br>
    <strong>OPD:</strong> {{ $laporan->kegiatan->opd->nama_opd }}<br>
    <strong>Waktu:</strong> {{ $laporan->kegiatan->waktu }}<br>
    <strong>Lokasi:</strong> {{ $laporan->kegiatan->lokasi }}<br>
    <strong>Total Hadir:</strong> {{ $laporan->total_hadir }}
</div>

<div class="ttd">
    <p>Pimpinan OPD</p>
    <img src="{{ $laporan->ttd_pimpinan }}" alt="Tanda Tangan">
</div>

</body>
</html>
