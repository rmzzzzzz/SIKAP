<?php

namespace App\Filament\Resources\Pangkats;

use App\Filament\Resources\Pangkats\Pages\CreatePangkat;
use App\Filament\Resources\Pangkats\Pages\EditPangkat;
use App\Filament\Resources\Pangkats\Pages\ListPangkats;
use App\Filament\Resources\Pangkats\Schemas\PangkatForm;
use App\Filament\Resources\Pangkats\Tables\PangkatsTable;
use App\Models\Pangkat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PangkatResource extends Resource
{
    protected static ?string $model = Pangkat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'pangkat';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Data';

    protected static ?string $navigationLabel = 'Pangkat';
    public static function form(Schema $schema): Schema
    {
        return PangkatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PangkatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPangkats::route('/'),
            'create' => CreatePangkat::route('/create'),
            'edit' => EditPangkat::route('/{record}/edit'),
        ];
    }
}
