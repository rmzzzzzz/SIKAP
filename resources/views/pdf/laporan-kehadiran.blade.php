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
                    {{ $laporan->kegiatan->opd->alamat }} | Telepon: {{ $laporan->kegiatan->opd->telp }}<br>
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
            <td>Hari / Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($laporan->kegiatan->tanggal)->translatedFormat('l / d F Y') }}</td>
        </tr>
        tr>
            <td>Waktu</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($laporan->kegiatan->waktu_mulai)->translatedFormat('H:i') }} - {{ \Carbon\Carbon::parse($laporan->kegiatan->waktu_selesai)->translatedFormat('H:i') }} WIB</td>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td>{{ $laporan->kegiatan->lokasi }}</td>
        </tr>
        <tr>
            <td width="25%">Acara</td>
            <td width="2%">:</td>
            <td>{{ $laporan->kegiatan->nama_kegiatan }}</td>
        </tr>
    </table>
</div>

{{-- TABEL ABSENSI --}}
@php
    $chunks = $laporan->kegiatan->kehadiran->chunk(20);
@endphp

@foreach ($chunks as $chunkIndex => $chunk)
    
    @if ($chunkIndex > 0)
        <div style="page-break-before: always;"></div>
    @endif

    <table class="absen">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">NAMA</th>

                @if ($laporan->kegiatan->akses_kegiatan === 'lintas_opd')
                    <th width="20%">OPD</th>
                @endif

                <th width="20%">JABATAN</th>
                <th width="15%">NO. TELP</th>
                <th width="15%">TANDA TANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chunk as $i => $h)
                <tr>
                    <td>{{ ($chunkIndex * 20) + $i + 1 }}</td>
                    <td class="nama">{{ $h->pegawai->nama }}</td>

                    @if ($laporan->kegiatan->akses_kegiatan === 'lintas_opd')
                        <td>{{ $h->pegawai->opd->nama_opd ?? $h->pegawai->unit_kerja ?? '-' }}</td>
                    @endif

                    <td>{{ $h->pegawai->jabatan }}</td>
                    <td>{{ $h->pegawai->telp ?? '-' }}</td>
                    <td>{{ ($chunkIndex * 20) + $i + 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endforeach


{{-- TANDA TANGAN PIMPINAN --}}
<div class="ttd">
    <div class="ttd-kanan">
        <div>Sumenep, {{ now()->translatedFormat('d F Y') }}</div>
        <div>Kepala</div>

        <img class="" src="{{ $laporan->ttd_pimpinan }}">
        
        <div>{{ $pimpinan->name ?? '-' }}</div>
        <div>{{ $pimpinan->pegawai->jabatan ?? '-' }}</div>
        <div>NIP: {{ $pimpinan->pegawai->nip ?? '-' }}</div>
        {{-- <div style="margin-top:5px;font-weight:bold;">
            {{ $laporan->kegiatan->opd->nama_opd }}
        </div> --}}
    </div>
    <div class="clear"></div>
</div>
@if ($laporan->dokumentasi->count())

    @php
        $fotoChunks = $laporan->dokumentasi->chunk(4);
    @endphp

    @foreach ($fotoChunks as $index => $chunk)

        <div style="page-break-before: always;"></div>

        <div style="margin-top:20px;">
            <strong>DOKUMENTASI KEGIATAN</strong>
            <br><br>

            <table width="100%" cellpadding="5">
                @foreach ($chunk->chunk(2) as $row)
                    <tr>
                        @foreach ($row as $foto)
                            <td width="50%" align="center">
                                <img 
                                    src="{{ public_path('storage/' . $foto->path) }}"
                                    style="width:100%; max-height:250px;"
                                >
                            </td>
                        @endforeach

                        @if ($row->count() == 1)
                            <td width="50%"></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>

    @endforeach

@endif


</body>
</html>
