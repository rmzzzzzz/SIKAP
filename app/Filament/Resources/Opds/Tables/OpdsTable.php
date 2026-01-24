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
            ]);
    }
}
