<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div
                style="display: flex; align-items: center; gap: 8px; color: #1e40af; font-weight: 700; font-size: 18px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; flex-shrink: 0;" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Proyectos en Terreno: Logros de nuestras Organizaciones
            </div>
        </x-slot>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 16px;">
            @forelse ($proyectos as $proyecto)
                <div
                    style="border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; background-color: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column;">

                    <div style="width: 100%; height: 180px; background-color: #f3f4f6; overflow: hidden;">
                        <img src="{{ asset('storage/' . $proyecto->imagen_terreno) }}" alt="{{ $proyecto->nombre }}"
                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                            onmouseover="this.style.transform='scale(1.05)'"
                            onmouseout="this.style.transform='scale(1)'">
                    </div>

                    <div
                        style="padding: 16px; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                        <div>
                            <span
                                style="display: inline-block; padding: 2px 8px; background-color: #dbeafe; color: #1e40af; font-size: 11px; font-weight: bold; border-radius: 9999px; margin-bottom: 8px;">
                                Proyecto Aprobado
                            </span>
                            <h3
                                style="font-weight: bold; font-size: 16px; color: #111827; margin-bottom: 6px; line-height: 1.3;">
                                {{ $proyecto->nombre }}
                            </h3>
                            <p
                                style="font-size: 13px; color: #4b5563; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $proyecto->descripcion ?? 'Presentación y ejecución exitosa del fondo en la comunidad.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 30px; color: #6b7280; font-size: 14px;">
                    Aún no hay registros fotográficos de proyectos en terreno publicados. ¡Sube la primera foto desde el
                    panel de edición!
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
