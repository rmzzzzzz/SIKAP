<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('opd_id')
                    ->label('Opd')
                    ->relationship('opd', 'nama_opd')
                    ->live()
                    ->required(),
                TextInput::make('nama_kegiatan')
                    ->required(),
                Select::make('pic')
                    ->label('PIC')
                    ->relationship(
                        name: 'peserta',
                        titleAttribute: 'nama',
                        modifyQueryUsing: function (Builder $query, callable $get) {
                            $opdId = $get('opd_id');

                            if ($opdId) {
                                $query->where('opd_id', $opdId);
                            }
                        }
                    )
                    ->required(),
                    select::make('akses_kegiatan')
                    ->label('Akses Kegiatan')
                    ->options([
                        'satu opd' => 'Satu OPD',
                        'lintas opd' => 'Lintas OPD',
                    ])
                    ->required(),
                DateTimePicker::make('waktu')
                    ->required(),
                TextInput::make('lokasi')
                    ->required(),
                TextInput::make('latitude')
                    ->required()
                    ->rule('between:-90,90')
                    ->numeric(),
                TextInput::make('longitude')
                    ->required()
                    ->rule('between:-180,180')
                    ->numeric(),
            ]);
    }
}
