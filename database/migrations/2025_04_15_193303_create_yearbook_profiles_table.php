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
        Schema::create('yearbook_profiles', function (Blueprint $table) {
            $table->id();

            // Link to the users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users.id

            // Yearbook Profile Fields (nullable allows saving partially)
            $table->string('nickname')->nullable();
            // Academic Structure - Storing as strings for now, could be foreign keys later
            $table->string('college')->nullable();
            $table->string('course')->nullable();
            $table->string('year_and_section')->nullable();
            $table->integer('age')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
             // Email/Contact might be redundant if always same as user table, but included per requirements
            $table->string('contact_number')->nullable();
             // $table->string('email')->nullable(); // Often taken from users table

            // Parents
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();

            // Affiliations (Simple text storage for now)
            $table->string('affiliation_1')->nullable();
            $table->string('affiliation_2')->nullable();
            $table->string('affiliation_3')->nullable();

            $table->text('awards')->nullable(); // Text allows longer entries
            $table->text('mantra')->nullable(); // Text allows longer entries

            // Internal Fields
            $table->string('active_contact_number_internal')->nullable(); // For staff use
            $table->string('subscription_type')->nullable(); // e.g., 'Full', 'Partial'
            $table->string('jacket_size')->nullable(); // e.g., 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'

            // Status Fields (Important for Admin workflow)
            $table->string('payment_status')->default('pending'); // e.g., 'pending', 'paid', 'partial'
            $table->boolean('profile_submitted')->default(false); // Track if form was submitted
            $table->timestamp('submitted_at')->nullable(); // When was profile submitted?
            $table->timestamp('paid_at')->nullable(); // When was payment confirmed?
            $table->softDeletes(); // Adds 'deleted_at' for soft deletes (for Admin 'Deleted' tab)

            $table->timestamps(); // created_at, updated_at

            // Ensure a user can only have one profile (usually)
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearbook_profiles');
    }
};