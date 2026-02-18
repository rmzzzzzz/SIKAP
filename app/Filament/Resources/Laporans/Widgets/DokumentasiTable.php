<?php
namespace App\Filament\Resources\Laporans\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use App\Models\Dokumentasi;

class DokumentasiTable extends TableWidget
{
    public ?int $laporanId = null;

    protected static ?string $heading = 'Dokumentasi Kegiatan';

    protected static ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Dokumentasi::query()
                    ->where('laporan_id', $this->laporanId)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Foto')
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->path))
                    ->height(150)
                    ->openUrlInNewTab(),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->paginated(false);
    }
}
