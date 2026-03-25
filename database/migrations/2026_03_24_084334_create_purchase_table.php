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
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')
                  ->contrained('supplier');

            $table->integer('invoice_number');
            $table->date('invoice_date');
            $table->date('invoice_duo_date');
            $table->integer('invoice_grossAmount');
            $table->integer('invoice_vatAmount');
            $table->integer('invoice_netAmount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
