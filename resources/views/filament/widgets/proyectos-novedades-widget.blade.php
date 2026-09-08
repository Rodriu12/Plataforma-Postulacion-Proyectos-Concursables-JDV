<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div
                style="display: flex; align-items: center; gap: 10px; color: #1d4ed8; font-weight: 700; font-size: 18px;">
                <div
                    style="width: 28px; height: 28px; border-radius: 8px; background-color: #eff6ff; display: flex; align-items: center; justify-content: center; color: #2563eb;">
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

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 12px;">
            @forelse($proyectos as $proyecto)
                <div class="fund-card"
                    style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.04);">
                    <div>
                        <div style="margin-bottom: 12px;">
                            <span
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; text-transform: uppercase; letter-spacing: 0.05em;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #2563eb;"
                                    class="pulse-dot"></span>
                                ABIERTO
                            </span>
                        </div>

                        <div style="space-y: 8px;">
                            <h4
                                style="font-size: 14px; font-weight: 700; color: #0f172a; line-height: 1.4; margin: 0 0 6px 0;">
                                {{ $proyecto->tipo ?? 'Regional' }}
                                {{ $proyecto->organismo ?? ($proyecto->nombre_institucion ?? '') }}
                            </h4>

                            <p
                                style="font-size: 13px; font-weight: 600; color: #334155; line-height: 1.4; margin: 0 0 6px 0;">
                                {{ $proyecto->nombre }}
                            </p>

                            <p
                                style="font-size: 12px; color: #64748b; font-weight: 500; line-height: 1.4; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                Beneficiarios/as:
                                {{ $proyecto->beneficiarios ?? ($proyecto->descripcion ?? 'Persona Jurídica') }}
                            </p>
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                        <a href="{{ $proyecto->url_fuente }}" target="_blank" rel="noopener noreferrer"
                            class="shimmer-btn"
                            style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 16px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; font-weight: 600; color: #1d4ed8; text-decoration: none; transition: all 0.2s;"
                            onmouseover="this.style.backgroundColor='#eff6ff'; this.style.borderColor='#93c5fd';"
                            onmouseout="this.style.backgroundColor='#f8fafc'; this.style.borderColor='#cbd5e1';">
                            Ver enlace oficial del fondo
                            <span style="font-weight: 700; font-size: 14px;">›</span>
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #64748b; font-size: 14px;">
                    No hay nuevas convocatorias de fondos disponibles en este momento.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
