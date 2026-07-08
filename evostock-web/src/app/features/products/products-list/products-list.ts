import { CurrencyPipe } from '@angular/common';
import { Component, inject, signal } from '@angular/core';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import { FormControl, ReactiveFormsModule } from '@angular/forms';
import { MatButtonModule } from '@angular/material/button';
import { MatChipsModule } from '@angular/material/chips';
import { MatDialog } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatPaginatorModule, PageEvent } from '@angular/material/paginator';
import { MatProgressBarModule } from '@angular/material/progress-bar';
import { MatSelectModule } from '@angular/material/select';
import { MatTableModule } from '@angular/material/table';
import { MatTooltipModule } from '@angular/material/tooltip';
import { debounceTime, distinctUntilChanged } from 'rxjs';
import { Category } from '../../../core/models/category.model';
import { Product } from '../../../core/models/product.model';
import { NotificationService } from '../../../core/services/notification.service';
import { ConfirmDialog } from '../../../shared/components/confirm-dialog/confirm-dialog';
import { CategoriesService } from '../../categories/categories.service';
import { ProductFormDialog } from '../product-form-dialog/product-form-dialog';
import { ProductsService } from '../products.service';

type SortOption = {
  label: string;
  sortBy: 'name' | 'created_at';
  sortDir: 'asc' | 'desc';
};

const SORT_OPTIONS: SortOption[] = [
  { label: 'Nombre (A-Z)', sortBy: 'name', sortDir: 'asc' },
  { label: 'Nombre (Z-A)', sortBy: 'name', sortDir: 'desc' },
  { label: 'Más recientes primero', sortBy: 'created_at', sortDir: 'desc' },
  { label: 'Más antiguos primero', sortBy: 'created_at', sortDir: 'asc' },
];

@Component({
  selector: 'app-products-list',
  imports: [
    CurrencyPipe,
    ReactiveFormsModule,
    MatButtonModule,
    MatChipsModule,
    MatFormFieldModule,
    MatIconModule,
    MatInputModule,
    MatPaginatorModule,
    MatProgressBarModule,
    MatSelectModule,
    MatTableModule,
    MatTooltipModule,
  ],
  templateUrl: './products-list.html',
  styleUrl: './products-list.scss',
})
export class ProductsList {
  private readonly productsService = inject(ProductsService);
  private readonly categoriesService = inject(CategoriesService);
  private readonly dialog = inject(MatDialog);
  private readonly notifications = inject(NotificationService);

  readonly displayedColumns = ['name', 'price', 'stock', 'categories', 'is_active', 'actions'];
  readonly sortOptions = SORT_OPTIONS;

  readonly searchControl = new FormControl('', { nonNullable: true });
  readonly categoryControl = new FormControl<number | null>(null);
  readonly statusControl = new FormControl<boolean | null>(null);
  readonly sortControl = new FormControl(0, { nonNullable: true });

  readonly categories = signal<Category[]>([]);
  readonly products = signal<Product[]>([]);
  readonly total = signal(0);
  readonly pageIndex = signal(0);
  readonly loading = signal(false);
  readonly pageSize = 10;

  constructor() {
    this.categoriesService.listAllActive().subscribe((categories) => this.categories.set(categories));

    this.searchControl.valueChanges
      .pipe(debounceTime(300), distinctUntilChanged(), takeUntilDestroyed())
      .subscribe(() => this.reload());

    this.categoryControl.valueChanges.pipe(takeUntilDestroyed()).subscribe(() => this.reload());
    this.statusControl.valueChanges.pipe(takeUntilDestroyed()).subscribe(() => this.reload());
    this.sortControl.valueChanges.pipe(takeUntilDestroyed()).subscribe(() => this.reload());

    this.load();
  }

  private reload(): void {
    this.pageIndex.set(0);
    this.load();
  }

  load(): void {
    const sort = this.sortOptions[this.sortControl.value];

    this.loading.set(true);
    this.productsService
      .list({
        search: this.searchControl.value,
        category_id: this.categoryControl.value,
        status: this.statusControl.value,
        sort_by: sort.sortBy,
        sort_dir: sort.sortDir,
        page: this.pageIndex() + 1,
        per_page: this.pageSize,
      })
      .subscribe({
        next: (response) => {
          this.products.set(response.data);
          this.total.set(response.meta.total);
          this.loading.set(false);
        },
        error: () => this.loading.set(false),
      });
  }

  onPageChange(event: PageEvent): void {
    this.pageIndex.set(event.pageIndex);
    this.load();
  }

  openCreateDialog(): void {
    this.dialog
      .open(ProductFormDialog, { data: {} })
      .afterClosed()
      .subscribe((result) => {
        if (result) {
          this.notifications.success('Producto creado correctamente.');
          this.load();
        }
      });
  }

  openEditDialog(product: Product): void {
    this.dialog
      .open(ProductFormDialog, { data: { product } })
      .afterClosed()
      .subscribe((result) => {
        if (result) {
          this.notifications.success('Producto actualizado correctamente.');
          this.load();
        }
      });
  }

  confirmDelete(product: Product): void {
    this.dialog
      .open(ConfirmDialog, {
        data: {
          title: 'Eliminar producto',
          message: `¿Seguro que deseas eliminar "${product.name}"? Esta acción no se puede deshacer.`,
        },
      })
      .afterClosed()
      .subscribe((confirmed) => {
        if (confirmed) {
          this.productsService.delete(product.id).subscribe(() => {
            this.notifications.success('Producto eliminado.');
            this.load();
          });
        }
      });
  }
}
