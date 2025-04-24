<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Bulletin extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image_path', 
        'user_id',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who posted the bulletin.
     */
    public function author(): BelongsTo
    {
        // Assuming 'user_id' is the foreign key
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor for the public image URL.
     */
    public function getImageUrlAttribute(): ?string // Return type is nullable string
    {
        return $this->image_path
                   ? Storage::disk('public')->url($this->image_path)
                   : null; // Return null if no image path
    }

    /**
     * Model Boot Method for handling deletion.
     */
     protected static function boot()
     {
         parent::boot();

         static::deleting(function ($bulletin) {
             // Delete the associated image file from storage if it exists
             if ($bulletin->image_path && Storage::disk('public')->exists($bulletin->image_path)) {
                 Storage::disk('public')->delete($bulletin->image_path);
             }
         });
     }
}