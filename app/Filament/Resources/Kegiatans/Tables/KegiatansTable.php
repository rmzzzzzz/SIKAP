<?php

namespace App\Filament\Resources\Kegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->striped()
            ->columns([
                TextColumn::make('nama_kegiatan') ->wrap()
                    ->searchable(),
                TextColumn::make('opd.nama_opd') ->wrap()
                    ->label('OPD')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pegawai.nama')
                    ->label('PIC')
                    ->searchable(),
                TextColumn::make('tanggal') ->wrap()
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
                    ]),
                TextColumn::make('latitude') ->wrap()
                    ->numeric()
                     ->toggleable(),
                TextColumn::make('longitude') ->wrap()
                    ->numeric()
                    ->toggleable(),
                TextColumn::make('created_at') ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at') ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
