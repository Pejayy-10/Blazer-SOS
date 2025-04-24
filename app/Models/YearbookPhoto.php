<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class YearbookPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'path',
        'original_filename',
        'order',
    ];

    /**
     * Get the user that owns the photo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor to get the public URL for the photo.
     * Assumes using the 'public' disk.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Override the delete method to also remove the file from storage.
     */
     protected static function boot()
     {
         parent::boot();

         static::deleting(function ($photo) {
             // Delete the file from the public disk when the model record is deleted
             if (Storage::disk('public')->exists($photo->path)) {
                 Storage::disk('public')->delete($photo->path);
             }
         });
     }
}