## Configuración e inicio

Desde la carpeta del proyecto inventario-api, ejecuta los siguientes comandos:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan optimize
php artisan serve
```

Antes de ejecutar las migraciones, configura en `.env` los datos de conexión a la base de datos. `migrate:fresh --seed` elimina las tablas existentes, las crea de nuevo y carga los datos iniciales.

Al iniciar el servidor, abre la URL que muestra Artisan (normalmente `http://127.0.0.1:8000`).

## Vue

Para Vue hay menos pasos, dichos pasos estan en el README.md en inventario-web generados por defecto

```bash
npm install
npm run serve
```