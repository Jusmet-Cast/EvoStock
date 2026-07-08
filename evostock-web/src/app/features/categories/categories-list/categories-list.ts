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
import { MatTableModule } from '@angular/material/table';
import { MatTooltipModule } from '@angular/material/tooltip';
import { debounceTime, distinctUntilChanged } from 'rxjs';
import { Category } from '../../../core/models/category.model';
import { NotificationService } from '../../../core/services/notification.service';
import { ConfirmDialog } from '../../../shared/components/confirm-dialog/confirm-dialog';
import { CategoriesService } from '../categories.service';
import { CategoryFormDialog } from '../category-form-dialog/category-form-dialog';

@Component({
  selector: 'app-categories-list',
  imports: [
    ReactiveFormsModule,
    MatButtonModule,
    MatChipsModule,
    MatFormFieldModule,
    MatIconModule,
    MatInputModule,
    MatPaginatorModule,
    MatProgressBarModule,
    MatTableModule,
    MatTooltipModule,
  ],
  templateUrl: './categories-list.html',
})
export class CategoriesList {
  private readonly categoriesService = inject(CategoriesService);
  private readonly dialog = inject(MatDialog);
  private readonly notifications = inject(NotificationService);

  readonly displayedColumns = ['name', 'description', 'is_active', 'products_count', 'actions'];
  readonly searchControl = new FormControl('', { nonNullable: true });

  readonly categories = signal<Category[]>([]);
  readonly total = signal(0);
  readonly pageIndex = signal(0);
  readonly loading = signal(false);
  readonly pageSize = 10;

  constructor() {
    this.searchControl.valueChanges
      .pipe(debounceTime(300), distinctUntilChanged(), takeUntilDestroyed())
      .subscribe(() => {
        this.pageIndex.set(0);
        this.load();
      });

    this.load();
  }

  load(): void {
    this.loading.set(true);
    this.categoriesService.list(this.searchControl.value, this.pageIndex() + 1).subscribe({
      next: (response) => {
        this.categories.set(response.data);
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
      .open(CategoryFormDialog, { data: {} })
      .afterClosed()
      .subscribe((result) => {
        if (result) {
          this.notifications.success('Categoría creada correctamente.');
          this.load();
        }
      });
  }

  openEditDialog(category: Category): void {
    this.dialog
      .open(CategoryFormDialog, { data: { category } })
      .afterClosed()
      .subscribe((result) => {
        if (result) {
          this.notifications.success('Categoría actualizada correctamente.');
          this.load();
        }
      });
  }

  confirmDelete(category: Category): void {
    this.dialog
      .open(ConfirmDialog, {
        data: {
          title: 'Eliminar categoría',
          message: `¿Seguro que deseas eliminar "${category.name}"? Esta acción no se puede deshacer.`,
        },
      })
      .afterClosed()
      .subscribe((confirmed) => {
        if (confirmed) {
          this.categoriesService.delete(category.id).subscribe(() => {
            this.notifications.success('Categoría eliminada.');
            this.load();
          });
        }
      });
  }
}
