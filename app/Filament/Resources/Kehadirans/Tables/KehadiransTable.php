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
        ->defaultSort('waktu', 'desc')
              ->columns([
            TextColumn::make('nama_kegiatan')->label('Kegiatan'),
            TextColumn::make('opd.nama_opd')->label('OPD'),
            TextColumn::make('waktu')->dateTime(),
            TextColumn::make('total_hadir')
                ->label('Hadir')
                ->badge()
                ->color('success'),
        ])
        ->actions([
            ViewAction::make()
                ->label('Detail'),
        ]);
    }
}
