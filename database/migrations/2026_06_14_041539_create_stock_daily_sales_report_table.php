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
        Schema::create('daily_sales_report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');
            $table->date('report_date');

            $table->decimal('total_purchases', 10, 2)->default(0);

            $table->decimal('gross_sales', 10, 2)->default(0);
            $table->decimal('less_expenses_stock', 10, 2)->default(0);
            $table->decimal('net_sales', 10, 2)->default(0);

            $table->decimal('total_stockout', 10, 2)->default(0);

            $table->decimal('total_expenses', 10, 2)->default(0);

            $table->decimal('total_cash_sales', 10, 2)->default(0);
            $table->decimal('gcash', 10, 2)->default(0);
            $table->decimal('initial_deposit', 10, 2)->default(0);
            $table->decimal('total_sales', 10, 2)->default(0);
            $table->decimal('difference', 10, 2)->default(0);

            $table->decimal('net_income', 10, 2)->default(0);

            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');

            $table->unique(['branch_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_daily_sales_report');
    }
};
