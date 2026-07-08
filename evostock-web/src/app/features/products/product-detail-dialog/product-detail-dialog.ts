import { CurrencyPipe, DatePipe } from '@angular/common';
import { Component, inject } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { MatChipsModule } from '@angular/material/chips';
import { MatDialogModule, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatIconModule } from '@angular/material/icon';
import { Product } from '../../../core/models/product.model';
import { categoryChipClass } from '../../../shared/utils/category-color';

export interface ProductDetailDialogData {
  product: Product;
}

@Component({
  selector: 'app-product-detail-dialog',
  imports: [CurrencyPipe, DatePipe, MatButtonModule, MatChipsModule, MatDialogModule, MatIconModule],
  templateUrl: './product-detail-dialog.html',
  styleUrl: './product-detail-dialog.scss',
})
export class ProductDetailDialog {
  private readonly dialogRef = inject(MatDialogRef<ProductDetailDialog>);
  readonly product = inject<ProductDetailDialogData>(MAT_DIALOG_DATA).product;
  readonly categoryChipClass = categoryChipClass;

  close(): void {
    this.dialogRef.close();
  }

  edit(): void {
    this.dialogRef.close('edit');
  }
}
