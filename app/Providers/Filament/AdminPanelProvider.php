<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn() => Blade::render('
                <!-- Google Fonts: Plus Jakarta Sans -->
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
                
                <style>
                    body, .fi-body {
                        font-family: "Plus Jakarta Sans", sans-serif !important;
                    }

                    .fi-body {
                        background-image:
                            radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.03) 0px, transparent 50%),
                            radial-gradient(at 100% 0%, rgba(245, 158, 11, 0.02) 0px, transparent 50%),
                            radial-gradient(at 50% 100%, rgba(37, 99, 235, 0.02) 0px, transparent 50%);
                    }

                    .fund-card {
                        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
                    }
                    .fund-card:hover {
                        transform: translateY(-4px);
                        border-color: rgba(59, 130, 246, 0.4);
                        box-shadow: 0 16px 32px -8px rgba(37, 99, 235, 0.1), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
                    }

                    @keyframes subtlePulse {
                        0%, 100% { opacity: 1; transform: scale(1); }
                        50% { opacity: 0.45; transform: scale(1.35); }
                    }
                    .pulse-dot {
                        animation: subtlePulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                    }
                </style>
            ')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
