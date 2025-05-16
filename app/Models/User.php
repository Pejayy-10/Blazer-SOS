<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // If using Sanctum for APIs
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable // Optional: implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable; // Add HasApiTokens if needed

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',   // Added
        'last_name',    // Added
        'middle_name',      // <-- Added
        'suffix',           // <-- Added
        'gender',           // <-- Added
        'birthdate',        // <-- Added
        'contact_number',   // <-- Added
        'address_line',     // <-- Added
        'city_province',
        'username',     // Added
        'email',
        'password',
        'role',         // Added (optional here if default is always set, but good practice)
        'role_name',   // Added (optional here if default is always set, but good practice)
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date', // <-- Added cast
    ];

     /**
     * Get the yearbook profile associated with the user.
     */
    public function yearbookProfile(): HasOne
    {
        return $this->hasOne(YearbookProfile::class);
    }
    
    /**
     * Get all yearbook subscriptions for this user.
     */
    public function yearbookSubscriptions(): HasMany
    {
        return $this->hasMany(YearbookSubscription::class);
    }

    /**
     * Get the yearbook photos uploaded by the user.
     */
    public function yearbookPhotos(): HasMany // Add this method
    {
        return $this->hasMany(YearbookPhoto::class)->orderBy('order'); // Order by 'order' column
    }
}