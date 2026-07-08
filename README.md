# EvoStock

**Sistema de Gestión de Productos** — prueba técnica para Programador Digital Senior (Agencia Evollution / Corporación Lady Lee).

Aplicación web full-stack para administrar un catálogo de productos clasificados por categorías, con un panel de indicadores. Está organizada como un monorepo con dos proyectos independientes y desacoplados:

| Proyecto | Rol | Stack | README |
|---|---|---|---|
| [`evostock-api/`](evostock-api/README.md) | API REST | Laravel · Sanctum · MySQL | [ver detalle](evostock-api/README.md) |
| [`evostock-web/`](evostock-web/README.md) | SPA cliente | Angular · Angular Material | [ver detalle](evostock-web/README.md) |

Cada proyecto tiene su propio README con los 9 puntos de documentación (tecnología, justificación, requisitos, instalación, configuración, variables de entorno, ejecución, usuario de prueba y arquitectura) específicos de su stack. **Este README es solo el punto de entrada genérico.**

## Funcionalidades

- **Autenticación** por token Bearer (login / logout).
- **Categorías**: crear, editar, activar/desactivar, eliminar (soft delete), listar y buscar. Regla de negocio: no se permiten nombres duplicados.
- **Productos**: CRUD completo con detalle, relación N:M con categorías, búsqueda, filtro por categoría y por estado, y ordenamiento por nombre o fecha.
- **Dashboard**: total de productos y categorías, productos activos/inactivos, bajo inventario (< 10 unidades) y últimos productos registrados.
- **Transversal**: paginación, confirmación antes de eliminar, mensajes de éxito/error, formularios validados, estados de carga y manejo centralizado de errores.

## Tecnologías

**Backend (`evostock-api`)**
- Laravel (PHP 8.3+) — API REST versionada (`/api/v1`).
- Laravel Sanctum — autenticación por token.
- MySQL 8 (vía Docker Compose, sin instalación nativa).
- PHPUnit — tests de feature.

**Frontend (`evostock-web`)**
- Angular 20 — standalone components + signals.
- Angular Material 20 — sistema de diseño (tema personalizado).
- RxJS — capa HTTP e interceptores.

## Estructura del repositorio

```
EvoStock/
├── evostock-api/     # Backend Laravel (API REST)
├── evostock-web/     # Frontend Angular (SPA)
├── README.md         # Este archivo (overview del monorepo)
└── .gitignore
```

## Arranque rápido

```bash
# 1. Backend
cd evostock-api
composer install
cp .env.example .env
php artisan key:generate
docker compose up -d          # MySQL local, no requiere instalación nativa
php artisan migrate --seed
php artisan serve              # http://127.0.0.1:8000

# 2. Frontend (en otra terminal)
cd evostock-web
npm install
ng serve                        # http://localhost:4200
```

Detalle de requisitos, configuración y variables de entorno en el README de cada proyecto.

## Usuario de prueba

- **Email:** `test@evostock.com`
- **Password:** `password`

## Por qué Laravel + Angular

Cada tecnología se eligió por una razón distinta:

- **Laravel** fue un reto autoimpuesto. Corporación Lady Lee lo usa como uno de sus frameworks principales, así que lo tomé para alinearme con su stack y desempolvar PHP, aun sin tener experiencia amplia con el framework. Esto implicó una carga investigativa considerable: documentación, asistencia con IA y una revisión panorámica del framework.
- **Angular** lo elegí sobre Next.js porque ya es una tecnología con la que tengo relación y con la que me he estado familiarizando desde hace un tiempo. Eso me permitió acelerar el desarrollo del frontend sin la investigación profunda que sí demandó Laravel, y concentrar ese esfuerzo donde estaba el verdadero reto (el backend), optimizando el tiempo total de la prueba.

El detalle de cada decisión está en el README de su proyecto ([backend](evostock-api/README.md), [frontend](evostock-web/README.md), sección 2).

## Arquitectura, en una frase

API (Laravel: Controllers delgados → Form Requests → Services → Repository delgado → Eloquent) desacoplada de una SPA (Angular: componentes standalone + signals, sin librería de estado), comunicadas por HTTP con autenticación Bearer (Sanctum). El detalle de cada lado vive en su propio README.
