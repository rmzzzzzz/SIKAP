<?php

namespace App\Filament\Resources\Kehadirans;

use App\Filament\Resources\Kehadirans\Pages\ListKehadirans;
use App\Filament\Resources\Kehadirans\Pages\ViewKehadiran;
use App\Filament\Resources\Kehadirans\Tables\KehadiransTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Kegiatan;
use UnitEnum;

class KehadiranResource extends Resource
{


    protected static ?string $model = Kegiatan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_kegiatan';
    protected static ?string $navigationLabel = 'Kehadiran';
    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';


    public static function table(Table $table): Table
    {
        return KehadiransTable::configure($table);
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
            'index' => ListKehadirans::route('/'),
            'view' => ViewKehadiran::route('/{record}'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
        ->where('buat_kehadiran', true)
            // ->whereHas('kehadiran')
            ->withCount([
                'kehadiran as total_hadir'
            ]);

        // hanya OPD sendiri
        if (Auth::user()->role === 'operator') {
            $query->where('kegiatan.opd_id', Auth::user()->pegawai->opd_id);
        }

        return $query;
    }
}
