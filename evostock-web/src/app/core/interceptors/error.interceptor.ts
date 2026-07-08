import { HttpErrorResponse, HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { catchError, throwError } from 'rxjs';
import { AuthService } from '../services/auth.service';
import { NotificationService } from '../services/notification.service';

/**
 * 401 means the session is gone (expired/revoked token) -> force logout.
 * 422 is left for the calling form to render field-level messages.
 * Everything else surfaces as a generic toast.
 */
export const errorInterceptor: HttpInterceptorFn = (req, next) => {
  const auth = inject(AuthService);
  const notifications = inject(NotificationService);

  return next(req).pipe(
    catchError((error: unknown) => {
      if (error instanceof HttpErrorResponse) {
        if (error.status === 401) {
          auth.clearSession();
        } else if (error.status !== 422) {
          notifications.error(error.error?.message ?? 'Ocurrió un error inesperado. Intenta de nuevo.');
        }
      }

      return throwError(() => error);
    }),
  );
};
