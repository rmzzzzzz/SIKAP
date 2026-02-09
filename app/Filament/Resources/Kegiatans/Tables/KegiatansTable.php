<?php

namespace App\Filament\Resources\Kegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('opd.nama_opd')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_kegiatan')
                    ->searchable(),
                TextColumn::make('pegawai.nama')
                    ->label('PIC')
                    ->searchable(),
                TextColumn::make('waktu')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lokasi')
                    ->searchable(),
                TextColumn::make('akses_kegiatan')
                    ->colors([
                        'success' => 'lintas_opd',
                        'danger'  => 'satu_opd',
                    ])
                    ->sortable(),
                // TextColumn::make('latitude')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('longitude')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
