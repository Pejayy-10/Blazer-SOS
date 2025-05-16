<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up(): void
    {
        // Skip if table already exists (was created manually)
        if (Schema::hasTable('yearbook_stocks')) {
            return;
        }
        
        Schema::create('yearbook_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('yearbook_platform_id')->constrained()->onDelete('cascade');
            $table->integer('initial_stock')->default(0);
            $table->integer('available_stock')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();
            
            // Ensure one stock record per platform
            $table->unique('yearbook_platform_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearbook_stocks');
    }
};
