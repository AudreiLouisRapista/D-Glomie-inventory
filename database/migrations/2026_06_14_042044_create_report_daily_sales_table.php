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
        Schema::create('report_daily_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('daily_sale_id');
            $table->timestamps();

            $table->foreign('report_id')
                  ->references('id')
                  ->on('daily_sales_report');

            $table->foreign('daily_sale_id')
                  ->references('id')
                  ->on('daily_sales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_daily_sales');
    }
};
