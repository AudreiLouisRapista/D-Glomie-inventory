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
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('inventory_id');
            $table->date('sale_date');
            $table->integer('quantity_sold');
            $table->decimal('total_amount', 10, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches');

            $table->foreign('inventory_id')
                  ->references('id')
                  ->on('inventory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sales');
    }
};
