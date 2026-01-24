<?php

namespace App\Filament\Resources\Pesertas\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PesertaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('opd_id')
                ->label('Opd')
                    ->relationship('opd', 'nama_opd')
                    ->searchable(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('nip'),
                TextInput::make('jabatan')
                    ->required(),
                TextInput::make('unit_kerja')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('telp')
                    ->tel()
                    ->required(),
            ]);
    }
}
