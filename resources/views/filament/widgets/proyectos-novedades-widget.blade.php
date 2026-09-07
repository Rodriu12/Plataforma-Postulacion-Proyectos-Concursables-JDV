<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div style="display: flex; align-items: center; gap: 8px; color: #2563eb; font-weight: 600;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                Novedades: Nuevos Fondos Disponibles
            </div>
        </x-slot>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 16px;">
            @forelse ($proyectos as $proyecto)
                <div style="padding: 20px; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #f9fafb; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#ffffff'; this.style.borderColor='#d1d5db';" onmouseout="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#e5e7eb';">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-weight: bold; font-size: 16px; color: #111827; margin-bottom: 8px; line-height: 1.3;">
                            {{ $proyecto->nombre }}
                        </h3>
                        <p style="font-size: 14px; color: #4b5563; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            {{ $proyecto->descripcion ?? 'Ingresa para ver los requisitos y detalles de esta convocatoria.' }}
                        </p>
                    </div>
                    
                    <x-filament::button
                        href="{{ \App\Filament\Resources\ProyectoExternos\ProyectoExternoResource::getUrl('view', ['record' => $proyecto->id]) }}"
                        tag="a"
                        size="sm"
                        outlined
                        style="width: 100%; justify-content: center;"
                    >
                        Ver detalles de postulación
                    </x-filament::button>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 30px; color: #6b7280;">
                    No hay nuevos fondos registrados por el momento.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
