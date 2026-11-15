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
                            const tooltipStyle = document.createElement('style');
                            tooltipStyle.textContent = `
                                .fi-ta-selection-indicator-actions-ctn { display: none !important; }
                                .fi-bulk-select-tooltip {
                                    position: fixed; z-index: 9999; padding: 0.35rem 0.6rem; border-radius: 0.375rem;
                                    background: #fff; border: 1px solid #111827; color: #111827; font-size: 0.75rem;
                                    font-weight: 500; line-height: 1.25rem; white-space: nowrap;
                                    pointer-events: none; opacity: 0; visibility: hidden;
                                    transition: opacity 100ms ease;
                                }
                            `;
                            document.head.appendChild(tooltipStyle);

                            const bulkSelectTooltip = document.createElement('span');
                            bulkSelectTooltip.className = 'fi-bulk-select-tooltip';
                            bulkSelectTooltip.setAttribute('role', 'tooltip');
                            document.body.appendChild(bulkSelectTooltip);

                            let activeBulkCheckbox = null;
                            const updateBulkSelectTooltip = () => {
                                if (! activeBulkCheckbox) {
                                    return;
                                }

                                if (activeBulkCheckbox.classList.contains('fi-ta-record-checkbox')) {
                                    bulkSelectTooltip.textContent = 'Seleccionar';

                                    return;
                                }

                                bulkSelectTooltip.textContent = activeBulkCheckbox.checked ? 'Deseleccionar todo' : 'Seleccionar todo';
                            };
                            const positionBulkSelectTooltip = () => {
                                if (! activeBulkCheckbox) {
                                    return;
                                }

                                const bounds = activeBulkCheckbox.getBoundingClientRect();
                                const tooltipBounds = bulkSelectTooltip.getBoundingClientRect();
                                const left = Math.max(8, Math.min(
                                    bounds.left + (bounds.width / 2) - (tooltipBounds.width / 2),
                                    window.innerWidth - tooltipBounds.width - 8,
                                ));

                                bulkSelectTooltip.style.left = `${left}px`;
                                bulkSelectTooltip.style.top = `${bounds.bottom + 8}px`;
                            };
                            const showBulkSelectTooltip = (checkbox) => {
                                activeBulkCheckbox = checkbox;
                                updateBulkSelectTooltip();
                                bulkSelectTooltip.style.visibility = 'visible';
                                bulkSelectTooltip.style.opacity = '1';
                                positionBulkSelectTooltip();
                            };
                            const hideBulkSelectTooltip = (checkbox) => {
                                if (activeBulkCheckbox !== checkbox) {
                                    return;
                                }

                                activeBulkCheckbox = null;
                                bulkSelectTooltip.style.opacity = '0';
                                bulkSelectTooltip.style.visibility = 'hidden';
                            };

                            const updateBulkSelectTooltips = () => {
                                document.querySelectorAll('input.fi-ta-page-checkbox, input.fi-ta-record-checkbox, input[type="checkbox"][aria-label]').forEach((checkbox) => {
                                    const ariaLabel = (checkbox.getAttribute('aria-label') || '').toLowerCase();
                                    const isBulkSelect = checkbox.classList.contains('fi-ta-page-checkbox')
                                        || checkbox.classList.contains('fi-ta-record-checkbox')
                                        || ariaLabel.includes('select all')
                                        || ariaLabel.includes('bulk select page')
                                        || ariaLabel.includes('seleccionar/deseleccionar todos');

                                    if (! isBulkSelect) {
                                        return;
                                    }

                                    if (! checkbox.dataset.bulkSelectTooltipBound) {
                                        checkbox.dataset.bulkSelectTooltipBound = 'true';
                                        checkbox.addEventListener('mouseenter', () => showBulkSelectTooltip(checkbox));
                                        checkbox.addEventListener('mouseleave', () => hideBulkSelectTooltip(checkbox));
                                        checkbox.addEventListener('focus', () => showBulkSelectTooltip(checkbox));
                                        checkbox.addEventListener('blur', () => hideBulkSelectTooltip(checkbox));
                                        checkbox.addEventListener('change', updateBulkSelectTooltip);
                                    }
                                });
                            };

                            const updateComprobantePreviews = () => {
                                if (! window.Alpine) {
                                    return;
                                }

                                document.querySelectorAll('[data-comprobante-upload]').forEach((upload) => {
                                    const component = window.Alpine.$data(upload);
                                    const pond = component?.pond;

                                    if (! pond) {
                                        return;
                                    }

                                    if (upload._comprobantePreviewPond === pond) {
                                        upload._renderComprobantePreview?.(pond.getFiles()[0] || null);
                                        return;
                                    }

                                    const renderPreview = (file = null) => {
                                        const preview = upload.closest('form')?.querySelector('[data-comprobante-preview]');
                                        const frame = preview?.querySelector('[data-comprobante-preview-frame]');
                                        const emptyMessage = preview?.querySelector('[data-comprobante-preview-empty]');

                                        if (! preview || ! frame || ! emptyMessage) {
                                            return;
                                        }

                                        let url = null;

                                        if (file) {
                                            const isPdf = file.file?.type === 'application/pdf'
                                                || file.file?.name?.toLowerCase().endsWith('.pdf');

                                            if (isPdf && file.origin === window.FilePond?.FileOrigin?.LOCAL) {
                                                if (upload._comprobantePreviewObjectUrl) {
                                                    URL.revokeObjectURL(upload._comprobantePreviewObjectUrl);
                                                    upload._comprobantePreviewObjectUrl = null;
                                                    upload._comprobantePreviewFileId = null;
                                                }

                                                url = file.getMetadata('openableUrl') || file.source;
                                            } else if (isPdf && file.file instanceof Blob) {
                                                if (upload._comprobantePreviewFileId !== file.id) {
                                                    if (upload._comprobantePreviewObjectUrl) {
                                                        URL.revokeObjectURL(upload._comprobantePreviewObjectUrl);
                                                    }

                                                    upload._comprobantePreviewObjectUrl = URL.createObjectURL(file.file);
                                                    upload._comprobantePreviewFileId = file.id;
                                                }

                                                url = upload._comprobantePreviewObjectUrl;
                                            } else if (upload._comprobantePreviewObjectUrl) {
                                                URL.revokeObjectURL(upload._comprobantePreviewObjectUrl);
                                                upload._comprobantePreviewObjectUrl = null;
                                                upload._comprobantePreviewFileId = null;
                                            }
                                        } else if (upload._comprobantePreviewObjectUrl) {
                                            URL.revokeObjectURL(upload._comprobantePreviewObjectUrl);
                                            upload._comprobantePreviewObjectUrl = null;
                                            upload._comprobantePreviewFileId = null;
                                        }

                                        if (url) {
                                            frame.src = url;
                                            frame.classList.remove('hidden');
                                            emptyMessage.classList.add('hidden');
                                        } else {
                                            frame.removeAttribute('src');
                                            frame.classList.add('hidden');
                                            emptyMessage.classList.remove('hidden');
                                        }
                                    };

                                    upload._comprobantePreviewPond = pond;
                                    upload._renderComprobantePreview = renderPreview;

                                    const refreshPreview = () => renderPreview(pond.getFiles()[0] || null);
                                    pond.on('addfile', (error) => {
                                        if (! error) {
                                            refreshPreview();
                                        }
                                    });
                                    pond.on('removefile', refreshPreview);
                                    pond.on('initfile', refreshPreview);
                                    refreshPreview();
                                });
                            };

                            const updateInterface = () => {
                                updateBulkSelectTooltips();
                                updateComprobantePreviews();
                            };

                            window.addEventListener('resize', positionBulkSelectTooltip);
                            window.addEventListener('scroll', positionBulkSelectTooltip, true);
                            updateInterface();

                            const observer = new MutationObserver(() => {
                                updateInterface();
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
