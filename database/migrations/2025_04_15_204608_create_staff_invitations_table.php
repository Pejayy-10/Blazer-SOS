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
        Schema::create('staff_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // Invite only one user per email at a time
            $table->string('role_name'); // The descriptive role assigned (e.g., "Editor")
            $table->string('token', 64)->unique(); // Unique, secure token for the registration link
            $table->timestamp('expires_at'); // When the invitation link expires
            $table->timestamp('registered_at')->nullable(); // When the user registered using this token
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_invitations');
    }
};