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
        Schema::create('denom', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('report_id');
            $table->date('denom_date');
            $table->integer('bill_1000')->default(0);
            $table->integer('bill_500')->default(0);
            $table->integer('bill_200')->default(0);
            $table->integer('bill_100')->default(0);
            $table->integer('bill_50')->default(0);
            $table->integer('bill_20')->default(0);
            $table->decimal('coins', 10, 2)->default(0);
            $table->decimal('total_cash', 10, 2)->default(0);
            $table->decimal('gcash', 10, 2)->default(0);
            $table->decimal('initial_deposit', 10, 2)->default(0);
            $table->decimal('total_sales', 10, 2)->default(0);
            $table->decimal('difference', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches');

            $table->foreign('report_id')
                  ->references('id')
                  ->on('daily_sales_report');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denom');
    }
};
