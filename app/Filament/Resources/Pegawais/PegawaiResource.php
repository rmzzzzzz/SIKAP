<?php

namespace App\Filament\Resources\Pegawais;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Pegawais\Pages\CreatePegawai;
use App\Filament\Resources\Pegawais\Pages\EditPegawai;
use App\Filament\Resources\Pegawais\Pages\ListPegawais;
use App\Filament\Resources\Pegawais\Schemas\PegawaiForm;
use App\Filament\Resources\Pegawais\Tables\PegawaisTable;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';
    protected static ?string $recordTitleAttribute = 'nama_Pegawai';
    protected static ?string $navigationLabel = 'Pegawai';

    public static function form(Schema $schema): Schema
    {
        return PegawaiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PegawaisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user  = Auth::user();

         if ($user->role === 'operator') {
        $query->where('opd_id', $user->pegawai->opd_id);
    }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPegawais::route('/'),
            'create' => CreatePegawai::route('/create'),
            'edit' => EditPegawai::route('/{record}/edit'),
        ];
    }
}
