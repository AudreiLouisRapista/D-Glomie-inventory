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
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('report_id');
            $table->date('expense_date');
            $table->string('label', 255);
            $table->decimal('amount', 10, 2);
            $table->softDeletes();
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
        Schema::dropIfExists('expenses');
    }
};
