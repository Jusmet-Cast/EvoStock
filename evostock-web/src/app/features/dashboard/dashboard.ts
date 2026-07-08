import { CurrencyPipe } from '@angular/common';
import { Component, inject, signal } from '@angular/core';
import { MatCardModule } from '@angular/material/card';
import { MatChipsModule } from '@angular/material/chips';
import { MatIconModule } from '@angular/material/icon';
import { MatProgressBarModule } from '@angular/material/progress-bar';
import { MatTableModule } from '@angular/material/table';
import { DashboardSummary } from '../../core/models/dashboard.model';
import { DashboardService } from './dashboard.service';

interface StatCard {
  label: string;
  value: number;
  icon: string;
  accent?: 'warn';
}

@Component({
  selector: 'app-dashboard',
  imports: [
    CurrencyPipe,
    MatCardModule,
    MatChipsModule,
    MatIconModule,
    MatProgressBarModule,
    MatTableModule,
  ],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.scss',
})
export class Dashboard {
  private readonly dashboardService = inject(DashboardService);

  readonly displayedColumns = ['name', 'price', 'stock', 'is_active'];
  readonly loading = signal(true);
  readonly summary = signal<DashboardSummary | null>(null);
  readonly cards = signal<StatCard[]>([]);

  constructor() {
    this.dashboardService.summary().subscribe({
      next: (summary) => {
        this.summary.set(summary);
        this.cards.set([
          { label: 'Total de productos', value: summary.total_products, icon: 'inventory_2' },
          { label: 'Total de categorías', value: summary.total_categories, icon: 'category' },
          { label: 'Productos activos', value: summary.active_products, icon: 'check_circle' },
          { label: 'Productos inactivos', value: summary.inactive_products, icon: 'cancel' },
          {
            label: 'Bajo inventario (< 10)',
            value: summary.low_stock_products,
            icon: 'warning',
            accent: 'warn',
          },
        ]);
        this.loading.set(false);
      },
      error: () => this.loading.set(false),
    });
  }
}
