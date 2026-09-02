<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JuntApp - En Construcción</title>
    <!-- Tailwind CSS (CDN para la vista temporal) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js (Parte de tu TALL Stack) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<!-- x-data inicia la variable letraGrande en falso. 
     :class aplica un tamaño u otro dependiendo del estado del botón -->
<body x-data="{ letraGrande: false }" 
      :class="letraGrande ? 'text-xl md:text-2xl' : 'text-base md:text-lg'" 
      class="bg-slate-50 flex flex-col items-center justify-center min-h-screen m-0 text-slate-800 transition-all duration-300">

    <!-- Botón de Accesibilidad -->
    <div class="absolute top-6 right-6">
        <button @click="letraGrande = !letraGrande" 
                class="flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-full shadow-md transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                title="Alternar tamaño de letra">
            <!-- Ícono de accesibilidad visual (ojo) -->
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <span x-text="letraGrande ? 'Letra Normal' : 'Letra Grande'"></span>
        </button>
    </div>

    <div class="text-center px-6 md:px-12 max-w-3xl mt-12 md:mt-0">
        <!-- Ícono o Logo representativo -->
        <div class="mb-8 flex justify-center">
            <svg class="w-24 h-24 text-blue-600 transition-transform duration-300" 
                 :class="letraGrande ? 'scale-110' : ''" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
        </div>

        <!-- Títulos con clases dinámicas para escalar drásticamente -->
        <h1 class="font-bold mb-6 text-slate-900 tracking-tight transition-all duration-300"
            :class="letraGrande ? 'text-5xl md:text-7xl' : 'text-4xl md:text-6xl'">
            JuntApp
        </h1>
        <h2 class="font-semibold mb-6 text-blue-600 transition-all duration-300"
            :class="letraGrande ? 'text-3xl md:text-4xl' : 'text-2xl md:text-3xl'">
            Plataforma en Construcción
        </h2>
        <p class="text-slate-600 mb-10 leading-relaxed">
            Estamos trabajando en una nueva plataforma digital para optimizar la gestión, coordinación y transparencia de nuestra comunidad. ¡Pronto habrá novedades!
        </p>

        <!-- Botón temporal -->
        <a href="/admin" 
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-300"
           :class="letraGrande ? 'text-xl' : 'text-lg'">
            Acceso Administrativo
        </a>
    </div>

</body>
</html>
