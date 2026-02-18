<?php

namespace App\Filament\Resources\Laporans\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Kehadiran;
use Filament\Tables\Columns\TextColumn;

class KehadiranTable extends TableWidget
{
     public ?int $kegiatanId = null;

    protected static ?string $heading = 'Daftar Kehadiran';
    public function table(Table $table): Table
    {
        
        return $table
            ->query(
                Kehadiran::query()
                    ->where('kegiatan_id', $this->kegiatanId)
                    ->with(['pegawai.opd']))
            ->columns([
                TextColumn::make('pegawai.nama')
                                ->label('Nama'),

                            TextColumn::make('pegawai.jabatan')
                                ->label('Jabatan'),
                             TextColumn::make('opd_display')
                                ->label('OPD')
                                ->getStateUsing(
                                    fn($record) =>
                                    $record->pegawai?->opd?->nama_opd
                                        ?? $record->pegawai?->unit_kerja
                                ),

                            TextColumn::make('status_pegawai')
                                ->label('Status')
                                ->badge()
                                ->color(
                                    fn(string $state) =>
                                    $state === 'internal' ? 'success' : 'warning'
                                ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
