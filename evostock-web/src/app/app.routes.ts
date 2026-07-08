import { Routes } from '@angular/router';
import { authGuard, guestGuard } from './core/guards/auth.guard';
import { Shell } from './shared/components/shell/shell';

export const routes: Routes = [
  {
    path: 'login',
    loadComponent: () => import('./features/auth/login/login').then((m) => m.Login),
    canActivate: [guestGuard],
  },
  {
    path: '',
    component: Shell,
    canActivate: [authGuard],
    children: [
      {
        path: '',
        loadComponent: () => import('./features/dashboard/dashboard').then((m) => m.Dashboard),
      },
      {
        path: 'categories',
        loadComponent: () =>
          import('./features/categories/categories-list/categories-list').then(
            (m) => m.CategoriesList,
          ),
      },
      {
        path: 'products',
        loadComponent: () =>
          import('./features/products/products-list/products-list').then((m) => m.ProductsList),
      },
      { path: '**', redirectTo: '' },
    ],
  },
];
