<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JuntApp - Plataforma de Gestión Comunitaria</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html {
            transition: font-size 0.2s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen" x-data="{
    agrandar: localStorage.getItem('agrandarTexto') === 'true',
    aplicar() { document.documentElement.style.fontSize = this.agrandar ? '145%' : '100%'; },
    toggle() {
        this.agrandar = !this.agrandar;
        localStorage.setItem('agrandarTexto', this.agrandar);
        this.aplicar();
    }
}"
    x-init="aplicar()">
    <header class="w-full bg-white shadow-sm py-4 px-6 sm:px-10 flex justify-between items-center">
        <div class="flex items-center gap-2 text-2xl font-bold text-gray-900 tracking-tight">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="currentColor"
                viewBox="0 0 24 24">
                <path
                    d="M11.47 3.84a.75.75 0 011.06 0l8.99 9a.75.75 0 11-1.06 1.06l-4.71-4.72V20a1 1 0 01-1 1h-3v-6a1 1 0 00-1-1h-2a1 1 0 00-1 1v6H5a1 1 0 01-1-1V9.18L.78 13.9a.75.75 0 01-1.06-1.06l8.99-9zM7 10.59V19h2v-6a2.5 2.5 0 012.5-2.5h2A2.5 2.5 0 0116 13v6h2v-8.41l-6-6-6 6z" />
            </svg>
            JuntApp
        </div>
        <button @click="toggle()" type="button"
            x-bind:style="agrandar
                ?
                'display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; font-size: 14px; font-weight: 700; border-radius: 9999px; background-color: #ffffff; color: #1d4ed8; border: 2px solid #2563eb; cursor: pointer; transition: all 0.2s;' :
                'display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; font-size: 13px; font-weight: 500; border-radius: 9999px; background-color: #ffffff; color: #374151; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); cursor: pointer; transition: all 0.2s;'"
            :title="agrandar ? 'Volver a la vista normal' : 'Activar accesibilidad de lectura'">

            <span
                style="width: 16px; height: 16px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;"
                x-bind:style="agrandar ? 'color: #1d4ed8;' : 'color: #2563eb;'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </span>
            <span x-text="agrandar ? 'Volver a la vista normal' : 'Accesibilidad de lectura'"></span>
        </button>
    </header>
    <main class="flex-grow flex flex-col items-center justify-center px-4 py-12 text-center">
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100 max-w-3xl w-full">
            <div class="mb-6 flex justify-center">
                <div class="p-4 bg-blue-50 rounded-full">
                    <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Bienvenido a JuntApp</h1>
            <h2 class="text-xl text-blue-600 font-semibold mb-6">Plataforma de Gestión y Coordinación Comunitaria</h2>

            <p class="text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed text-lg">
                Herramienta digital diseñada para fortalecer el trabajo de las Juntas de Vecinos y Organizaciones
                Sociales en Yumbel y sus alrededores.
                Nuestro objetivo es optimizar la administración de fondos y agilizar la comunicación,
                haciendo los procesos más accesibles y transparentes para toda la comunidad local.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-4">
                <a href="/admin"
                    class="inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-colors duration-200">
                    Pulse aquí para acceder a la página
                </a>
            </div>
        </div>
    </main>

    <footer class="w-full bg-white border-t border-gray-200 py-6 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} JuntApp - Fundación Bio Bío Cultural, Yumbel. Desarrollando soluciones para
            la comunidad.</p>
    </footer>

</body>

</html>
