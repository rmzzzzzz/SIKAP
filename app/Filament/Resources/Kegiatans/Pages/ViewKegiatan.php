<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Resources\Kegiatans\Widgets\KehadiranTable;

use App\Models\Pegawai;
use App\Models\Kehadiran;
use App\Models\Laporan;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ViewKegiatan extends ViewRecord
{
    protected static string $resource = KegiatanResource::class;



    public function infolist(Schema $schema): Schema
    {
        $kegiatan = $this->getRecord();

        return $schema->schema([
            Section::make('Detail Kegiatan')
                ->columns(10)
                ->columnSpan('full')
                ->schema([
                    TextEntry::make('nama_kegiatan')
                        ->label('Nama Kegiatan')
                        ->weight('bold'),

                    TextEntry::make('opd.nama_opd')
                        ->label('OPD'),
                    TextEntry::make('pegawai.nama')
                        ->label('PIC'),

                    TextEntry::make('tanggal')
                        ->dateTime('d M Y'),

                    TextEntry::make('waktu_mulai')
                        ->label('Waktu Mulai')
                        ->dateTime('H:i'),
                    TextEntry::make('waktu_selesai')
                        ->label('Waktu Selesai')    
                        ->dateTime('H:i'),

                    TextEntry::make('lokasi'),
                    TextEntry::make('latitude')
                        ->numeric(),
                    TextEntry::make('longitude')
                        ->numeric(),

                    TextEntry::make('akses_kegiatan')
                        ->label('Jenis Kegiatan')
                        ->badge()
                        ->color(fn(string $state) => match ($state) {
                            'satu opd'    => 'success',
                            'lintas opd'  => 'info',
                            default       => 'gray',
                        })
                        ->formatStateUsing(fn(string $state) => match ($state) {
                            'satu opd'    => 'Internal',
                            'lintas opd'  => 'Eksternal',
                            default       => ucfirst(str_replace('_', ' ', $state)),
                        }),
                ]),


            Section::make('Daftar Wajib Hadir (Pegawai Internal)')
                ->visible(fn() => $kegiatan->akses_kegiatan === 'satu opd')
                ->columnSpan('full')
                ->schema([
                    RepeatableEntry::make('pegawai_wajib')
                        ->columns(4)
                        ->schema([
                            TextEntry::make('nama'),
                            TextEntry::make('nip')->label('NIP'),
                            TextEntry::make('jabatan'),
                        ])
                        ->state(function () use ($kegiatan) {

                            $pegawaiOpd = $kegiatan->pegawaiWajib;

                            $hadirIds = Kehadiran::where('kegiatan_id', $kegiatan->id)
                                ->pluck('pegawai_id')
                                ->toArray();

                            return $pegawaiOpd->map(fn($p) => [
                                'nama'    => $p->nama,
                                'nip'     => $p->nip,
                                'jabatan' => $p->jabatan,
                                'status'  => in_array($p->id, $hadirIds)
                                    ? 'hadir'
                                    : 'belum',
                            ]);
                        }),
                ]),
        ]);
}
}
