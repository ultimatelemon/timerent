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
        Schema::create('venues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('support_code', 6)->nullable();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('subdomain')->unique();

            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('phone_number_support')->nullable();
            $table->integer('coc_number')->nullable();
            $table->string('tax_number')->nullable();

            $table->string('payment_service_provider')->nullable();
            $table->longText('payment_api_key')->nullable();
            $table->string('reservation_prefix')->nullable()->default('TR-');
            $table->integer('cancellation_hours')->nullable();

            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_connect_id')->nullable();
            $table->boolean('stripe_connect_onboarded')->nullable()->default(false);
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamp('stripe_current_period_ends_at')->nullable();

            $table->timestamp('canceled_at')->nullable();

            $table->foreignUuid('plan_id')->constrained();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
