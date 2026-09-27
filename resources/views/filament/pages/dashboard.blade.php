<x-filament-panels::page>
    <div id="contenido-lectura" style="display: flex; flex-direction: column; gap: 40px;">
        @foreach (\Filament\Facades\Filament::getWidgets() as $widget)
            @livewire($widget)
        @endforeach
    </div>
</x-filament-panels::page>
