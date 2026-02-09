<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use Carbon\Carbon;

class KegiatanChart extends ChartWidget
{
  protected ?string $heading = 'Kegiatan per Bulan';

    protected function getData(): array
    {
        $user  = Auth::user();
        $opdId = $user->pegawai?->opd_id;

        $query = Kegiatan::query();

        if (in_array($user->role, ['operator', 'pimpinan'])) {
            $query->where('opd_id', $opdId);
        }

        $data = $query
            ->selectRaw('MONTH(waktu) as bulan, COUNT(*) as total')
            ->whereYear('waktu', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $labels = [];
        $values = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->translatedFormat('F');
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kegiatan',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
