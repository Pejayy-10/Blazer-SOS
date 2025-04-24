<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YearbookPlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'name',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year' => 'integer', // Cast year to integer
    ];

    /**
     * Get the yearbook profiles associated with this platform.
     */
    public function yearbookProfiles(): HasMany
    {
        return $this->hasMany(YearbookProfile::class);
    }

    /**
     * Scope a query to only include the active platform.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->first(); // Helper to get the single active one
    }

     /**
      * Ensure only one platform can be active at a time.
      */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($platform) {
            // If this platform is being marked as active,
            // find any OTHER platform that is currently active and deactivate it.
            if ($platform->is_active) {
                static::where('id', '!=', $platform->id)
                      ->where('is_active', true)
                      ->update(['is_active' => false]);
            }
        });
    }
}