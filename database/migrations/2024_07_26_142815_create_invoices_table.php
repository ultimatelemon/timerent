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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('email');
            $table->string('phone_number');

            $table->integer('payment_amount');
            $table->integer('revenue_high')->nullable();
            $table->integer('revenue_low')->nullable();
            $table->integer('tax_low')->nullable();
            $table->integer('tax_high')->nullable();

            $table->string('payment_id')->nullable();
            $table->string('payment_status');
            $table->string('payment_url')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->foreignUuid('sent_by')->nullable()->references('id')->on('users');

            $table->timestamp('paid_at')->nullable();

            $table->foreignUuid('reservation_id')->constrained();
            $table->foreignUuid('venue_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
