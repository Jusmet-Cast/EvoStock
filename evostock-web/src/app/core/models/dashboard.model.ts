import { Product } from './product.model';

export interface DashboardSummary {
  total_products: number;
  total_categories: number;
  active_products: number;
  inactive_products: number;
  low_stock_products: number;
  latest_products: Product[];
}
