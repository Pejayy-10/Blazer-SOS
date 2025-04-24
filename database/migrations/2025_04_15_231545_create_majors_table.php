<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            // Foreign key to courses table
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Web Development", "Networking"
             // Add unique constraint for name within a course
            $table->unique(['course_id', 'name']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('majors'); }
};