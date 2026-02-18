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
                    ->default(fn() => Auth::user()->opd_id)
                    ->searchable(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('nip'),
                TextInput::make('jabatan')
                    ->required(),
                Select::make('pangkat_id')
                    ->label('Pangkat')
                    ->relationship('pangkat', 'nama_pangkat')
                    ->searchable()
                    ->helperText('Opsional, jika pegawai memiliki pangkat')
                    ->nullable(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('telp')
                    ->tel()
                    ->required()
                    ->helperText('Masukkan nomor telepon dengan format yang benar, misalnya: 081234567890'),
            ]);
    }
}
