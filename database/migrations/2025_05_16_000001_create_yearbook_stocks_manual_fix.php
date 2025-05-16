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
        if (!Schema::hasTable('yearbook_stocks')) {
            Schema::create('yearbook_stocks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('yearbook_platform_id');
                $table->integer('initial_stock')->default(0);
                $table->integer('available_stock')->default(0);
                $table->decimal('price', 10, 2)->nullable();
                $table->timestamps();
                
                // Ensure one stock record per platform
                $table->unique('yearbook_platform_id');
                
                // Add foreign key if yearbook_platforms table exists
                if (Schema::hasTable('yearbook_platforms')) {
                    $table->foreign('yearbook_platform_id')
                        ->references('id')
                        ->on('yearbook_platforms')
                        ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearbook_stocks');
    }
};
