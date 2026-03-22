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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->contrained('category')
                  ->onDelete('cascade');
            $table->foreignId('perishable_id')
                  ->contrained('perishable')
                  ->onDelete('cascade');
            $table->string('product_name');
            $table->integer('product_quantity');
            $table->integer('product_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
