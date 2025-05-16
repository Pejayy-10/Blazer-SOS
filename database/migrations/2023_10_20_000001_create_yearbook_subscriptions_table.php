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
        Schema::create('yearbook_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('yearbook_platform_id')->constrained()->onDelete('cascade');
            $table->string('subscription_type')->nullable(); // e.g., "digital", "physical", "premium"
            $table->string('jacket_size')->nullable(); // Only relevant for physical subscriptions
            $table->string('payment_status')->default('pending'); // pending, paid, confirmed, cancelled, refunded
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('payment_confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('order_notes')->nullable(); // For admin notes or special instructions
            $table->foreignId('shipping_address_id')->nullable(); // Optional reference to a shipping address
            $table->timestamps();
            $table->softDeletes();
            
            // Create a unique constraint to prevent duplicate subscriptions for the same platform/user
            $table->unique(['user_id', 'yearbook_platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearbook_subscriptions');
    }
};
