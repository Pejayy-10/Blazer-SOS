<?php

require __DIR__.'/vendor/autoload.php';

try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "=== Creating Personal Access Tokens Table ===\n\n";
    
    // Explicitly include Schema class
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    
    if (!Schema::hasTable('personal_access_tokens')) {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        
        echo "✓ Successfully created personal_access_tokens table\n";
    } else {
        echo "! personal_access_tokens table already exists\n";
    }
    
    // Now check if the personal_access_tokens table exists
    $exists = Schema::hasTable('personal_access_tokens');
    echo "\nTable status: personal_access_tokens - " . ($exists ? "EXISTS" : "MISSING") . "\n";
    
    if ($exists) {
        // Check the columns in the table to verify it's correctly created
        $columns = Schema::getColumnListing('personal_access_tokens');
        echo "Columns in personal_access_tokens table: " . implode(", ", $columns) . "\n";
    }
    
    echo "\n=== Personal Access Tokens Table Creation Complete ===\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}
