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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('venue_id')->constrained();
            $table->string('title');
            $table->string('message');
            $table->string('status')->default('open');
            $table->string('type');
            $table->foreignUuid('emplooye_id')->nullable()->references('id')->on('employees');
            $table->foreignUuid('user_id')->nullable()->constrained();
            $table->timestamp('first_responded_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
