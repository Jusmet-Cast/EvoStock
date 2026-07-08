export interface Category {
  id: number;
  name: string;
  description: string | null;
  is_active: boolean;
  products_count?: number;
  created_at: string;
  updated_at: string;
}

export interface CategoryPayload {
  name: string;
  description: string | null;
  is_active: boolean;
}
