<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Widgets\PendaftaranList;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Awcodes\Overlook\OverlookPlugin;
use App\Models\Pendaftaran;
use App\Models\Penerimaan;
use Awcodes\Overlook\Widgets\OverlookWidget;
use App\Filament\Resources\Widgets\AdvancedStatsOverviewWidget;
use App\Filament\Widgets\AdvancedStatsOverviewWidget as WidgetsAdvancedStatsOverviewWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Navigation\NavigationItem;
use App\Filament\Widgets\PendaftaranChart;
use App\Filament\Widgets\PenerimaanChart;

class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->colors([
                // 'danger' => '#1A1A19',
                // 'success' => '#859F3D',
                // 'info' => '#F6FCDF',
                // 'primary' => '#31511E',
                'primary' => Color::Blue
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationItems([
                NavigationItem::make('SMK Media Informatika')
                ->url('https://smkmediainformatika.sch.id/')
                ->icon('heroicon-o-academic-cap')
                ->group('External')
                ->sort(2),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                OverlookWidget::class,
                WidgetsAdvancedStatsOverviewWidget::class,
                // PendaftaranList::class,
                PendaftaranChart::class,
                PenerimaanChart::class,

            ])
            ->middleware(middleware:[
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins([
                OverlookPlugin::make()
                    ->sort(2)
                    ->columns([
                        'md' => 3,
                    ])
                    ->abbreviateCount(false)
                    ->alphabetical()
                ])
            //     ->plugin(
            //     \Hasnayeen\Themes\ThemesPlugin::make()
            // )
            ->favicon(asset('images/metik.png'))
            ->databaseNotifications()
            ->navigationGroups([
                'Form PPDB',
                'Manajemen Data',
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ]);
    }
}
