<?php

namespace App\Filament\Resources\Kehadirans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KehadiranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('peserta_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kegiatan_id')
                    ->required()
                    ->numeric(),
                Select::make('tipe_peserta')
                    ->options(['narasumber' => 'Narasumber', 'peserta' => 'Peserta'])
                    ->required(),
                Select::make('status_peserta')
                    ->options(['internal' => 'Internal', 'eksternal' => 'Eksternal'])
                    ->required(),
                DateTimePicker::make('waktu_hadir'),
                TextInput::make('latitude_hadir')
                    ->numeric(),
                TextInput::make('longitude_hadir')
                    ->numeric(),
                TextInput::make('tanda_tangan'),
            ]);
    }
}
