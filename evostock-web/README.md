# EvoStock Web

Frontend del Sistema de Gestión de Productos (prueba técnica — Programador Digital Senior, Evollution / Corporación Lady Lee). SPA en Angular que consume la API de `evostock-api` (Sanctum, Bearer token).

## 1. Tecnología utilizada

- **Angular 20** — standalone components, signals, `@if`/`@for`.
- **Angular Material 20** (tema Material 3 personalizado, paleta azul/slate en vez del morado por defecto).
- **RxJS** para la capa HTTP; sin librería de estado (NgRx, etc.) — con 2 entidades y un dashboard, un store global habría sido sobre-ingeniería.
- **Reactive Forms** para validación de formularios.

## 2. Justificación de la elección

Angular se evaluó junto con Next.js para el frontend; se optó por Angular por ser, junto con Laravel, parte del stack que maneja Corporación Lady Lee — la misma lógica de alineamiento con el ambiente técnico del corporativo que motivó la elección de Laravel en el backend (ver `evostock-api/README.md`, sección 2). Angular Material se eligió sobre Tailwind a medida porque entrega de fábrica exactamente las piezas que pide el brief (tabla+paginación, diálogo de confirmación, snackbar de éxito/error, spinners de carga) con una apariencia corporativa consistente, sin invertir tiempo de la prueba construyendo esos primitivos desde cero.

## 3. Requisitos

- Node.js 20+ y npm.
- Angular CLI 20 (`npm install -g @angular/cli`, opcional — también funciona con `npx`).
- La API de `evostock-api` corriendo en `http://localhost:8000` (ver su README).

## 4. Instalación

```bash
cd EvoStock/evostock-web
npm install
```

## 5. Configuración

La URL de la API está centralizada en `src/environments/environment.ts` (build de producción) y `environment.development.ts` (`ng serve`):

```ts
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api/v1',
};
```

CORS ya está habilitado en el backend para `http://localhost:4200`.

## 6. Variables de entorno

Este proyecto no usa un `.env` (Angular resuelve config de build vía `environment.ts`), pero el valor equivalente a una variable de entorno es `apiUrl` en los archivos de `src/environments/`. Ajusta ese valor si la API corre en otro host/puerto.

## 7. Instrucciones para ejecutar el proyecto

```bash
ng serve
```

Abre `http://localhost:4200`. Requiere que `evostock-api` esté corriendo (`docker compose up -d` + `php artisan serve` desde `evostock-api/`).

Tests unitarios (Karma/Jasmine, generados por el propio `ng generate`):

```bash
ng test
```

Build de producción:

```bash
ng build
```

## 8. Usuario de prueba

El mismo sembrado por el backend:

- **Email:** `test@evostock.com`
- **Password:** `password`

## 9. Explicación breve de la arquitectura

```
Feature (routes lazy-loaded) → Component (standalone, signals)
                                      ↓
                          Service (HttpClient + environment.apiUrl)
                                      ↓
                    Interceptors (auth: Bearer token / error: 401 → logout, resto → toast)
                                      ↓
                                evostock-api
```

- **`core/`**: transversal a toda la app — `AuthService` (sesión con signals, persistida en `localStorage`), `NotificationService` (wrapper de `MatSnackBar`), guards (`authGuard`/`guestGuard`), interceptores HTTP y los modelos TypeScript que reflejan uno a uno los API Resources del backend.
- **`shared/`**: componentes reutilizables entre features — el `Shell` (toolbar + sidenav responsive) y el `ConfirmDialog` genérico usado por Categorías y Productos antes de eliminar.
- **`features/`**: un directorio por módulo de negocio (`auth`, `dashboard`, `categories`, `products`), cada uno con su propio servicio HTTP y componentes. CRUD de Categorías/Productos se resuelve con **diálogos** (`MatDialog`) sobre la vista de listado, en vez de rutas separadas de crear/editar — menos pantallas, patrón de panel admin más simple.
- **Sin Repository/Store propio en el frontend**: los `*.service.ts` llaman a `HttpClient` directo. Añadir una capa de abstracción adicional sobre HttpClient (o un store tipo NgRx) para 3 recursos no aportaría nada que no resuelva ya RxJS + signals — mismo criterio de "no sobre-ingeniería" aplicado en el Repository del backend.
- **Errores de validación (422)**: el `errorInterceptor` los deja pasar sin mostrar un toast genérico, para que cada formulario los traduzca a mensajes específicos por campo (ej. nombre de categoría duplicado).
