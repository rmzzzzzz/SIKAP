<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class KegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kegiatan')
                    ->required(),
                Select::make('opd_id')
                    ->default(fn() => Auth::user()->opd_id)
                    ->label('Opd')
                    ->relationship('opd', 'nama_opd')
                    ->live()
                    ->required()
                    ->dehydrated(true),
                Select::make('pic')
                    ->label('PIC')
                    ->relationship(
                        name: 'pegawai',
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
                        'satu opd' => 'Internal',
                        'lintas opd' => 'Eksternal',
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
                Select::make('pegawai_ids')
                    ->label('Peserta')
                    ->multiple() // INI KUNCI
                    ->searchable()
                    ->options(
                        fn() =>
                        \App\Models\Pegawai::query()
                            ->when(Auth::user()->role === 'operator', function ($q) {
                                $q->where('opd_id', Auth::user()->opd_id)
                                    ->orWhereNull('opd_id');
                            })
                            ->pluck('nama', 'id_pegawai')
                    )
                    ->helperText('Pilih lebih dari satu pegawai')
                    ->dehydrated(false),
            ]);
    }
}
