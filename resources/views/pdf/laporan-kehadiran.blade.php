<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }

        .kop {
            width: 100%;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .kop-table {
            width: 100%;
        }

        .kop-logo {
            width: 80px;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text .instansi {
            font-size: 14px;
            font-weight: bold;
        }

        .kop-text .nama {
            font-size: 20px;
            font-weight: bold;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            font-size: 14px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 3px 0;
            vertical-align: top;
        }

        table.absen {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.absen th,
        table.absen td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        table.absen th {
            font-weight: bold;
        }

        table.absen td.nama,
        table.absen td.jabatan {
            text-align: left;
        }

        .ttd {
            width: 100%;
            margin-top: 40px;
        }

        .ttd-kanan {
            width: 40%;
            float: right;
            text-align: center;
        }

        .ttd img {
            width: 160px;
            margin-top: 5px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>

{{-- KOP SURAT --}}
<div class="kop">
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                {{-- Ganti path logo sesuai aset --}}
                <img src="{{ public_path('sumenep.png') }}" width="70">
            </td>
            <td class="kop-text">
                <div class="instansi">PEMERINTAH KABUPATEN SUMENEP</div>
                <div class="nama">{{ strtoupper($laporan->kegiatan->opd->nama_opd) }}</div>
                <div>
                    {{ $laporan->kegiatan->opd->alamat }}<br>
                    Email: {{ $laporan->kegiatan->opd->email }} | Website: {{ $laporan->kegiatan->opd->website }}
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- JUDUL --}}
<div class="judul">DAFTAR HADIR</div>

{{-- INFO KEGIATAN --}}
<div class="info">
    <table>
        <tr>
            <td width="25%">Rincian Kegiatan</td>
            <td width="2%">:</td>
            <td>{{ $laporan->kegiatan->nama_kegiatan }}</td>
        </tr>
        <tr>
            <td>Hari / Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($laporan->kegiatan->waktu)->translatedFormat('l / d F Y') }}</td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td>{{ $laporan->kegiatan->lokasi }}</td>
        </tr>
    </table>
</div>

{{-- TABEL ABSENSI --}}
<table class="absen">
    <thead>
        <tr>
           <th width="5%">NO</th>
        <th width="25%">NAMA</th>

        @if ($laporan->kegiatan->akses_kegiatan === 'lintas_opd')
            <th width="25%">OPD</th>
        @endif

        <th width="20%">JABATAN</th>
        <th width="15%">NO. TELP</th>
        <th width="10%">TANDA TANGAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporan->kegiatan->kehadiran as $i => $h)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td class="nama">{{ $h->pegawai->nama }}</td>
              @if ($laporan->kegiatan->akses_kegiatan === 'lintas_opd')
            <td>
                {{ $h->pegawai->opd->nama_opd ?? '-' }}
            </td>
        @endif
            <td class="jabatan">{{ $h->pegawai->jabatan }}</td>
             <td>
            {{ $h->pegawai->telp ?? '-' }}
        </td>
            <td >{{ $h->tanda_tangan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- TANDA TANGAN PIMPINAN --}}
<div class="ttd">
    <div class="ttd-kanan">
        <div>Sumenep, {{ now()->translatedFormat('d F Y') }}</div>
        <div>Pimpinan OPD</div>

        <img src="{{ $laporan->ttd_pimpinan }}">

        <div style="margin-top:5px;font-weight:bold;">
            {{ $laporan->kegiatan->opd->nama_opd }}
        </div>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>
