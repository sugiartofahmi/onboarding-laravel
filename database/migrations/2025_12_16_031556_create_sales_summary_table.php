<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_summary', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('sale_date')->unique();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->integer('total_items_sold')->default(0);
            $table->timestamps();

            $table->index('sale_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_summary');
    }
};
