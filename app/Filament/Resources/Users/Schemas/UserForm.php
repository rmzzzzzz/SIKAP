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
        return $schema
            ->components([
                Select::make('role')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'operator'    => 'Operator',
                    ])
                    ->required()
                    ->live(),

                Select::make('opd_id')
                    ->relationship('opd', 'nama_opd')
                    ->visible(fn($get) => $get('role') === 'operator')
                    ->required(fn($get) => $get('role') === 'operator'),

                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true),

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
