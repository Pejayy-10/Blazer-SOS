<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yearbook_platforms', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique(); // The primary year identifier (e.g., 2024)
            $table->string('name'); // Display name (e.g., "AY 2024-2025 Yearbook")
            // Status options: setup, open, closed, printing, archived etc.
            $table->string('status')->default('setup');
            $table->boolean('is_active')->default(false); // Flag to mark the platform for new registrations
            $table->timestamps();

            $table->index('is_active'); // Index for quick lookup of active platform
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yearbook_platforms');
    }
};