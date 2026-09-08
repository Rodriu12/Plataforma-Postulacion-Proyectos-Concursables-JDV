<x-filament-panels::page>
   <div>
    <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;" x-data="narradorAccesibilidad()">
        <button 
            @click="toggleLectura()" 
            type="button"
            style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 9999px; border: 1px solid #bfdbfe; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
            x-bind:style="leyendo ? 'background-color: #ffe4e6; color: #be123c; border-color: #fda4af;' : 'background-color: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;'"
        >
            <svg x-show="!leyendo" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
            </svg>
            
            <svg x-show="leyendo" style="display: none; width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
            </svg>

            <span x-text="leyendo ? 'Detener lectura' : 'Escuchar pantalla'"></span>
        </button>
    </div>

    <div id="contenido-lectura" style="display: flex; flex-direction: column; gap: 40px;">
        @foreach (\Filament\Facades\Filament::getWidgets() as $widget)
            @livewire($widget)
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('narradorAccesibilidad', () => ({
            leyendo: false,
            sintesis: window.speechSynthesis,
            
            toggleLectura() {
                if (this.leyendo) {
                    this.sintesis.cancel();
                    this.leyendo = false;
                    return;
                }

                let contenedor = document.getElementById('contenido-lectura');
                let textoLimpio = contenedor.innerText
                    .replace(/Ver enlace oficial del fondo/g, '') 
                    .replace(/ABIERTO/g, 'Estado: Abierto. '); 
                
                let mensaje = new SpeechSynthesisUtterance(textoLimpio);
                
                mensaje.lang = 'es-CL'; 
                mensaje.rate = 0.85;    
                mensaje.pitch = 1;      
                
                mensaje.onend = () => { 
                    this.leyendo = false; 
                };
                
                this.sintesis.speak(mensaje);
                this.leyendo = true;
            }
        }))
    })
</script>
</x-filament-panels::page>
