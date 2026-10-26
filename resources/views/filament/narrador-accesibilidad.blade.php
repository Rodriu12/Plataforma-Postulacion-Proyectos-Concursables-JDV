<div
    x-data="accesibilidadPanel()"
    data-accesibilidad-omitir
    style="display:flex; justify-content:flex-end; padding:10px 20px 0; position:relative; z-index:50;"
>
    <button
        @click="abierto = !abierto"
        @keydown.escape.window="abierto = false"
        type="button"
        :aria-expanded="abierto"
        aria-haspopup="true"
        style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:9999px; border:1px solid #bfdbfe; font-size:14px; font-weight:600; cursor:pointer; background:#eff6ff; color:#1d4ed8; transition:background 0.15s, box-shadow 0.15s;"
        :style="abierto ? 'background:#dbeafe; box-shadow:0 2px 8px rgba(37,99,235,0.15);' : ''"
    >
        <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="4" r="1.5" fill="currentColor" stroke="none"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7c-2.8 0-5 .5-5 .5L8 10l1 5-2 6h2l1.5-4L12 19l1.5-2L15 21h2l-2-6 1-5 1-2.5S14.8 7 12 7z"/>
        </svg>
        <span>Accesibilidad</span>
        <svg
            style="width:14px;height:14px;flex-shrink:0;transition:transform 0.2s;"
            :style="abierto ? 'transform:rotate(180deg);' : ''"
            fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="abierto"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="abierto = false"
        style="position:absolute; top:calc(100% + 4px); right:20px; min-width:220px; background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 8px 24px -4px rgba(15,23,42,0.12); padding:8px; display:none;"
        x-cloak
    >
        <p style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.06em; padding:4px 10px 6px;">Herramientas</p>

        <button
            @click="toggleLectura(); abierto = false"
            type="button"
            :aria-pressed="agrandar"
            style="display:flex; align-items:center; gap:10px; width:100%; padding:9px 10px; border-radius:8px; border:none; font-size:13px; font-weight:500; cursor:pointer; text-align:left; transition:background 0.12s;"
            :style="agrandar ? 'background:#eff6ff; color:#1d4ed8; font-weight:600;' : 'background:transparent; color:#334155;'"
            @mouseenter="$el.style.background = agrandar ? '#dbeafe' : '#f8fafc'"
            @mouseleave="$el.style.background = agrandar ? '#eff6ff' : 'transparent'"
        >
            <span style="display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; flex-shrink:0;"
                  :style="agrandar ? 'background:#dbeafe;' : 'background:#f1f5f9;'">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </span>
            <span x-text="agrandar ? 'Texto grande activo' : 'Agrandar Texto'"></span>
            <span x-show="agrandar" style="margin-left:auto; font-size:10px; padding:2px 7px; border-radius:999px; background:#dbeafe; color:#1d4ed8; font-weight:700;">ON</span>
        </button>

        <div style="height:1px; background:#f1f5f9; margin:4px 0;"></div>

        <button
            @click="toggleNarrador(); abierto = false"
            type="button"
            :aria-pressed="leyendo"
            style="display:flex; align-items:center; gap:10px; width:100%; padding:9px 10px; border-radius:8px; border:none; font-size:13px; font-weight:500; cursor:pointer; text-align:left; transition:background 0.12s;"
            :style="leyendo ? 'background:#fff1f2; color:#be123c; font-weight:600;' : 'background:transparent; color:#334155;'"
            @mouseenter="$el.style.background = leyendo ? '#ffe4e6' : '#f8fafc'"
            @mouseleave="$el.style.background = leyendo ? '#fff1f2' : 'transparent'"
        >
            <span style="display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; flex-shrink:0;"
                  :style="leyendo ? 'background:#ffe4e6;' : 'background:#f1f5f9;'">
                <svg x-show="!leyendo" style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
                <svg x-show="leyendo" style="display:none;width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
            </span>
            <span x-text="leyendo ? 'Detener narrador' : 'Narrador de voz'"></span>
            <span x-show="leyendo" style="margin-left:auto; font-size:10px; padding:2px 7px; border-radius:999px; background:#ffe4e6; color:#be123c; font-weight:700;">ON</span>
        </button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('accesibilidadPanel', () => ({
        abierto: false,
        agrandar: localStorage.getItem('agrandarTexto') === 'true',

        leyendo: false,
        sintesis: window.speechSynthesis,
        fragmentos: [],
        indice: 0,

        init() {
            this.aplicarLectura();
            if (this.sintesis && this.sintesis.speaking) this.leyendo = true;
        },

        toggleLectura() {
            this.agrandar = !this.agrandar;
            localStorage.setItem('agrandarTexto', this.agrandar);
            this.aplicarLectura();
        },

        aplicarLectura() {
            document.documentElement.style.fontSize = this.agrandar ? '130%' : '';
        },

        toggleNarrador() {
            if (this.leyendo) return this.detener();
            this.sintesis.cancel();
            const contenedor = document.getElementById('fi-main-content');
            if (!contenedor) return;
            const texto = this.obtenerTextoVisible(contenedor);
            if (!texto) return;
            this.fragmentos = this.dividir(texto, 220);
            this.indice = 0;
            this.leyendo = true;
            this.siguiente();
        },

        detener() {
            this.sintesis.cancel();
            this.fragmentos = [];
            this.indice = 0;
            this.leyendo = false;
        },

        esVisible(el) {
            if (el.hidden || el.inert || el.getAttribute('aria-hidden') === 'true' ||
                el.closest('[hidden],[inert],[aria-hidden="true"]')) return false;
            const cerrado = el.closest('details:not([open])');
            if (cerrado && !el.matches('summary') && !el.closest('summary')) return false;
            const s = getComputedStyle(el);
            if (s.display === 'none' || s.visibility === 'hidden' || s.visibility === 'collapse') return false;
            const r = el.getBoundingClientRect();
            return r.width > 0 && r.height > 0;
        },

        omitir(el) {
            return !!(el.closest('[data-accesibilidad-omitir]') ||
                el.closest('script,style,template,noscript,svg,button,[role="button"],[role="menu"],nav,aside') ||
                el.matches('input,textarea,select,option'));
        },

        obtenerTextoVisible(contenedor) {
            const walker = document.createTreeWalker(contenedor, NodeFilter.SHOW_TEXT, {
                acceptNode: n => {
                    const p = n.parentElement;
                    if (!p || !this.esVisible(p) || this.omitir(p)) return NodeFilter.FILTER_REJECT;
                    return n.nodeValue.replace(/\s+/g, ' ').trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
                }
            });
            const partes = [];
            while (walker.nextNode()) partes.push(walker.currentNode.nodeValue.replace(/\s+/g, ' ').trim());
            return partes.join(' ')
                .replace(/Ver enlace oficial del fondo/gi, '')
                .replace(/\bABIERTO\b/gi, 'Estado: Abierto.')
                .replace(/\bCERRADO\b/gi, 'Estado: Cerrado.')
                .replace(/\s+([,.;:!?])/g, '$1')
                .replace(/\s{2,}/g, ' ')
                .trim();
        },

        dividir(texto, max = 220) {
            const oraciones = texto.match(/[^.!?]+[.!?]+|[^.!?]+$/g) || [texto];
            const out = [];
            let actual = '';
            for (const parte0 of oraciones) {
                const parte = parte0.trim();
                if (!parte) continue;
                if (!actual) { actual = parte; continue; }
                if ((actual + ' ' + parte).length <= max) actual += ' ' + parte;
                else { out.push(actual); actual = parte; }
            }
            if (actual) out.push(actual);
            return out;
        },

        siguiente() {
            if (!this.leyendo || this.indice >= this.fragmentos.length) { this.detener(); return; }
            const u = new SpeechSynthesisUtterance(this.fragmentos[this.indice]);
            u.lang = 'es-CL'; u.rate = 0.85; u.pitch = 1;
            u.onend = () => { this.indice++; this.siguiente(); };
            u.onerror = () => this.detener();
            this.sintesis.speak(u);
        },
    }));
});
</script>
