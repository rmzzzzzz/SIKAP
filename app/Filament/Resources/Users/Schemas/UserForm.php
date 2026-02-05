<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ROLE
            Select::make('role')
                ->options([
                    'super_admin' => 'Super Admin',
                    'operator'    => 'Operator',
                    'pimpinan'    => 'Pimpinan',
                ])
                ->required()
                ->live(),

            // OPD FILTER (hanya untuk memilih pegawai)
            Select::make('opd_filter')
                ->label('OPD')
                ->options(fn () => \App\Models\Opd::pluck('nama_opd', 'id_opd'))
                ->searchable()
                ->reactive()
                ->hidden(fn ($get) => $get('role') === 'super_admin')
                ->dehydrated(false), // tidak disimpan ke users

            // PILIH PEGAWAI
            Select::make('pegawai_id')
                ->label('Nama Pegawai')
                ->options(function (callable $get) {
                    $opdId = $get('opd_filter');
                    if (!$opdId) return [];

                    return \App\Models\Pegawai::where('opd_id', $opdId)
                        ->pluck('nama', 'id_pegawai');
                })
                ->searchable()
                ->reactive()
                ->hidden(fn ($get) =>
                    $get('role') === 'super_admin' || !$get('opd_filter')
                )
                ->afterStateUpdated(function ($state, callable $set) {
                    $pegawai = \App\Models\Pegawai::find($state);

                    if ($pegawai) {
                        $set('name', $pegawai->nama);
                        $set('email', $pegawai->email);
                    }
                }),

            // NAME (manual hanya untuk super admin)
            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->hidden(fn ($get) =>
                    $get('role') !== 'super_admin' && $get('pegawai_id')
                ),

            // EMAIL
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            // PASSWORD
            TextInput::make('password')
                ->password()
                ->required(fn ($context) => $context === 'create')
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->dehydrated(fn ($state) => filled($state)),
        ]);
    }
}
