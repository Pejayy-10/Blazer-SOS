<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yearbook_photos', function (Blueprint $table) {
            $table->id();
             // Link to the user who uploaded it
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Could also link to yearbook_profile_id if preferred
            // $table->foreignId('yearbook_profile_id')->constrained()->onDelete('cascade');

            $table->string('path'); // Path relative to the storage disk (e.g., 'yearbook_photos/user_1/photo.jpg')
            $table->string('original_filename')->nullable();
            $table->unsignedInteger('order')->default(0); // Optional: For ordering photos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yearbook_photos');
    }
};