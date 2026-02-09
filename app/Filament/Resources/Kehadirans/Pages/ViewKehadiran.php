<?php

namespace App\Filament\Resources\Kehadirans\Pages;

use App\Filament\Resources\Kehadirans\KehadiranResource;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use App\Models\Laporan;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Support\Facades\Auth;

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

                ->visible(function () {
                    $record = $this->record;
                    $user   = Auth::user();

                    if ($user->role !== 'operator') {
                        return false;
                    }
                    
                    return ! Laporan::where(
                        'kegiatan_id',
                        $record->id_kegiatan
                    )->exists();
                })

                ->requiresConfirmation()

                ->action(function () {
                    $record = $this->record;

                    $totalHadir = Kehadiran::where(
                        'kegiatan_id',
                        $record->id_kegiatan
                    )->count();

                    Laporan::create([
                        'kegiatan_id' => $record->id_kegiatan,
                        'opd_id' => $record->opd_id,
                        'total_hadir' => $totalHadir,
                        'status_persetujuan' => 'menunggu',
                    ]);

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
                ->columns(4)
                ->columnSpan('full')
                ->schema([
                    TextEntry::make('nama_kegiatan')->label('Kegiatan'),
                    TextEntry::make('opd.nama_opd')->label('OPD'),
                    TextEntry::make('waktu')->dateTime(),
                    TextEntry::make('lokasi'),
                ]),

            Section::make('Daftar Kehadiran')
            ->columnSpan('full')
                ->schema([
                    RepeatableEntry::make('kehadiran')
                        ->label(false)
                        ->columns(4)
                        
                        ->schema([
                            TextEntry::make('pegawai.nama')
                                ->label('Nama Pegawai'),

                            TextEntry::make('pegawai.jabatan')
                                ->label('Jabatan'),
                            TextEntry::make('pegawai.opd.nama_opd')
                                ->label('OPD'),

                            TextEntry::make('status_pegawai')
                                ->label('Status')
                                ->badge()
                                ->color(
                                    fn(string $state) =>
                                    $state === 'internal' ? 'success' : 'warning'
                                ),
                        ])
                        
                ]),
        ]);
    }
}
