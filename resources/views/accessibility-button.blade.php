<div style="display: flex; justify-content: flex-end; width: 100%; margin-bottom: 20px;">
    <button x-data="{
        agrandar: localStorage.getItem('agrandarTexto') === 'true',
        toggle() {
            this.agrandar = !this.agrandar;
            localStorage.setItem('agrandarTexto', this.agrandar);
            this.aplicar();
        },
        aplicar() {
            // Cambiamos de 115% a 145% para que el aumento sea muy notorio y coincida con tu imagen
            document.documentElement.style.fontSize = this.agrandar ? '145%' : '100%';
        }
    }" x-init="aplicar()" @click="toggle()" type="button"
        x-bind:style="agrandar
            ?
            'display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; font-size: 14px; font-weight: 700; border-radius: 9999px; background-color: #ffffff; color: #1d4ed8; border: 2px solid #2563eb; cursor: pointer; transition: all 0.2s;' :
            'display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; font-size: 13px; font-weight: 500; border-radius: 9999px; background-color: #ffffff; color: #374151; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); cursor: pointer; transition: all 0.2s;'"
        :title="agrandar ? 'Volver a la vista normal' : 'Activar accesibilidad de lectura'">

        <span
            style="width: 16px; height: 16px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;"
            x-bind:style="agrandar ? 'color: #1d4ed8;' : 'color: #2563eb;'">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </span>

        <span x-text="agrandar ? 'Volver a la vista normal' : 'Accesibilidad de lectura'"></span>
    </button>
</div>
