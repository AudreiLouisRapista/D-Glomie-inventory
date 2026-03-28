<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->contrained('category');
            $table->foreignId('product_id')
                  ->contrained('product');
            $table->foreignId('status_id')
                  ->contrained('status');        
            $table->integer('inventory_startingQty');
            $table->integer('inventory_newQty');
            $table->integer('inventory_sellingPrice');
            $table->integer('inventory_totalSold');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
