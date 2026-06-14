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
        Schema::create('stock_transfer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('from_branch_id');
            $table->unsignedBigInteger('to_branch_id');
            $table->unsignedBigInteger('product_id');
            $table->date('transfer_date');
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches');

            $table->foreign('from_branch_id')
                  ->references('id')
                  ->on('branches');

            $table->foreign('to_branch_id')
                  ->references('id')
                  ->on('branches');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfer');
    }
};
