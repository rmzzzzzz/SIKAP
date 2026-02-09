<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Laporan;
use App\Models\Pegawai;
use App\Models\Opd;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user  = Auth::user();
        $role  = $user->role;
        $opdId = $user->pegawai?->opd_id;

        // ===== BASE QUERY =====
        $kegiatan = Kegiatan::query();
        $laporan  = Laporan::query();
        $pegawai  = Pegawai::query();
        $opd      = Opd::query();

        // ===== FILTER OPD UNTUK OPERATOR & PIMPINAN =====
        if (in_array($role, ['operator', 'pimpinan'])) {
            $kegiatan->where('opd_id', $opdId);
            $laporan->where('opd_id', $opdId);
            $pegawai->where('opd_id', $opdId);
        }

        $stats = [
            Stat::make('Kegiatan', $kegiatan->count())
                ->icon('heroicon-o-calendar-days')
                ->color('primary'),

            Stat::make(
                'Laporan Menunggu',
                (clone $laporan)->where('status_persetujuan', 'menunggu')->count()
            )
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make(
                'Laporan Disetujui',
                (clone $laporan)->where('status_persetujuan', 'disetujui')->count()
            )
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];

        // ===== TAMPILKAN PEGAWAI =====
        if (in_array($role, ['super_admin', 'operator'])) {
            $stats[] = Stat::make('Pegawai', $pegawai->count())
                ->icon('heroicon-o-users')
                ->color('info');
        }

        // ===== KHUSUS SUPER ADMIN =====
        if ($role === 'super_admin') {
            $stats[] = Stat::make('OPD', $opd->count())
                ->icon('heroicon-o-building-office')
                ->color('gray');
        }

        return $stats;
    }
}
