<?php

namespace App\Filament\Resources\Opds;

use App\Filament\Resources\Opds\Pages\CreateOpd;
use App\Filament\Resources\Opds\Pages\EditOpd;
use App\Filament\Resources\Opds\Pages\ListOpds;
use App\Filament\Resources\Opds\Schemas\OpdForm;
use App\Filament\Resources\Opds\Tables\OpdsTable;
use App\Models\Opd;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Support\Facades\Auth;

class OpdResource extends Resource
{
    protected static ?string $model = Opd::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Data';

    protected static ?string $recordTitleAttribute = 'nama_opd';
    protected static ?string $navigationLabel = 'OPD';
    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user && $user->role === 'super_admin';
    }

    public static function form(Schema $schema): Schema
    {
        return OpdForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OpdsTable::configure($table);
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
            'index' => ListOpds::route('/'),
            'create' => CreateOpd::route('/create'),
            'edit' => EditOpd::route('/{record}/edit'),
        ];
    }
}
