<?php

namespace App\Filament\Resources\Laporans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

class LaporanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('ttd')
                    ->label('Tanda Tangan Pimpinan (Base64)')
                    ->visible(fn() => Auth::user()->role === 'pimpinan')
                    ->required(),

                Hidden::make('status_persetujuan')
                    ->default('disetujui'),
            ]);
    }
}
