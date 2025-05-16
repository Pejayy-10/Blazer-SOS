<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class YearbookSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'yearbook_platform_id',
        'subscription_type',
        'jacket_size',
        'payment_status',
        'submitted_at',
        'paid_at',
        'payment_confirmed_by',
        'order_notes',
        'shipping_address_id', // Optional reference to a shipping address if needed
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'paid_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who made this subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the yearbook platform for this subscription.
     */
    public function yearbookPlatform(): BelongsTo
    {
        return $this->belongsTo(YearbookPlatform::class);
    }

    /**
     * Get the admin user who confirmed the payment.
     */
    public function paymentConfirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_confirmed_by');
    }

    /**
     * Get the associated yearbook profile (if exists).
     */
    public function yearbookProfile(): BelongsTo
    {
        return $this->belongsTo(YearbookProfile::class, 'user_id', 'user_id')
                    ->where('yearbook_platform_id', $this->yearbook_platform_id);
    }

    /**
     * Get the shipping address for this subscription.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Scope a query to only include subscriptions with a specific payment status.
     */
    public function scopeWithPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }
    
    /**
     * Check if this subscription has a profile submitted for it.
     */
    public function hasProfile()
    {
        return $this->yearbookProfile()->exists();
    }
}
