# EvoStock API

Backend del Sistema de Gestión de Productos (prueba técnica — Programador Digital Senior, Evollution / Corporación Lady Lee). Expone una API REST (Laravel + Sanctum) para los módulos de Categorías, Productos y Dashboard, consumida por un cliente Angular (en `evostock-web`, en desarrollo).

## 1. Tecnología utilizada

- **Laravel 13** (PHP 8.3+) — API REST.
- **MySQL 8** — persistencia.
- **Laravel Sanctum** — autenticación por token (Bearer).
- **Docker Compose** — MySQL local sin necesidad de instalarlo nativamente.
- **PHPUnit** — tests de feature sobre SQLite en memoria.
- **Laravel Pint** — estilo de código.

## 2. Justificación de la elección

Elegí **Laravel sobre Next.js**, y vale la pena ser honesto sobre el motivo: no fue por dominio previo del framework. Tenía nociones generales de PHP pero no experiencia amplia con Laravel. La decisión fue justamente lo contrario a "ir a lo seguro": Corporación Lady Lee usa Laravel como uno de sus frameworks principales, así que lo tomé como un reto autoimpuesto para:

- Desempolvar y profundizar mis conocimientos de PHP.
- Alinearme desde ya con el stack técnico que se maneja en el corporativo.
- Aprender a llevar criterios de lógica, estructura y arquitectura (que ya tenía interiorizados en otros lenguajes/frameworks) a las convenciones propias de Laravel.

En otras palabras, también usé la prueba como una oportunidad de acoplamiento al ambiente técnico de la empresa, más que como una demostración de una habilidad ya dominada. Adicionalmente, el dominio del problema (CRUD con reglas de negocio, relaciones N:M, agregaciones para el dashboard) encaja naturalmente con Eloquent, migraciones y validación nativa de Laravel, y separar API (Laravel) de UI (Angular) me permite demostrar diseño de arquitectura en ambos lados de forma independiente.

**Arquitectura**: Controllers delgados → Form Requests (validación) → Services (lógica de negocio) → Repositories (solo para queries específicas: filtros, paginación, agregados) → Eloquent Models. Ver detalle en la sección 9.

## 3. Requisitos

- PHP >= 8.3 con extensiones `pdo_mysql`, `mbstring`, `openssl`.
- Composer 2.x.
- MySQL 8 (vía Docker, recomendado) o una instancia local propia.
- Docker Desktop (si se usa el `docker-compose.yml` incluido).

## 4. Instalación

```bash
git clone <url-del-repositorio>
cd EvoStock/evostock-api

composer install
cp .env.example .env
php artisan key:generate
```

## 5. Configuración

Levantar MySQL con Docker (recomendado, no requiere instalar MySQL nativo):

```bash
docker compose up -d
```

Esto crea una base de datos `evostock` en `127.0.0.1:3306` con las credenciales ya definidas en `.env.example`. Si prefieres un MySQL propio (XAMPP, Laragon, etc.), simplemente ajusta `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` en tu `.env`.

## 6. Variables de entorno

Las relevantes para este proyecto (ya presentes en `.env.example`):

| Variable | Descripción | Valor por defecto |
|---|---|---|
| `DB_CONNECTION` | Driver de base de datos | `mysql` |
| `DB_HOST` | Host de MySQL | `127.0.0.1` |
| `DB_PORT` | Puerto de MySQL | `3306` |
| `DB_DATABASE` | Nombre de la base de datos | `evostock` |
| `DB_USERNAME` | Usuario de MySQL | `evostock` |
| `DB_PASSWORD` | Password de MySQL | `evostock` |
| `APP_URL` | URL base de la API | `http://localhost` |

CORS ya está configurado en `config/cors.php` para aceptar `http://localhost:4200` (Angular en desarrollo).

## 7. Instrucciones para ejecutar el proyecto

```bash
php artisan migrate --seed   # crea el esquema y datos de ejemplo (usuario de prueba incluido)
php artisan serve            # sirve la API en http://127.0.0.1:8000
```

Ejecutar los tests (no requieren MySQL, corren sobre SQLite en memoria):

```bash
php artisan test
```

### Endpoints principales (`/api/v1`)

| Método | Ruta | Descripción | Auth |
|---|---|---|---|
| POST | `/auth/login` | Login, devuelve token Sanctum | No |
| POST | `/auth/logout` | Revoca el token actual | Sí |
| GET | `/auth/me` | Usuario autenticado | Sí |
| GET/POST | `/categories` | Listar (con `search`) / crear | Sí |
| GET/PUT/DELETE | `/categories/{id}` | Detalle / actualizar / eliminar (soft delete) | Sí |
| GET/POST | `/products` | Listar (con `search`, `category_id`, `status`, `sort_by`, `sort_dir`) / crear | Sí |
| GET/PUT/DELETE | `/products/{id}` | Detalle / actualizar / eliminar (soft delete) | Sí |
| GET | `/dashboard` | Totales, activos/inactivos, bajo inventario, últimos productos | Sí |

Todas las rutas protegidas usan `Authorization: Bearer <token>` obtenido en el login.

## 8. Usuario de prueba

Creado por el seeder (`php artisan migrate --seed`):

- **Email:** `test@evostock.com`
- **Password:** `password`

## 9. Explicación breve de la arquitectura

```
Request → Controller (Api/V1) → Form Request (validación) → Service (lógica de negocio)
                                                                   ↓
                                                    Repository (solo queries específicas)
                                                                   ↓
                                                            Eloquent Model → MySQL
```

- **Controllers** (`app/Http/Controllers/Api/V1`): solo orquestan HTTP — reciben la request ya validada, delegan al Service y devuelven un `API Resource`.
- **Form Requests** (`app/Http/Requests`): validación de entrada declarativa por endpoint.
- **Services** (`app/Services`): dueños de las reglas de negocio. Ejemplos: `CategoryService` rechaza nombres duplicados (usando `CategoryRepositoryInterface::nameExists()`); `ProductService` sincroniza la relación N:M con categorías en cada create/update; `DashboardService` agrega los indicadores del panel.
- **Repositories** (`app/Repositories`): **deliberadamente delgados**. Solo exponen las queries que Eloquent no resuelve "gratis" (paginación filtrada/ordenada, chequeo de unicidad, agregados del dashboard). El CRUD trivial (`create`, `update`, `delete`) se hace directo con el Eloquent Model dentro del Service — envolver eso en un Repository genérico no aportaría nada real, ya que Eloquent es en sí mismo una capa de acceso a datos. Las interfaces (`Contracts/`) están inyectadas por contrato (bindeadas en `RepositoryServiceProvider`) para mantener inversión de dependencias y facilitar tests.
- **API Resources** (`app/Http/Resources`): dan forma consistente a las respuestas JSON, independiente de cómo estén modelados los datos internamente.
- **Modelos**: `Category` y `Product` con relación `belongsToMany` (tabla pivote `category_product`), `SoftDeletes` para poder "eliminar o desactivar" sin perder historial, y un flag `is_active` para el filtro de estado.
- **Auth**: Sanctum en modo *token* (no cookies SPA), ya que el frontend Angular vive en otro origen/puerto — evita la complejidad de dominios "stateful" y CSRF que solo aplica al modo cookie de Sanctum.
