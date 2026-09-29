<div
    x-data="narradorAccesibilidad()"
    data-accesibilidad-omitir
    style="display: flex; justify-content: flex-end; padding: 12px 24px 0 24px;"
>
    <button
        @click="toggleLectura()"
        type="button"
        aria-label="Narrador de voz"
        style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 9999px; border: 1px solid #bfdbfe; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
        x-bind:style="leyendo ? 'background-color: #ffe4e6; color: #be123c; border-color: #fda4af;' : 'background-color: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;'"
    >
        <svg x-show="!leyendo" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
        </svg>

        <svg x-show="leyendo" style="display: none; width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
        </svg>

        <span x-text="leyendo ? 'Detener lectura' : 'Narrador de voz'"></span>
    </button>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('narradorAccesibilidad', () => ({
            leyendo: false,
            sintesis: null,
            cola: [],
            indice: 0,

            init() {
                this.sintesis = window.speechSynthesis ?? null;

                document.addEventListener('livewire:navigated', () => {
                    this.detenerLectura();
                });
            },

            detenerLectura() {
                if (this.sintesis) {
                    this.sintesis.cancel();
                }

                this.cola = [];
                this.indice = 0;
                this.leyendo = false;
            },

            obtenerContenedor() {
                return document.getElementById('fi-main-content')
                    || document.querySelector('main[role="main"]')
                    || document.querySelector('main');
            },

            prepararTexto() {
                const contenedor = this.obtenerContenedor();

                if (!contenedor) {
                    return '';
                }

                const clon = contenedor.cloneNode(true);

                clon.querySelectorAll(
                    '[data-accesibilidad-omitir], script, style, noscript, [aria-hidden="true"]'
                ).forEach((el) => el.remove());

                return (clon.innerText || clon.textContent || '')
                    .replace(/Ver enlace oficial del fondo/gi, '')
                    .replace(/\bABIERTO\b/gi, 'Estado: Abierto.')
                    .replace(/\s+/g, ' ')
                    .trim();
            },

            dividirTexto(texto, maxCaracteres = 260) {
                const oraciones = texto.match(/[^.!?]+[.!?]+|[^.!?]+$/g) || [];
                const partes = [];
                let actual = '';

                oraciones.forEach((oracion) => {
                    const fragmento = oracion.trim();

                    if (!fragmento) {
                        return;
                    }

                    if ((actual + ' ' + fragmento).trim().length <= maxCaracteres) {
                        actual = (actual + ' ' + fragmento).trim();
                        return;
                    }

                    if (actual) {
                        partes.push(actual);
                    }

                    if (fragmento.length > maxCaracteres) {
                        const palabras = fragmento.split(/\s+/);
                        actual = '';

                        palabras.forEach((palabra) => {
                            if ((actual + ' ' + palabra).trim().length <= maxCaracteres) {
                                actual = (actual + ' ' + palabra).trim();
                            } else {
                                if (actual) {
                                    partes.push(actual);
                                }
                                actual = palabra;
                            }
                        });
                    } else {
                        actual = fragmento;
                    }
                });

                if (actual) {
                    partes.push(actual);
                }

                return partes;
            },

            hablarSiguiente() {
                if (!this.leyendo || !this.sintesis) {
                    return;
                }

                if (this.indice >= this.cola.length) {
                    this.detenerLectura();
                    return;
                }

                const mensaje = new SpeechSynthesisUtterance(this.cola[this.indice]);

                mensaje.lang = 'es-CL';
                mensaje.rate = 0.85;
                mensaje.pitch = 1;

                mensaje.onend = () => {
                    if (!this.leyendo) {
                        return;
                    }

                    this.indice++;
                    this.hablarSiguiente();
                };

                mensaje.onerror = () => {
                    this.detenerLectura();
                };

                this.sintesis.speak(mensaje);
            },

            toggleLectura() {
                if (this.leyendo) {
                    this.detenerLectura();
                    return;
                }

                if (!this.sintesis || typeof SpeechSynthesisUtterance === 'undefined') {
                    console.warn('El navegador no soporta síntesis de voz.');
                    return;
                }

                this.sintesis.cancel();

                const texto = this.prepararTexto();

                if (!texto) {
                    console.warn('No se encontró contenido para narrar.');
                    return;
                }

                this.cola = this.dividirTexto(texto);
                this.indice = 0;
                this.leyendo = this.cola.length > 0;

                if (this.leyendo) {
                    this.hablarSiguiente();
                }
            },
        }));
    });
</script>
