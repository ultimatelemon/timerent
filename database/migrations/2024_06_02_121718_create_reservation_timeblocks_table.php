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
        Schema::create('reservation_timeblocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('venue_id')->constrained();
            $table->foreignUuid('reservation_id')->constrained();
            $table->foreignUuid('unit_id')->constrained();

            $table->string('unit_name');
            $table->integer('price');
            $table->integer('interval');
            $table->string('from');
            $table->string('to');

            $table->timestamp('canceled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_timeblocks');
    }
};
