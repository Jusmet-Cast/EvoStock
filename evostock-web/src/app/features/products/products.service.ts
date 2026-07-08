import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from '../../../environments/environment';
import { ApiResponse } from '../../core/models/api-response.model';
import { Product, ProductFilters, ProductPayload } from '../../core/models/product.model';
import { PaginatedResponse } from '../../core/models/pagination.model';

@Injectable({ providedIn: 'root' })
export class ProductsService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = `${environment.apiUrl}/products`;

  list(filters: ProductFilters): Observable<PaginatedResponse<Product>> {
    let params = new HttpParams().set('per_page', filters.per_page ?? 10).set('page', filters.page ?? 1);

    if (filters.search) {
      params = params.set('search', filters.search);
    }
    if (filters.category_id) {
      params = params.set('category_id', filters.category_id);
    }
    if (filters.status !== null && filters.status !== undefined) {
      params = params.set('status', filters.status);
    }
    if (filters.sort_by) {
      params = params.set('sort_by', filters.sort_by);
    }
    if (filters.sort_dir) {
      params = params.set('sort_dir', filters.sort_dir);
    }

    return this.http.get<PaginatedResponse<Product>>(this.baseUrl, { params });
  }

  create(payload: ProductPayload): Observable<Product> {
    return this.http
      .post<ApiResponse<Product>>(this.baseUrl, payload)
      .pipe(map((response) => response.data));
  }

  update(id: number, payload: ProductPayload): Observable<Product> {
    return this.http
      .put<ApiResponse<Product>>(`${this.baseUrl}/${id}`, payload)
      .pipe(map((response) => response.data));
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/${id}`);
  }
}
