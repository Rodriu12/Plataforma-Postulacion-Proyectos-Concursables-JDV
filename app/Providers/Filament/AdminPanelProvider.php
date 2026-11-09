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
use Filament\Navigation\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Vecindar')
            ->brandLogo(fn () => view('filament.brand-logo'))
            ->brandLogoHeight('2.4rem')
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->passwordReset()
            ->profile(\App\Filament\Pages\Auth\CustomEditProfile::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->darkMode(false)
            ->themeSwitcher(false)
            ->colors([
                'primary' => '#2563eb',
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->userMenuItems([
                'logout' => MenuItem::make()
                    ->label('Cerrar sesión')
                    ->url('/salir')
                    ->icon('heroicon-o-arrow-right-on-rectangle'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn () => view('filament.narrador-accesibilidad'),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn () => Blade::render(<<<'HTML'
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const injectLabel = () => {
                                document.querySelectorAll('input[type="checkbox"][aria-label]').forEach((checkbox) => {
                                    const ariaLabel = (checkbox.getAttribute('aria-label') || '').toLowerCase();
                                    const isBulkSelect = ariaLabel.includes('select all') || ariaLabel.includes('seleccionar todo') || ariaLabel.includes('bulk select page');

                                    if (! isBulkSelect) {
                                        return;
                                    }

                                    if (checkbox.parentElement?.classList.contains('fi-bulk-select-wrapper')) {
                                        return;
                                    }

                                    const existing = checkbox.parentElement?.querySelector('.fi-bulk-select-label');
                                    if (existing) {
                                        return;
                                    }

                                    const wrapper = document.createElement('span');
                                    wrapper.className = 'fi-bulk-select-wrapper';
                                    wrapper.style.display = 'inline-flex';
                                    wrapper.style.alignItems = 'center';
                                    wrapper.style.gap = '0.5rem';
                                    wrapper.style.lineHeight = '1.2';

                                    const label = document.createElement('span');
                                    label.className = 'fi-bulk-select-label';
                                    label.textContent = 'Seleccionar todo';
                                    label.style.fontSize = '0.82rem';
                                    label.style.fontWeight = '600';
                                    label.style.color = '#10233c';
                                    label.style.whiteSpace = 'nowrap';

                                    checkbox.parentNode.insertBefore(wrapper, checkbox);
                                    wrapper.appendChild(checkbox);
                                    wrapper.appendChild(label);
                                });
                            };

                            injectLabel();

                            const observer = new MutationObserver(() => {
                                injectLabel();
                            });

                            observer.observe(document.body, {
                                childList: true,
                                subtree: true,
                            });
                        });
                    </script>
                HTML),
            )
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
