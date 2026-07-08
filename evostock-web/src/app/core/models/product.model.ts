import { Category } from './category.model';

export interface Product {
  id: number;
  name: string;
  description: string | null;
  price: number;
  stock: number;
  is_active: boolean;
  categories: Category[];
  created_at: string;
  updated_at: string;
}

export interface ProductPayload {
  name: string;
  description: string | null;
  price: number;
  stock: number;
  is_active: boolean;
  category_ids: number[];
}

export interface ProductFilters {
  search?: string;
  category_id?: number | null;
  status?: boolean | null;
  sort_by?: 'name' | 'created_at';
  sort_dir?: 'asc' | 'desc';
  page?: number;
  per_page?: number;
}
