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
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { ApiValidationError } from '../../../core/models/api-response.model';
import { Category } from '../../../core/models/category.model';
import { CategoriesService } from '../categories.service';

export interface CategoryFormDialogData {
  category?: Category;
}

@Component({
  selector: 'app-category-form-dialog',
  imports: [
    ReactiveFormsModule,
    MatButtonModule,
    MatDialogModule,
    MatFormFieldModule,
    MatInputModule,
    MatProgressSpinnerModule,
    MatSlideToggleModule,
  ],
  templateUrl: './category-form-dialog.html',
  styleUrl: './category-form-dialog.scss',
})
export class CategoryFormDialog {
  private readonly fb = inject(FormBuilder);
  private readonly categories = inject(CategoriesService);
  private readonly dialogRef = inject(MatDialogRef<CategoryFormDialog>);
  readonly data = inject<CategoryFormDialogData>(MAT_DIALOG_DATA);

  readonly isEdit = !!this.data.category;
  readonly saving = signal(false);

  readonly form = this.fb.nonNullable.group({
    name: [this.data.category?.name ?? '', [Validators.required, Validators.maxLength(255)]],
    description: [this.data.category?.description ?? ''],
    is_active: [this.data.category?.is_active ?? true],
  });

  submit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.saving.set(true);
    const payload = this.form.getRawValue();
    const request = this.isEdit
      ? this.categories.update(this.data.category!.id, payload)
      : this.categories.create(payload);

    request.subscribe({
      next: (category) => {
        this.saving.set(false);
        this.dialogRef.close(category);
      },
      error: (error: HttpErrorResponse) => {
        this.saving.set(false);
        const validation = error.error as ApiValidationError | undefined;
        const nameError = validation?.errors?.['name']?.[0];

        if (nameError) {
          this.form.controls.name.setErrors({ server: nameError });
        }
      },
    });
  }

  cancel(): void {
    this.dialogRef.close();
  }
}
