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
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('venue_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('phone_number');
            $table->string('email');
            $table->text('comments')->nullable();

            $table->string('payment_provider');
            $table->integer('payment_amount');
            $table->integer('tax_low')->nullable();
            $table->integer('tax_high')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('payment_status');

            $table->timestamp('date');

            $table->longText('payment_url')->nullable();
            $table->uuid('rebook_id')->nullable();
            $table->uuid('rebook_of')->nullable();

            $table->timestamp('canceled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->foreign('rebook_id')->references('id')->on('reservations');
            $table->foreign('rebook_of')->references('id')->on('reservations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
