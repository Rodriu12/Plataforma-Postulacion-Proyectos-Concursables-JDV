<div x-data="narradorAccesibilidad()" data-accesibilidad-omitir style="display:flex;justify-content:flex-end;padding:12px 24px 0;">
    <button @click="toggleLectura()" type="button" aria-label="Narrador de voz"
        style="display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:9999px;border:1px solid #bfdbfe;font-size:14px;font-weight:600;cursor:pointer;"
        x-bind:style="leyendo ? 'background:#ffe4e6;color:#be123c;border-color:#fda4af;' : 'background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;'">
        <svg x-show="!leyendo" style="width:20px;height:20px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
        <svg x-show="leyendo" style="display:none;width:20px;height:20px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
        <span x-text="leyendo ? 'Detener lectura' : 'Narrador'"></span>
    </button>
</div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('narradorAccesibilidad', () => ({
        leyendo:false, sintesis:window.speechSynthesis, fragmentos:[], indice:0,
        toggleLectura() {
            if (this.leyendo) return this.detener();
            this.sintesis.cancel();
            const contenedor=document.getElementById('fi-main-content');
            if (!contenedor) return;
            const texto=this.obtenerTextoVisible(contenedor);
            if (!texto) return;
            this.fragmentos=this.dividir(texto,220); this.indice=0; this.leyendo=true; this.siguiente();
        },
        detener() { this.sintesis.cancel(); this.fragmentos=[]; this.indice=0; this.leyendo=false; },
        esVisible(el) {
            if (el.hidden || el.inert || el.getAttribute('aria-hidden')==='true' ||
                el.closest('[hidden],[inert],[aria-hidden="true"]')) return false;
            const cerrado=el.closest('details:not([open])');
            if (cerrado && !el.matches('summary') && !el.closest('summary')) return false;
            const s=getComputedStyle(el);
            if (s.display==='none' || s.visibility==='hidden' || s.visibility==='collapse') return false;
            const r=el.getBoundingClientRect();
            return r.width>0 && r.height>0;
        },
        omitir(el) {
            return !!(el.closest('[data-accesibilidad-omitir]') ||
                el.closest('script,style,template,noscript,svg,button,[role="button"],[role="menu"],nav,aside') ||
                el.matches('input,textarea,select,option'));
        },
        obtenerTextoVisible(contenedor) {
            const walker=document.createTreeWalker(contenedor,NodeFilter.SHOW_TEXT,{
                acceptNode:n => {
                    const p=n.parentElement;
                    if (!p || !this.esVisible(p) || this.omitir(p)) return NodeFilter.FILTER_REJECT;
                    return n.nodeValue.replace(/\s+/g,' ').trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
                }
            });
            const partes=[];
            while(walker.nextNode()) partes.push(walker.currentNode.nodeValue.replace(/\s+/g,' ').trim());
            return partes.join(' ').replace(/Ver enlace oficial del fondo/gi,'')
                .replace(/\bABIERTO\b/gi,'Estado: Abierto.')
                .replace(/\bCERRADO\b/gi,'Estado: Cerrado.')
                .replace(/\s+([,.;:!?])/g,'$1').replace(/\s{2,}/g,' ').trim();
        },
        dividir(texto,max=220) {
            const oraciones=texto.match(/[^.!?]+[.!?]+|[^.!?]+$/g)||[texto], out=[]; let actual='';
            for(const parte0 of oraciones){ const parte=parte0.trim(); if(!parte) continue;
                if(!actual){actual=parte;continue;}
                if((actual+' '+parte).length<=max) actual+=' '+parte; else {out.push(actual);actual=parte;}
            }
            if(actual) out.push(actual); return out;
        },
        siguiente() {
            if(!this.leyendo || this.indice>=this.fragmentos.length){this.detener();return;}
            const u=new SpeechSynthesisUtterance(this.fragmentos[this.indice]);
            u.lang='es-CL'; u.rate=.85; u.pitch=1;
            u.onend=()=>{this.indice++;this.siguiente();}; u.onerror=()=>this.detener();
            this.sintesis.speak(u);
        }
    }));
});
</script>
