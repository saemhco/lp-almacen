# Almacén

API REST en Laravel 12 para administrar ítems de un almacén: nombre, descripción, precio y cantidad. La base de datos local es SQLite.

## Qué creamos

| Pieza | Archivo | Para qué |
| --- | --- | --- |
| Proyecto Laravel | raíz del repo | Esqueleto de la aplicación |
| Migración `items` | `database/migrations/2026_09_30_133514_create_items_table.php` | Crea la tabla en la base de datos |
| Modelo `Item` | `app/Models/Item.php` | Representa un ítem y permite guardarlo con Eloquent |
| Factory | `database/factories/ItemFactory.php` | Genera ítems de prueba |
| Seeder | `database/seeders/ItemSeeder.php` | Inserta datos iniciales de ítems |
| Seeder principal | `database/seeders/DatabaseSeeder.php` | Crea el usuario admin y llama a `ItemSeeder` |
| Controlador | `app/Http/Controllers/ItemController.php` | CRUD de la API |
| Trait de respuestas | `app/Traits/ApiResponseTrait.php` | Formato JSON de éxito y error |
| Rutas API | `routes/api.php` | Endpoints bajo `/api` |
| Sanctum | `laravel/sanctum` y su migración | Tokens de acceso (aún no se usan en las rutas) |

Endpoints actuales (sin autenticación):

| Método | Ruta | Acción |
| --- | --- | --- |
| GET | `/api/items` | Listar |
| POST | `/api/items` | Crear |
| GET | `/api/items/{id}` | Ver uno |
| PUT | `/api/items/{id}` | Actualizar |
| DELETE | `/api/items/{id}` | Eliminar |

## Laravel

### Crear el proyecto y dejarlo listo

```bash
composer create-project laravel/laravel almacen
cd almacen
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

- `composer create-project` descarga Laravel y sus dependencias.
- `.env` guarda la configuración local (no se sube a GitHub).
- `key:generate` crea `APP_KEY`, necesaria para cifrar sesiones y cookies.
- `touch` crea el archivo de SQLite. En `.env`, `DB_CONNECTION=sqlite`.
- `migrate` ejecuta las migraciones y crea las tablas.
- `serve` levanta el servidor en `http://127.0.0.1:8000`.

### Instalar Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

`vendor:publish` copia la migración de `personal_access_tokens`. `migrate` crea esa tabla.

### Crear modelo, migración, factory, seeder y controlador

```bash
php artisan make:model Item -mfs
php artisan make:controller ItemController --api
php artisan make:seeder ItemSeeder
```

En `make:model`, las banderas son:

- `-m` migración
- `-f` factory
- `-s` seeder

`--api` genera los métodos `index`, `store`, `show`, `update` y `destroy`, sin las vistas de un controlador web.

El trait `ApiResponseTrait` se escribió a mano en `app/Traits/`. Artisan no trae un comando para traits.

### Migraciones

```bash
php artisan make:migration create_items_table
php artisan migrate
php artisan migrate:status
php artisan migrate:rollback
php artisan migrate:fresh
php artisan migrate:fresh --seed
```

- `migrate` aplica las migraciones pendientes.
- `migrate:status` muestra cuáles ya corrieron.
- `rollback` deshace el último lote.
- `migrate:fresh` borra todas las tablas y las vuelve a crear. En clase se usa en local, nunca sobre datos que haya que conservar.
- `--seed` ejecuta los seeders justo después.

### Seeders y factories

```bash
php artisan make:factory ItemFactory --model=Item
php artisan make:seeder ItemSeeder
php artisan db:seed
php artisan db:seed --class=ItemSeeder
```

- `db:seed` corre `DatabaseSeeder` (usuario admin y luego `ItemSeeder`).
- `--class` corre un seeder concreto.
- Dentro del seeder, `Item::factory()->count(10)->create()` usa el factory para insertar 10 ítems falsos.

### Ver rutas y probar en consola

```bash
php artisan route:list
php artisan tinker
```

En Tinker, ejemplos de lo que ya existe en el modelo:

```php
Item::all();
Item::find(1);
Item::factory()->create();
```

Salir de Tinker: `exit`.

### Comandos que conviene recordar

```bash
php artisan list
php artisan --version
php artisan make:model Nombre -mfs
php artisan make:controller NombreController --api
php artisan migrate:fresh --seed
php artisan serve
```

## Git y GitHub

### Empezar el repositorio

```bash
git init
git status
git add .
git commit -m "Agrega modelo, migracion, factory, seeder, controlador y rutas de items"
```

- `git init` convierte la carpeta en un repositorio.
- `git status` muestra qué cambió y qué no está incluido.
- `git add .` prepara todos los cambios. `.env`, `vendor/` y `node_modules/` quedan fuera por `.gitignore`.
- `git commit` guarda una versión. El mensaje dice qué se creó en la clase: modelo, migración, factory, seeder, controlador y rutas.

### Ver el historial y deshacer lo que aún no se commiteó

```bash
git log --oneline
git diff
git restore archivo.php
git restore --staged archivo.php
```

- `git log` muestra los commits.
- `git diff` muestra los cambios sin commitear.
- `git restore` descarta cambios de un archivo que todavía no está en stage.
- `git restore --staged` lo saca del stage y lo deja como cambio local.

### Ramas

```bash
git branch
git switch -c feature/items
git switch main
git merge feature/items
```

- `git branch` lista las ramas.
- `git switch -c` crea una rama y se cambia a ella.
- `git merge` integra esa rama en la actual.

### Subir el proyecto a GitHub

Crear el repositorio vacío en GitHub (sin README, para no pisar este) y conectarlo:

```bash
git remote add origin git@github.com:USUARIO/almacen.git
git branch -M main
git push -u origin main
```

- `remote add` guarda la dirección del repositorio remoto con el nombre `origin`.
- `branch -M main` nombra la rama principal `main`.
- `push -u` sube los commits y deja `main` siguiendo a `origin/main`. Las siguientes veces basta con `git push`.

### Bajar cambios y clonar

```bash
git pull
git clone git@github.com:USUARIO/almacen.git
```

- `git pull` trae y fusiona lo que haya en GitHub.
- `git clone` copia un repositorio completo a una carpeta nueva.

### Secuencia de cada cambio

```bash
git status
git add .
git commit -m "Agrega modelo, migracion, factory, seeder, controlador y rutas de items"
git push
```

## Dejar el proyecto corriendo desde cero

En una máquina nueva, después de clonar:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

La API queda en `http://127.0.0.1:8000/api/items`.
