<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use Carbon\Carbon;

class KehadiranChart extends ChartWidget
{
  protected ?string $heading = 'Kehadiran per Bulan';

    protected function getData(): array
    {
        $user  = Auth::user();
        $opdId = $user->pegawai?->opd_id;

        $query = Kegiatan::query()->where('buat_kehadiran', '1');

        if (in_array($user->role, ['operator', 'pimpinan'])) {
            $query->where('opd_id', $opdId);
        }

        $data = $query
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->whereYear('tanggal', now()->year)
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
                    'label' => 'Kehadiran',
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
