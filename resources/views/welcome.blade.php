<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JuntApp - En Construcción</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body x-data="{ letraGrande: false }" 
      class="bg-slate-50 flex flex-col items-center justify-center min-h-screen m-0 transition-all duration-300">

    <div class="absolute top-6 right-6">
        <button @click="letraGrande = !letraGrande" 
                class="flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-100 text-slate-800 font-bold py-3 px-5 rounded-full shadow-md transition duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500"
                title="Alternar tamaño de letra">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <span x-text="letraGrande ? 'Volver a la vista normal' : 'Accesibilidad de lectura'"></span>
        </button>
    </div>

    <div class="text-center px-6 md:px-12 max-w-5xl mt-20 md:mt-0">
        <div class="mb-8 flex justify-center">
            <svg class="w-24 h-24 text-blue-600 transition-transform duration-300" 
                 :class="letraGrande ? 'scale-150 mb-6' : ''" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
        </div>

        <h1 class="font-bold mb-6 text-slate-900 tracking-tight transition-all duration-300"
            :class="letraGrande ? 'text-6xl md:text-8xl' : 'text-4xl md:text-6xl'">
            JuntApp
        </h1>
        
        <h2 class="font-semibold mb-8 text-blue-600 transition-all duration-300"
            :class="letraGrande ? 'text-4xl md:text-5xl' : 'text-2xl md:text-3xl'">
            Plataforma en Construcción
        </h2>
        
        <p class="mb-12 leading-relaxed transition-all duration-300"
           :class="letraGrande ? 'text-2xl md:text-4xl text-slate-900 font-medium' : 'text-lg md:text-xl text-slate-600'">
            Estamos trabajando en una nueva plataforma digital para optimizar la gestión, coordinación y transparencia de nuestra comunidad. ¡Pronto habrá novedades!
        </p>

        <a href="/admin" 
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg transition-all duration-300"
           :class="letraGrande ? 'text-2xl md:text-3xl py-5 px-10' : 'text-lg py-3 px-8'">
            Acceso Administrativo
        </a>
    </div>

</body>
</html>