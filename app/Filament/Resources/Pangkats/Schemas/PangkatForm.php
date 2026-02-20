<?php

namespace App\Filament\Resources\Pangkats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PangkatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_pangkat')
                    ->required(),
            ]);
    }
}
