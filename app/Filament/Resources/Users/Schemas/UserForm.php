<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('role')
                ->options([
                    'super_admin' => 'Super Admin',
                    'operator'    => 'Operator',
                ])
                ->required()
                ->live(),

            Select::make('opd_filter')
                ->label('OPD')
                ->options(
                    fn() =>
                    \App\Models\Opd::pluck('nama_opd', 'id_opd')
                )
                ->searchable()
                ->reactive()
                ->visible(fn($get) => $get('role') === 'operator'),


            Select::make('pegawai_id')
                ->label('Nama')
                ->options(function (callable $get) {
                    $opdId = $get('opd_filter');

                    if (!$opdId) {
                        return [];
                    }
                    return \App\Models\Pegawai::where('opd_id', $opdId)
                        ->pluck('nama', 'id_pegawai');
                })
                ->searchable()
                ->reactive()
                ->visible(
                    fn($get) =>
                    $get('role') === 'operator' &&
                        $get('opd_filter') &&
                        \App\Models\Pegawai::where('opd_id', $get('opd_filter'))->exists()
                )
                ->afterStateUpdated(function ($state, callable $set) {
                    $pegawai = \App\Models\Pegawai::find($state);
                    if ($pegawai) {
                        $set('name', $pegawai->nama);
                    }
                }),


            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->visible(
                    fn($get) =>
                    $get('role') !== 'operator' ||
                        !$get('pegawai_id')
                ),


           
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

         
            TextInput::make('password')
                ->password()
                ->required(fn($context) => $context === 'create')
                ->dehydrateStateUsing(fn($state) => bcrypt($state))
                ->dehydrated(fn($state) => filled($state)),
        ]);
    }
}
