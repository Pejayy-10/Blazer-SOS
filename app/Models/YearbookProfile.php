<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo
use Illuminate\Database\Eloquent\SoftDeletes;

class YearbookProfile extends Model
{
    use HasFactory, SoftDeletes;

    // Ensure college_id, course_id, major_id are in $fillable
    protected $fillable = [
        'user_id',
        'nickname',
        'middle_name', // Added field
        'college_id', // Added ID
        'course_id',  // Added ID
        'major_id',   // Added ID (Ensure this was added via migration)
        'yearbook_platform_id', // Added ID
        'year_and_section',
        'age',
        'birth_date',
        'street_address',    // <-- Add
        'city',              // <-- Add
        'province_state',    // <-- Add
        'zip_code',          // <-- Add
        'country',           // <-- Add
        'contact_number',
        'mother_name',
        'father_name',
        'affiliation_1',
        'affiliation_2',
        'affiliation_3',
        'awards',
        'mantra',
        'active_contact_number_internal',
        'subscription_type',
        'jacket_size',
        'payment_status',
        'profile_submitted',
        'submitted_at',
        'paid_at',
        'payment_confirmed_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'profile_submitted' => 'boolean',
        'submitted_at' => 'datetime',
        'paid_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /** Relationship to User */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Relationship to College */
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    /** Relationship to Course */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** Relationship to Major (Optional) */
    public function major(): BelongsTo
    {
        // Assuming you have a Major model and major_id column
        return $this->belongsTo(Major::class);
    }

    public function yearbookPlatform(): BelongsTo // <-- ADD THIS
    {
        return $this->belongsTo(YearbookPlatform::class);
    }   

    /**
     * Get the user (Admin) who confirmed the payment.
     */
    public function paymentConfirmer(): BelongsTo // <-- ADD THIS RELATIONSHIP
    {
        // Using 'payment_confirmed_by' as the foreign key linking to the User model
        return $this->belongsTo(User::class, 'payment_confirmed_by');
    }

    /** Accessor for public photo URL */
    public function getUrlAttribute(): string
    {
         // Assuming YearbookPhoto model exists and has path/url logic
         // This part needs adjustment based on how you store the primary photo
         // Example placeholder:
         // $primaryPhoto = $this->yearbookPhotos()->orderBy('order')->first();
         // return $primaryPhoto ? $primaryPhoto->url : asset('images/default-avatar.png');
         return asset('images/default-avatar.png'); // Placeholder default
    }
}