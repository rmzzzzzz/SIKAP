<?php

namespace App\Filament\Resources\Opds\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


class OpdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_opd')
                    ->label('Nama OPD')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telp')
                    ->label('Telepon'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('website')
                    ->label('Website')
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
