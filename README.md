# EvoStock

Sistema de Gestión de Productos — prueba técnica para Programador Digital Senior (Agencia Evollution / Corporación Lady Lee).

Monorepo con dos proyectos independientes:

- **[`evostock-api/`](evostock-api/README.md)** — backend Laravel + Sanctum + MySQL (API REST).
- **[`evostock-web/`](evostock-web/README.md)** — frontend Angular + Angular Material (SPA que consume la API).

Cada carpeta tiene su propio README con el detalle completo (tecnología, justificación, requisitos, instalación, configuración, variables de entorno, cómo correrlo, usuario de prueba y arquitectura). Este README es solo el punto de entrada.

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

## Usuario de prueba

- **Email:** `test@evostock.com`
- **Password:** `password`

## Por qué Laravel + Angular

Ambas tecnologías se eligieron por la misma razón: alinearse con el stack que usa Corporación Lady Lee, más que por dominio previo — fue un reto autoimpuesto para desempolvar PHP y llevar criterios de arquitectura ya conocidos a convenciones nuevas (Laravel) y a un framework de frontend estructurado (Angular). El detalle completo de esta decisión está en `evostock-api/README.md` (sección 2).

## Arquitectura, en una frase

API (Laravel: Controllers delgados → Form Requests → Services → Repository delgado → Eloquent) desacoplada de una SPA (Angular: componentes standalone + signals, sin librería de estado) que se comunican por HTTP con auth Bearer (Sanctum). El detalle de cada lado está en su propio README.
