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
        Schema::create('month_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('venue_id')->constrained();
            $table->integer('month');
            $table->integer('year');

            $table->integer('revenue_high');
            $table->integer('revenue_low');

            $table->integer('tax_amount_high');
            $table->integer('tax_amount_low');

            $table->integer('customer_count');
            $table->integer('reservation_count');


            $table->timestamp('period_from');
            $table->timestamp('period_to');
            $table->date('available_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('month_reports');
    }
};
