<?php

namespace App\Filament\Resources\Opds\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OpdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('nama_opd')
                    ->label('Nama OPD')
                    ->required()
                    ->maxLength(150),
                TextInput::make('alamat')
                    ->label('Alamat OPD')
                    ->required()
                    ->maxLength(255),
                TextInput::make('telp')
                    ->label('Telepon OPD')
                    ->required()
                    ->maxLength(20),
                TextInput::make('email')
                    ->label('Email OPD')
                    ->required()
                    ->email()
                    ->maxLength(100),
                TextInput::make('website')
                    ->label('Website OPD')
                    ->required()
                    ->maxLength(100),

            ]);
    }
}
