<x-filament-widgets::widget class="proyectos-novedades-widget">
    <x-filament::section class="proyectos-novedades-section">
        <x-slot name="heading">
            <div class="novedades-title">
                <div class="novedades-icon">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                        </path>
                    </svg>
                </div>
                Novedades: Nuevos Fondos Disponibles
            </div>
        </x-slot>

        <div class="proyectos-novedades-grid">
            @forelse($proyectos as $proyecto)
                <div class="fund-card">
                    <div>
                        <div class="fund-badge-wrap">
                            <span class="fund-badge">
                                <span class="pulse-dot"></span>
                                ABIERTO
                            </span>
                        </div>

                        <div class="fund-content">
                            <h4 class="fund-title" :style="leyendo ? 'font-size: 22px; font-weight: 800;' : 'font-size: 14px; font-weight: 700;'">
                                {{ $proyecto->tipo ?? 'Regional' }}
                                {{ $proyecto->organismo ?? ($proyecto->nombre_institucion ?? '') }}
                            </h4>

                            <p class="fund-name" :style="leyendo ? 'font-size: 18px;' : 'font-size: 13px;'">
                                {{ $proyecto->nombre }}
                            </p>

                            <p class="fund-description" :style="leyendo ? 'font-size: 16px;' : 'font-size: 12px;'">
                                Beneficiarios/as:
                                {{ $proyecto->beneficiarios ?? ($proyecto->descripcion ?? 'Persona Jurídica') }}
                            </p>
                        </div>
                    </div>

                    <div class="fund-link-wrap">
                        <a href="{{ $proyecto->url_fuente }}" target="_blank" rel="noopener noreferrer"
                            class="shimmer-btn fund-link"
                            :style="leyendo ? 'font-size: 16px; padding: 14px 20px;' : 'font-size: 13px; padding: 10px 16px;'">
                            Ver enlace oficial del fondo
                            <span class="fund-link-arrow">›</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="fund-empty-state">
                    No hay nuevas convocatorias de fondos disponibles en este momento.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>