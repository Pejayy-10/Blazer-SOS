<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YearbookStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'yearbook_platform_id',
        'initial_stock',
        'available_stock',
        'price',
    ];

    protected $casts = [
        'initial_stock' => 'integer',
        'available_stock' => 'integer',
        'price' => 'decimal:2',
    ];    /**
     * Get the platform that owns this stock information.
     */
    public function yearbookPlatform(): BelongsTo
    {
        return $this->belongsTo(YearbookPlatform::class);
    }
    
    /**
     * Get a default stock record when the real one isn't available
     * This prevents application errors when the database table is missing
     */
    public static function getDefaultStock($platformId = null)
    {
        return (object) [
            'id' => 0,
            'yearbook_platform_id' => $platformId ?? 0,
            'initial_stock' => 100,
            'available_stock' => 100,
            'price' => 2300.00,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
