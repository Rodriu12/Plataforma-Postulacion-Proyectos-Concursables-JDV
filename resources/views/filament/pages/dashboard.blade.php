<x-filament-panels::page>
    <div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
        <form method="POST" action="{{ route('proyectos.externos.actualizar') }}" style="margin: 0;">
            @csrf
            <button
                type="submit"
                style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 9999px; border: 1px solid #bfdbfe; font-size: 14px; font-weight: 600; cursor: pointer; background: #eff6ff; color: #1d4ed8; transition: background 0.15s, box-shadow 0.15s; box-shadow: none;"
                onmouseover="this.style.background='#dbeafe'; this.style.boxShadow='0 2px 8px rgba(37,99,235,0.15)';"
                onmouseout="this.style.background='#eff6ff'; this.style.boxShadow='none';"
            >
                <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 0113.65-5.65M20 12a8 8 0 01-13.65 5.65"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 4v6h-6"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20v-6h6"/>
                </svg>
                <span>Actualizar</span>
            </button>
        </form>
    </div>

    @if (session('status'))
        <div style="margin-bottom: 16px; padding: 10px 14px; border-radius: 10px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 13px; font-weight: 600;">
            {{ session('status') }}
        </div>
    @endif

    <div id="contenido-lectura" style="display: flex; flex-direction: column; gap: 40px;">
        @foreach (\Filament\Facades\Filament::getWidgets() as $widget)
            @livewire($widget)
        @endforeach
    </div>
</x-filament-panels::page>
