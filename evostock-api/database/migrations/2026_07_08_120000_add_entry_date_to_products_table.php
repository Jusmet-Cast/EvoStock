<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->date('entry_date')->nullable()->after('stock');
            $table->index('entry_date');
        });

        // Backfill existing rows so the new column reflects when each product
        // was first registered (its creation date) instead of staying null.
        DB::table('products')
            ->whereNull('entry_date')
            ->update(['entry_date' => DB::raw('DATE(created_at)')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['entry_date']);
            $table->dropColumn('entry_date');
        });
    }
};
