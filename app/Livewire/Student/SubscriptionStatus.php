<?php

namespace App\Livewire\Student;

use App\Models\YearbookProfile;
use App\Models\YearbookPlatform;
use App\Models\YearbookSubscription;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Subscription Status')]
class SubscriptionStatus extends Component
{
    public ?YearbookProfile $profile = null;
    public bool $hasSubmittedProfile = false;
    public string $paymentStatus = 'not_started'; // Default status
    public string $overallStatusMessage = '';
    public string $nextStepMessage = '';
    
    // For current and past yearbooks
    public ?YearbookPlatform $activePlatform = null;
    public Collection $pastYearbookPlatforms;
    public Collection $userSubscriptions;
    
    // For new yearbook subscription
    public ?int $selectedPlatformId = null;

    public function mount()
    {
        // Get user's profile for active platform (if any)
        $this->activePlatform = YearbookPlatform::active();
        $this->profile = Auth::user()->yearbookProfile()->first(); // Get active profile or null

        // Get all user's subscriptions
        $this->userSubscriptions = YearbookSubscription::where('user_id', Auth::id())
            ->with('yearbookPlatform')
            ->get();

        // Get past yearbooks with available stock
        $this->pastYearbookPlatforms = YearbookPlatform::pastYears()
            ->withAvailableStock()
            ->where('status', 'archived')
            ->orderBy('year', 'desc')
            ->get();

        if ($this->profile) {
            $this->hasSubmittedProfile = $this->profile->profile_submitted;
            $this->paymentStatus = $this->profile->payment_status ?? 'pending'; // Default to pending if profile exists but status is null

             // Determine overall status message for active platform
             if ($this->paymentStatus === 'paid' && $this->hasSubmittedProfile) {
                 $this->overallStatusMessage = 'Your yearbook subscription is complete and confirmed!';
                 $this->nextStepMessage = 'Thank you! No further action is needed regarding your basic subscription.';
             } elseif ($this->paymentStatus === 'paid' && !$this->hasSubmittedProfile) {
                 $this->overallStatusMessage = 'Payment confirmed, but your profile is not yet submitted.';
                 $this->nextStepMessage = 'Please complete and submit your Yearbook Profile information.';
             } elseif ($this->paymentStatus === 'pending' && $this->hasSubmittedProfile) {
                 $this->overallStatusMessage = 'Your profile has been submitted and is awaiting payment confirmation.';
                 $this->nextStepMessage = 'Please proceed to the designated office to complete your payment. An admin will update your status once payment is verified.';
             } elseif ($this->paymentStatus === 'pending' && !$this->hasSubmittedProfile) {
                 // This state shouldn't really happen with the current registration flow
                 $this->overallStatusMessage = 'Your profile has not been submitted yet.';
                  $this->nextStepMessage = 'Please fill out and submit your Yearbook Profile form. Payment is due after submission.';
             } else {
                 // Catch other potential statuses or edge cases
                 $this->overallStatusMessage = 'Please submit your profile to initiate your subscription.';
                 $this->nextStepMessage = 'Visit the Yearbook Profile page to get started.';
             }

        } else {
            // No profile record exists at all
            $this->hasSubmittedProfile = false;
            $this->paymentStatus = 'not_started';
            $this->overallStatusMessage = 'You have not started your yearbook subscription process yet.';
            $this->nextStepMessage = 'Please visit the Yearbook Profile page to fill out your information and begin your subscription.';
        }
    }

    /**
     * Subscribe to a past yearbook
     */
    public function subscribeToPastYearbook()
    {
        // Validate platform selection
        $this->validate([
            'selectedPlatformId' => 'required|integer|exists:yearbook_platforms,id',
        ]);
        
        // Get the platform
        $platform = YearbookPlatform::find($this->selectedPlatformId);
        
        // Check if it's valid for subscription
        if (!$platform || !$platform->allowsSubscription()) {
            session()->flash('error', 'This yearbook is not available for subscription.');
            return;
        }
        
        // Check if user already has a subscription for this platform
        $existingSubscription = YearbookSubscription::where('user_id', Auth::id())
            ->where('yearbook_platform_id', $platform->id)
            ->first();
            
        if ($existingSubscription) {
            session()->flash('error', 'You already have a subscription for this yearbook.');
            return;
        }
        
        // Check stock availability
        if (!$platform->hasAvailableStock()) {
            session()->flash('error', 'This yearbook is out of stock.');
            return;
        }
        
        // Create subscription
        YearbookSubscription::create([
            'user_id' => Auth::id(),
            'yearbook_platform_id' => $platform->id,
            'subscription_type' => 'full_package', // Default for past yearbooks
            'payment_status' => 'pending',
            'submitted_at' => now(),
            'order_notes' => 'Past yearbook subscription',
        ]);
        
        // Refresh subscriptions list
        $this->userSubscriptions = YearbookSubscription::where('user_id', Auth::id())
            ->with('yearbookPlatform')
            ->get();
            
        session()->flash('message', 'Subscription to ' . $platform->name . ' (' . $platform->year . ') has been created. Please proceed with payment.');
        
        // Reset selection
        $this->selectedPlatformId = null;
    }
    
    /**
     * Cancel a pending subscription
     */
    public function cancelSubscription($subscriptionId)
    {
        $subscription = YearbookSubscription::where('id', $subscriptionId)
            ->where('user_id', Auth::id())
            ->where('payment_status', 'pending')
            ->first();
            
        if (!$subscription) {
            session()->flash('error', 'Cannot cancel this subscription or it does not exist.');
            return;
        }
        
        // Soft delete the subscription
        $subscription->delete();
        
        // Refresh subscriptions list
        $this->userSubscriptions = YearbookSubscription::where('user_id', Auth::id())
            ->with('yearbookPlatform')
            ->get();
            
        session()->flash('message', 'Subscription cancelled successfully.');
    }

    public function render()
    {
        // Get available platforms for dropdown selection
        $availablePlatforms = YearbookPlatform::pastYears()
            ->withAvailableStock()
            ->where('status', 'archived')
            ->orderBy('year', 'desc')
            ->get()
            ->filter(function ($platform) {
                // Filter out platforms user already has subscriptions for
                return !$this->userSubscriptions->contains('yearbook_platform_id', $platform->id);
            });
            
        return view('livewire.student.subscription-status', [
            'availablePlatforms' => $availablePlatforms,
        ]);
    }
}