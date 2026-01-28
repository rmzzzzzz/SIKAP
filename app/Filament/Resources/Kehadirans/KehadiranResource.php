<?php

namespace App\Filament\Resources\Kehadirans;

use App\Filament\Resources\Kehadirans\Pages\CreateKehadiran;
use App\Filament\Resources\Kehadirans\Pages\EditKehadiran;
use App\Filament\Resources\Kehadirans\Pages\ListKehadirans;
use App\Filament\Resources\Kehadirans\Schemas\KehadiranForm;
use App\Filament\Resources\Kehadirans\Tables\KehadiransTable;
use App\Models\Kehadiran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KehadiranResource extends Resource
{
    protected static ?string $model = Kehadiran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'kehadiran';

    public static function form(Schema $schema): Schema
    {
        return KehadiranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KehadiransTable::configure($table);
    }
  public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user  = Auth::user();

        if ($user->role === 'operator') {
            $query->whereHas('kegiatan', function ($q) use ($user) {
                $q->where('opd_id', $user->pegawai->opd_id);
            });
        }

        return $query;
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
            'create' => CreateKehadiran::route('/create'),
            'edit' => EditKehadiran::route('/{record}/edit'),
        ];
    }
}
