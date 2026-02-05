<?php

namespace App\Filament\Resources\Kegiatans;

use App\Filament\Resources\Kegiatans\Pages\CreateKegiatan;
use App\Filament\Resources\Kegiatans\Pages\EditKegiatan;
use App\Filament\Resources\Kegiatans\RelationManagers\KehadiranRelationManager;
use App\Filament\Resources\Kegiatans\Pages\ListKegiatans;
use App\Filament\Resources\Kegiatans\Pages\ViewKegiatan;
use App\Filament\Resources\Kegiatans\Schemas\KegiatanForm;
use App\Filament\Resources\Kegiatans\Tables\KegiatansTable;
use App\Models\Kegiatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KegiatanResource extends Resource
{
    protected static ?string $model = Kegiatan::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';

    protected static ?string $recordTitleAttribute = 'nama_kegiatan';

    public static function form(Schema $schema): Schema
    {
        return KegiatanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KegiatansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // KehadiranRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListKegiatans::route('/'),
            'create' => CreateKegiatan::route('/create'),
            'view'   => ViewKegiatan::route('/{record}'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();
        if ($user->role === 'operator') {
            $query->where('opd_id', $user->pegawai->opd_id);
        }

        return $query;
    }
}
