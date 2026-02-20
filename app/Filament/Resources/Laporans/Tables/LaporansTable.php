<?php

namespace App\Filament\Resources\Laporans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LaporansTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->striped()
            ->columns([
                TextColumn::make('kegiatan.nama_kegiatan')
                    ->label('Kegiatan'),
                TextColumn::make('opd.nama_opd')
                    ->label('OPD'),
                TextColumn::make('total_hadir')
                    ->label('Total Hadir')
                    ->badge()
                    ->color('success'),

                TextColumn::make('status_persetujuan')
                    ->badge()
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->filters([
                //
            ])
             ->recordActions([
                EditAction::make()
                    ->label(''),
                DeleteAction::make()
                    ->label('')
                    ->requiresConfirmation()
                    ->color('danger'), 
            ])     
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
