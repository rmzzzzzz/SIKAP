<?php

namespace App\Filament\Resources\Kehadirans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KehadiransTable
{
    public static function configure(Table $table): Table

    {

        return $table
            ->striped()
            ->defaultSort('tanggal', 'desc')
            ->columns([
                TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('opd.nama_opd')
                    ->label('OPD')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('pegawai.nama')->label('PIC')
                    ->searchable(),
                TextColumn::make('lokasi')
                    ->searchable(),
                TextColumn::make('tanggal')
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
                    })
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('total_hadir')
                    ->label('Hadir')
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                ViewAction::make()
                    ->label(''),
            ]);
    }
}
