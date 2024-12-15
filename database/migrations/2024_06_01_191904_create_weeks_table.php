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
        Schema::create('weeks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('venue_id')->constrained()->cascadeOnDelete();

            $table->integer('year');
            $table->integer('week');
            $table->foreignUuid('unit_id')->constrained()->cascadeOnDelete();
            $table->unique(['venue_id', 'year', 'week', 'unit_id']);

            $table->string('template_name');
            $table->foreignUuid('template_id')->constrained()->cascadeOnDelete();
            $table->json('template');

            $table->integer('price');
            $table->integer('interval');

            $table->boolean('changed_from_origin')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weeks');
    }
};
