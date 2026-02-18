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
                TextColumn::make('nama_kegiatan')
                    ->searchable(),
                TextColumn::make('opd.nama_opd')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pegawai.nama')
                    ->label('PIC')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lokasi')
                    ->searchable(),
                TextColumn::make('akses_kegiatan')
                ->label('Jenis Kegiatan')
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'satu opd'    => 'Internal',
                        'lintas opd'  => 'Eksternal',
                        default       => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->colors([
                        'success' => 'lintas_opd',
                        'danger'  => 'satu_opd',
                    ])
                    ->sortable(),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                     ->toggleable(),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
