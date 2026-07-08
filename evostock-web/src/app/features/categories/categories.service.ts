import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { ApiResponse } from '../../core/models/api-response.model';
import { Category, CategoryPayload } from '../../core/models/category.model';
import { PaginatedResponse } from '../../core/models/pagination.model';

@Injectable({ providedIn: 'root' })
export class CategoriesService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = `${environment.apiUrl}/categories`;

  list(search: string, page: number): Observable<PaginatedResponse<Category>> {
    let params = new HttpParams().set('page', page).set('per_page', 10);

    if (search) {
      params = params.set('search', search);
    }

    return this.http.get<PaginatedResponse<Category>>(this.baseUrl, { params });
  }

  create(payload: CategoryPayload): Observable<Category> {
    return this.http
      .post<ApiResponse<Category>>(this.baseUrl, payload)
      .pipe(map((response) => response.data));
  }

  update(id: number, payload: CategoryPayload): Observable<Category> {
    return this.http
      .put<ApiResponse<Category>>(`${this.baseUrl}/${id}`, payload)
      .pipe(map((response) => response.data));
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/${id}`);
  }

  /**
   * Full list (single page, no search) for the product form's category
   * picker. The categories endpoint has no status filter, so active-only
   * filtering happens client-side.
   */
  listAllActive(): Observable<Category[]> {
    const params = new HttpParams().set('per_page', 100);

    return this.http.get<PaginatedResponse<Category>>(this.baseUrl, { params }).pipe(
      map((response) => response.data.filter((category) => category.is_active)),
    );
  }
}
