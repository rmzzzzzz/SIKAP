<?php

namespace App\Filament\Resources\Pegawais\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('opd_id')
                ->label('Opd')
                    ->relationship('opd', 'nama_opd')
                    ->default(fn () => Auth::user()->opd_id)
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
