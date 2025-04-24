<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('colleges', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "College of Computer Studies"
            $table->string('abbreviation')->nullable(); // e.g., "CCS"
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('colleges'); }
};