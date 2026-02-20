<?php

namespace App\Filament\Resources\Kehadirans\Pages;

use App\Filament\Resources\Kehadirans\KehadiranResource;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use App\Models\Laporan;
use App\Models\Dokumentasi;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use App\Services\ImageService;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\Kehadirans\Widgets\KehadiranTable;

class ViewKehadiran extends ViewRecord
{
    protected static string $resource = KehadiranResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('ajukanLaporan')
                ->label('Ajukan Laporan')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')

                ->form([
                    FileUpload::make('foto')
                        ->label('Dokumentasi Kegiatan')
                        ->multiple()
                        ->image()
                        ->imagePreviewHeight('150')
                        ->panelLayout('grid')
                        ->previewable(true)
                        ->openable()
                        ->downloadable()
                        ->reorderable()
                        ->maxFiles(5)
                        ->directory('laporan')
                        ->disk('public')
                        ->required()
                ])
                ->visible(function () {
                    $record = $this->record;
                    $user   = Auth::user();

                    if ($user->role === 'pimpinan') {
                        return false;
                    }

                    return ! Laporan::where(
                        'kegiatan_id',
                        $record->id_kegiatan
                    )->exists();
                })

                ->requiresConfirmation()

                ->action(function (array $data) {
                    $record = $this->record;

                    $totalHadir = Kehadiran::where(
                        'kegiatan_id',
                        $record->id_kegiatan
                    )->count();

                    $laporan = Laporan::create([
                        'kegiatan_id' => $record->id_kegiatan,
                        'opd_id' => $record->opd_id,
                        'total_hadir' => $totalHadir,
                        'status_persetujuan' => 'menunggu',
                    ]);
                    // simpan semua foto
                    foreach ($data['foto'] as $file) {

                        $compressedPath = ImageService::compressAndStore(
                            storage_path('app/public/' . $file),
                            'laporan'
                        );
                        Dokumentasi::create([
                            'laporan_id' => $laporan->id_laporan,
                            'path' => $compressedPath,
                        ]);
                        Storage::disk('public')->delete($file);
                    }

                    Notification::make()
                        ->title('Laporan berhasil diajukan')
                        ->success()
                        ->send();
                }),
        ];
    }
    public function infolist(Schema $schema): Schema
    {


        return $schema->schema([
            Section::make('Detail Kegiatan')
                ->columns(8)
                ->columnSpan('full')
                ->schema([
                    TextEntry::make('nama_kegiatan')->label('Kegiatan'),
                    TextEntry::make('opd.nama_opd')->label('OPD'),
                    TextEntry::make('pegawai.nama')->label('PIC'),
                    TextEntry::make('tanggal')
                        ->label('Tanggal & Waktu')
                        ->getStateUsing(function ($record) {
                            return collect([
                                $record->tanggal
                                    ? \Carbon\Carbon::parse($record->tanggal)->translatedFormat('d F Y')
                                    : '-',
                                $record->waktu_mulai && $record->waktu_selesai
                                    ? "{$record->waktu_mulai} - {$record->waktu_selesai}"
                                    : '-'
                            ])->implode(' | ');
                        }),
                    TextEntry::make('lokasi'),
                    TextEntry::make('latitude')->numeric(),
                    TextEntry::make('longitude')->numeric(),
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
        ]);
    }
    protected function getFooterWidgets(): array
    {
        return [
            KehadiranTable::make([
                'kegiatanId' => $this->record->id_kegiatan,
            ]),
        ];
    }
}
