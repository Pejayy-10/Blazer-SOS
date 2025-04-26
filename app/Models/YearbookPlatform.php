<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class YearbookPlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'name',
        'theme_title',            
        'background_image_path',
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
     * Accessor for the public background image URL.
     */
    public function getBackgroundImageUrlAttribute(): ?string
    {
        return $this->background_image_path
                   ? Storage::disk('public')->url($this->background_image_path)
                   : null; // Return null if no image path
    }

     /**
      * Ensure only one platform can be active at a time.
      */
      protected static function boot()
      {
          parent::boot();
  
          // Handle setting only one platform active
          static::saving(function ($platform) {
              if ($platform->is_active && $platform->isDirty('is_active')) { // Check if is_active changed to true
                  static::where('id', '!=', $platform->id)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
              }
          });
  
           // Delete associated image file when platform is deleted
          static::deleting(function ($platform) {
               if ($platform->background_image_path && Storage::disk('public')->exists($platform->background_image_path)) {
                   Storage::disk('public')->delete($platform->background_image_path);
               }
           });
      }
  }