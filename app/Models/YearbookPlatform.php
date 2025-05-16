<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
     * Get the yearbook subscriptions associated with this platform.
     */
    public function yearbookSubscriptions(): HasMany
    {
        return $this->hasMany(YearbookSubscription::class);
    }
    
    /**
     * Get the stock information for this platform.
     */
    public function stock(): HasOne
    {
        return $this->hasOne(YearbookStock::class);
    }
    
    /**
     * Ensure every platform has a stock record when accessed.
     * This serves as a fallback method to prevent errors when the stock table is missing or empty.
     */
    public function getStockAttribute()
    {
        try {
            $stockRecord = $this->stock()->first();
            
            // If no stock record exists, try to create one
            if (!$stockRecord) {
                try {
                    $stockRecord = YearbookStock::create([
                        'yearbook_platform_id' => $this->id,
                        'initial_stock' => 100,  // Default initial stock
                        'available_stock' => 100, // Default available stock
                        'price' => 2300.00, // Default price (₱2,300)
                    ]);
                } catch (\Exception $e) {
                    // If creation fails, return a default object
                    return YearbookStock::getDefaultStock($this->id);
                }
            }
            
            return $stockRecord;
        } catch (\Exception $e) {
            // Handle any database or relationship errors
            // Return a default stock object to prevent application errors
            return YearbookStock::getDefaultStock($this->id);
        }
    }

    /**
     * Scope a query to only include the active platform.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->first(); // Helper to get the single active one
    }

    /**
     * Scope a query to get current year platform only
     */
    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }

    /**
     * Scope a query to get past years platforms
     */
    public function scopePastYears($query)
    {
        return $query->where('year', '<', now()->year);
    }
    
    /**
     * Scope a query to get platforms with available stock
     */
    public function scopeWithAvailableStock($query)
    {
        return $query->whereHas('stock', function ($query) {
            $query->where('available_stock', '>', 0);
        });
    }
    
    /**
     * Scope a query to get platforms that are available to subscribe to
     * (either active current year or past years with stock)
     */
    public function scopeAvailableForSubscription($query)
    {
        return $query->where(function ($query) {
            // Current active platform
            $query->where('is_active', true)
                  ->where('year', now()->year);
        })->orWhere(function ($query) {
            // Past years with stock, only archived status
            $query->where('year', '<', now()->year)
                  ->where('status', 'archived')
                  ->whereHas('stock', function ($q) {
                      $q->where('available_stock', '>', 0);
                  });
        });
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
      * Ensure only one platform can be active at a time,
      * and strictly prevent setting past year platforms as active.
      */
      protected static function boot()
      {
          parent::boot();
  
          // Handle setting only one platform active
          static::saving(function ($platform) {
              // Check if platform is being set to active
              if ($platform->is_active && $platform->isDirty('is_active')) {
                  // Check if this platform is for a past year
                  $currentYear = now()->year;
                  if ($platform->year < $currentYear) {
                      // Reset is_active to false and don't allow activation
                      $platform->is_active = false;
                      return false;
                  }
                  
                  // If allowed, deactivate all other platforms
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
      
      /**
      * Check if this platform is for a past year
      */
      public function isPastYear(): bool
      {
          return $this->year < now()->year;
      }
      
      /**
      * Check if this platform has available stock
      */
      public function hasAvailableStock(): bool
      {
          return $this->stock && $this->stock->available_stock > 0;
      }

      /**
       * Check if subscription is allowed for this platform
       */
      public function allowsSubscription(): bool
      {
          // Current year and active
          if ($this->is_active && $this->year == now()->year) {
              return true;
          }
          
          // Past year with available stock and archived status
          if ($this->year < now()->year && 
              $this->status === 'archived' && 
              $this->hasAvailableStock()) {
              return true;
          }
          
          return false;
      }
}