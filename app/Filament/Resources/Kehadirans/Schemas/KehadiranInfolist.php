<?php

namespace App\Filament\Resources\Kehadirans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KehadiranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('pegawai_id')
                    ->numeric(),
                TextEntry::make('kegiatan_id')
                    ->numeric(),
                TextEntry::make('tipe_pegawai')
                    ->badge(),
                TextEntry::make('status_pegawai')
                    ->badge(),
                TextEntry::make('waktu_hadir')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('latitude_hadir')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude_hadir')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tanda_tangan')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
