<?php

namespace App\Filament\Resources\Laporans\Pages;



use App\Filament\Resources\Laporans\LaporanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\View;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\Laporans\Widgets\KehadiranTable;
use App\Filament\Resources\Laporans\Widgets\DokumentasiTable;

class ViewLaporan extends ViewRecord
{
    protected static string $resource = LaporanResource::class;
    public function infolist(Schema $schema): Schema
    {

        return $schema->schema([
            Section::make('Detail Kegiatan')
            ->columns(6)
            ->columnSpan('full')
                ->schema([
                    TextEntry::make('kegiatan.nama_kegiatan')
                        ->label('Nama Kegiatan'),

                    TextEntry::make('kegiatan.tanggal')
                        ->label('Tanggal')
                        ->date(),

                    TextEntry::make('kegiatan.lokasi')
                        ->label('Lokasi'),
                    TextEntry::make('total_hadir')
                        ->label('Total Hadir')
                        ->badge()
                        ->color('success'),
              
                    TextEntry::make('status_persetujuan')
                        ->badge(),

                    TextEntry::make('waktu_persetujuan')
                        ->label('Waktu Persetujuan')
                        ->dateTime('d M Y')
                        ->visible(fn($record) => $record->waktu_persetujuan),
               
                ]),
                  
        ]);
    }

protected function getFooterWidgets(): array
{
    return [
        KehadiranTable::make([
            'kegiatanId' => $this->record->kegiatan_id,
        ]),
        DokumentasiTable::make([
            'laporanId' => $this->record->id_laporan,
        ]),
    ];
}

    protected function getHeaderActions(): array
    {
        return [
            Action::make('setujui')
                ->label('Setujui & Tanda Tangan')
                ->color('primary')

                ->visible(
                    fn() =>
                    Auth::user()->role === 'pimpinan' &&
                        $this->record->status_persetujuan === 'menunggu'
                )
                ->form([
                    ViewField::make('ttd')
                        ->label('Tanda Tangan Pimpinan')
                        ->view('components.signature-pad')
                        ->required(),

                    Hidden::make('status_persetujuan')
                        ->default('disetujui'),
                ])

                ->action(function (array $data) {
                    $this->record->update([
                        'ttd_pimpinan' => $data['ttd'],
                        'status_persetujuan' => 'disetujui',
                        'waktu_persetujuan' => now(),
                    ]);

                    Notification::make()
                        ->title('Laporan disetujui')
                        ->success()
                        ->send();
                }),

            // ✅ TOMBOL CETAK PDF
            Action::make('cetakPdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(
                    fn() =>
                    route('laporan.pdf', $this->record->id_laporan)
                )
                ->openUrlInNewTab()
                ->visible(
                    fn() =>
                    $this->record->status_persetujuan === 'disetujui'
                ),
        ];
    }
}
