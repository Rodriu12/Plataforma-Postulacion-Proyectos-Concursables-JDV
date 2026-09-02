#  JuntApp

Plataforma web de gestión comunitaria desarrollada para optimizar la administración, la transparencia y la coordinación en las **Juntas de Vecinos**. Su objetivo principal es centralizar la gestión de usuarios, recursos y trámites comunitarios bajo una interfaz moderna, limpia y altamente accesible.

---


## Instrucciones de uso

1. Instalar las dependencias de PHP:
```bash
composer install
```
2. Configurar el entorno:

* Duplica el archivo de ejemplo como .env.

* Configura las credenciales de tu base de datos local en el archivo .env:

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

```
3. Generar la llave de la aplicación:
```bash
php artisan key:generate
```

4. Ejecutar las migraciones de la base de datos:
```bash
php artisan migrate
```
5. Crear el usuario administrador para Filament:
```bash
php artisan make:filament-user
```
(Aquí te pedirá que te pongas un nombre de usuario, email y contraseña)

6. Iniciar el servidor de desarrollo:
```bash
php artisan serve
```
7. Acceder a la plataforma:
Abre tu navegador y entra a: http://localhost:8000/admin

##  Tecnologías

El proyecto está construido utilizando el **TALL Stack** y herramientas modernas de desarrollo web:

* **Backend:** [Laravel](https://laravel.com/) (Framework PHP robusto y seguro).
* **Panel de Administración:** [FilamentPHP v3](https://filamentphp.com/) (Interfaz de administración basada en TALL).
* **Frontend / Dinamismo:** Livewire & Alpine.js (para interfaces reactivas sin la complejidad de una SPA completa).
* **Estilos:** Tailwind CSS (Diseño adaptable, limpio y moderno).
* **Base de Datos:** MySQL (gestionado mediante XAMPP).
* **Control de Versiones:** Git & GitHub (bajo flujo de trabajo GitHub Flow).

---

##  Características Principales

* **Panel de Control Administrativo:** Interfaz centralizada para directivos y administradores mediante Filament.
* **Gestión de Vecinos y Roles:** Control de cuentas de usuario, autoridades vecinales y voluntarios.
* **Enfoque en Accesibilidad:** Diseño pensado para ser intuitivo y accesible para líderes comunitarios de distintas edades.
* **Arquitectura Escalable:** Estructura modular basada en buenas prácticas de ingeniería de software.

---

##  Requisitos del Sistema

Antes de clonar e instalar el proyecto en tu entorno local, asegúrate de contar con lo siguiente:

* **PHP:** Versión `8.2` o superior.
* **Composer:** Administrador de dependencias de PHP.
* **XAMPP** (o entorno equivalente con Apache y MySQL).
* **Extensiones de PHP habilitadas:** `intl` y `zip`.

---

##  Guía de Instalación Local

Sigue estos pasos para poner en marcha el entorno de desarrollo en tu computador:

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/tu-repositorio.git](https://github.com/tu-usuario/tu-repositorio.git)
   cd Plataforma-Postulacion-Proyectos-Concursables-JDV
   ```

## Licencia
Este proyecto está bajo la Licencia MIT. Consulta el archivo [](LICENSE) para más detalles.

Copyright (c) 2026 Rodriu12 

## Autor
Proyecto desarrollado en el contexto académico de ingeniería, enfocado en dar solución digital a las necesidades de las organizaciones comunitarias por lo tanto su copia debe ser informada al autor de este proyecto. Esta prohibida estrictamente la copia o comercializacion de este software sin el permiso del autor.