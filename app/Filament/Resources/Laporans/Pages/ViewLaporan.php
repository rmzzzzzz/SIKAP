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

class ViewLaporan extends ViewRecord
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            // ✅ TOMBOL SETUJUI
            Action::make('setujui')
                ->label('Setujui & Tanda Tangan')
                ->color('primary')

                ->visible(fn () =>
                    Auth::user()->role === 'pimpinan' &&
                    $this->record->status_persetujuan === 'menunggu'
                )
//   ->form([
//     View::make('components.signature-pad'),

//     Hidden::make('ttd'), // ini penampung base64 dari canvas

//     Hidden::make('status_persetujuan')
//         ->default('disetujui'),
// ])

                ->form([
                    Textarea::make('ttd')
                        ->label('Tanda Tangan (Base64)')
                        ->required(),

                    Hidden::make('status_persetujuan')
                        ->default('disetujui'),
                ])

                ->action(function (array $data) {
                    $this->record->update([
                        'ttd_pimpinan' => $data['ttd'],
                        'status_persetujuan' => $data['status_persetujuan'],
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
                ->url(fn () =>
                    route('laporan.pdf', $this->record->id_laporan)
                )
                ->openUrlInNewTab()
                ->visible(fn () =>
                    $this->record->status_persetujuan === 'disetujui'
                ),
        ];
    }
}
