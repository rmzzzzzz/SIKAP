<?php

namespace App\Filament\Resources\Kegiatans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Toggle;
use App\Models\Pegawai;

class KegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('nama_kegiatan')
                ->label('Nama Kegiatan')
                ->required(),

            /** OPD */
            Select::make('opd_id')
                ->label('OPD')
                ->relationship('opd', 'nama_opd')
                ->default(fn() => Auth::user()?->pegawai?->opd_id)
                ->disabled(fn() => Auth::user()->role === 'operator')
                ->live()
                ->dehydrated(true)
                ->required(),
            Select::make('pic')
                ->label('Penanggung Jawab (PIC)')
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
                ->searchable()
                ->preload()
                ->reactive()
                ->required(),
            Select::make('akses_kegiatan')
                ->label('Akses Kegiatan')
                ->options([
                    'satu opd'   => 'Internal',
                    'lintas opd' => 'eksternal',
                ])
                ->required(),

            DateTimePicker::make('waktu')
                ->label('Waktu Kegiatan')
                ->required(),

            TextInput::make('lokasi')
                ->label('Lokasi')
                ->required(),

            TextInput::make('latitude')
                ->numeric()
                ->rule('between:-90,90')
                ->required(),

            TextInput::make('longitude')
                ->numeric()
                ->rule('between:-180,180')
                ->required(),

            CheckboxList::make('pegawai_ids')
                ->label('Daftar Pegawai Wajib Hadir')
                ->columns(2)
                ->bulkToggleable()
                ->searchable()
                ->reactive()
                ->options(function (callable $get) {

                    $opdId = $get('opd_id');
                    $akses = $get('akses_kegiatan');

                    if ($akses !== 'satu opd' || ! $opdId) {
                        return [];
                    }

                    return Pegawai::where('opd_id', $opdId)
                        ->pluck('nama', 'id_pegawai');
                })
                ->default(
                    fn($record) =>
                    $record?->pegawaiWajib?->pluck('id_pegawai')->toArray()
                )
                ->dehydrated(true),


            Toggle::make('buat_kehadiran')
                ->label('Gunakan Kehadiran')
                ->helperText('Aktifkan jika kegiatan memerlukan pencatatan kehadiran')
                ->default(true)
                ->dehydrated(true)
                ->dehydrateStateUsing(fn($state) => $state ? 1 : 0)
                ->required(),
                
        ]);
         
    }
}
