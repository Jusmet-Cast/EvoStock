import { HttpErrorResponse } from '@angular/common/http';
import { Component, inject, signal } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MatButtonModule } from '@angular/material/button';
import {
  MAT_DIALOG_DATA,
  MatDialogModule,
  MatDialogRef,
} from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatSelectModule } from '@angular/material/select';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { ApiValidationError } from '../../../core/models/api-response.model';
import { Category } from '../../../core/models/category.model';
import { Product } from '../../../core/models/product.model';
import { CategoriesService } from '../../categories/categories.service';
import { ProductsService } from '../products.service';

export interface ProductFormDialogData {
  product?: Product;
}

@Component({
  selector: 'app-product-form-dialog',
  imports: [
    ReactiveFormsModule,
    MatButtonModule,
    MatDialogModule,
    MatFormFieldModule,
    MatInputModule,
    MatProgressSpinnerModule,
    MatSelectModule,
    MatSlideToggleModule,
  ],
  templateUrl: './product-form-dialog.html',
  styleUrl: './product-form-dialog.scss',
})
export class ProductFormDialog {
  private readonly fb = inject(FormBuilder);
  private readonly products = inject(ProductsService);
  private readonly categoriesService = inject(CategoriesService);
  private readonly dialogRef = inject(MatDialogRef<ProductFormDialog>);
  readonly data = inject<ProductFormDialogData>(MAT_DIALOG_DATA);

  readonly isEdit = !!this.data.product;
  readonly saving = signal(false);
  readonly categories = signal<Category[]>([]);

  readonly form = this.fb.nonNullable.group({
    name: [this.data.product?.name ?? '', [Validators.required, Validators.maxLength(255)]],
    description: [this.data.product?.description ?? ''],
    price: [this.data.product?.price ?? 0, [Validators.required, Validators.min(0)]],
    stock: [this.data.product?.stock ?? 0, [Validators.required, Validators.min(0)]],
    is_active: [this.data.product?.is_active ?? true],
    category_ids: [this.data.product?.categories.map((category) => category.id) ?? []],
  });

  constructor() {
    this.categoriesService.listAllActive().subscribe((categories) => this.categories.set(categories));
  }

  submit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.saving.set(true);
    const payload = this.form.getRawValue();
    const request = this.isEdit
      ? this.products.update(this.data.product!.id, payload)
      : this.products.create(payload);

    request.subscribe({
      next: (product) => {
        this.saving.set(false);
        this.dialogRef.close(product);
      },
      error: (error: HttpErrorResponse) => {
        this.saving.set(false);
        const validation = error.error as ApiValidationError | undefined;

        for (const field of ['name', 'price', 'stock'] as const) {
          const message = validation?.errors?.[field]?.[0];
          if (message) {
            this.form.controls[field].setErrors({ server: message });
          }
        }
      },
    });
  }

  cancel(): void {
    this.dialogRef.close();
  }
}
