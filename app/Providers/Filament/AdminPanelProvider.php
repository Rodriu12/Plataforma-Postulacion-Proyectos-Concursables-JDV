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
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn() => Blade::render('
                <!-- Google Fonts: Plus Jakarta Sans -->
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
                
                <style>
                    aside.fi-sidebar, .fi-sidebar-header {
                        background-color: #0f172a !important;
                        border-right: none !important;
                    }

                    aside.fi-sidebar .fi-sidebar-item-label, 
                    aside.fi-sidebar .fi-sidebar-item-icon,
                    aside.fi-sidebar .fi-logo {
                        color: #94a3b8 !important;
                    }

                    aside.fi-sidebar .fi-sidebar-item-button:hover {
                        background-color: #1e293b !important;
                    }
                    aside.fi-sidebar .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                    aside.fi-sidebar .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        color: #f1f5f9 !important;
                    }

                    aside.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: #2563eb !important;
                        border-radius: 8px !important;
                    }
                    aside.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-label,
                    aside.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon {
                        color: #ffffff !important;
                        font-weight: 600 !important;
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
