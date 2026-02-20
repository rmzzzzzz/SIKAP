<?php

namespace App\Filament\Resources\Laporans;

use App\Filament\Resources\Laporans\Pages\CreateLaporan;
use App\Filament\Resources\Laporans\Pages\ViewLaporan;
use App\Filament\Resources\Laporans\Pages\EditLaporan;
use App\Filament\Resources\Laporans\Pages\ListLaporans;
use App\Filament\Resources\Laporans\Schemas\LaporanForm;
use App\Filament\Resources\Laporans\Tables\LaporansTable;
use App\Models\Laporan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';
    protected static ?string $recordTitleAttribute = 'kegiatan.nama_kegiatan';
    protected static ?string $navigationLabel = 'Laporan';

    public static function form(Schema $schema): Schema
    {
        return LaporanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporansTable::configure($table);
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
            'index' => ListLaporans::route('/'),
            'create' => CreateLaporan::route('/create'),
            'view' => ViewLaporan::route('/{record}'),
            // 'edit' => EditLaporan::route('/{record}/edit'),
        ];
    }
     public static function getEloquentQuery(): Builder
    {
        
        $query = parent::getEloquentQuery();
        
        $user = Auth::user();

      
        if ($user->role === 'pimpinan') {
            $query->where('opd_id', $user->pegawai->opd_id);
        }

        return $query;
    }
    
}
