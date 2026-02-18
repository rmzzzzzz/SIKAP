<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\KegiatanChart;
use App\Filament\Widgets\KehadiranChart;
use App\Models\Kehadiran;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('dashboard')
            ->brandName(null)
            ->renderHook(
                'panels::topbar.start',
                fn() => new HtmlString('
        <div style="display:flex;align-items:center;gap:12px">
            <img src="' . asset('sumenep.png') . '" style="height:40px">
            <div style="line-height:1.2">
                <div style="font-weight:bold;font-size:18px">HADIR
                    <span style="font-size:12px;color:#666">
                       Sumenep
                    </span>
                </div>
                <div style="font-size:13px;color:#666">
                    Sistem Informasi Kehadiran Pegawai
                </div>
            </div>
        </div>
    ')
            )




            ->assets([])
            ->widgets([
                DashboardStats::class,
                KegiatanChart::class,
                KehadiranChart::class,
            ])
            ->login()
            ->colors([
                'primary' => Color::Teal, // basis hijau
                'gray' => Color::Stone,   // krem / abu hangat
            ])
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\Filament\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\Filament\Pages'
            )
            ->pages([
                Dashboard::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
