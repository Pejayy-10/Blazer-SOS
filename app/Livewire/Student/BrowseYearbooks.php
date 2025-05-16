<?php

namespace App\Livewire\Student;

use App\Models\YearbookPlatform;
use App\Models\YearbookSubscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BrowseYearbooks extends Component
{
    use WithPagination;

    public $showPastYearbooks = false;
    public $selectedYearbookId = null;
    public $selectedSubscriptionType = null;
    public $selectedJacketSize = null;
    public $showOrderModal = false;
    
    protected $listeners = ['refreshYearbooks' => '$refresh'];

    public function mount()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function showOrderForm($yearbookId)
    {
        $this->selectedYearbookId = $yearbookId;
        $this->selectedSubscriptionType = null;
        $this->selectedJacketSize = null;
        $this->showOrderModal = true;
    }

    public function closeOrderModal()
    {
        $this->showOrderModal = false;
        $this->resetValidation();
    }

    public function togglePastYearbooks()
    {
        $this->showPastYearbooks = !$this->showPastYearbooks;
    }

    public function createSubscription()
    {
        // Validate request
        $this->validate([
            'selectedYearbookId' => 'required|exists:yearbook_platforms,id',
            'selectedSubscriptionType' => 'required|in:digital,physical,premium',
            'selectedJacketSize' => 'required_if:selectedSubscriptionType,physical,premium|in:XS,S,M,L,XL,XXL,XXXL',
        ]);

        // Check if user already has a subscription for this yearbook
        $existingSubscription = YearbookSubscription::where('user_id', Auth::id())
            ->where('yearbook_platform_id', $this->selectedYearbookId)
            ->first();

        if ($existingSubscription) {
            session()->flash('error', 'You already have a subscription for this yearbook.');
            return;
        }

        // Create a new subscription
        try {
            $subscription = YearbookSubscription::create([
                'user_id' => Auth::id(),
                'yearbook_platform_id' => $this->selectedYearbookId,
                'subscription_type' => $this->selectedSubscriptionType,
                'jacket_size' => $this->selectedJacketSize,
                'payment_status' => 'pending',
                'submitted_at' => now(),
            ]);

            session()->flash('message', 'Your yearbook order has been submitted. Please complete payment to confirm your order.');
            $this->closeOrderModal();
            
            // Redirect to payment page (you would need to create this)
            return redirect()->route('student.payment', ['subscription' => $subscription->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'There was an error creating your subscription: ' . $e->getMessage());
        }
    }    public function render()
    {
        try {
            // Get active yearbook platform with its stock
            $activeYearbook = YearbookPlatform::where('is_active', true)->first();
            
            // Get past yearbooks with available stock for purchase if toggle is on
            $pastYearbooks = collect();
            if ($this->showPastYearbooks) {
                try {
                    $pastYearbooks = YearbookPlatform::where('is_active', false)
                        ->where('status', 'archived')
                        ->where('year', '<', now()->year)
                        // No longer use whereHas - rely on accessor method instead
                        ->orderBy('year', 'desc')
                        ->get();
                        
                    // Filter the yearbooks that have available stock directly
                    $pastYearbooks = $pastYearbooks->filter(function ($platform) {
                        $stock = $platform->stock; // Uses the accessor method
                        return $stock && $stock->available_stock > 0;
                    });
                } catch (\Exception $e) {
                    // Log error but continue with empty collection
                    \Illuminate\Support\Facades\Log::error("Error loading past yearbooks: " . $e->getMessage());
                }
            }
            
            // Get user's current subscriptions to disable already purchased yearbooks
            $userSubscriptions = YearbookSubscription::where('user_id', Auth::id())
                ->pluck('yearbook_platform_id')
                ->toArray();
                
            return view('livewire.student.browse-yearbooks', [
                'activeYearbook' => $activeYearbook,
                'pastYearbooks' => $pastYearbooks,
                'userSubscriptions' => $userSubscriptions,
            ]);
        } catch (\Exception $e) {
            // Log the error and show an empty view
            \Illuminate\Support\Facades\Log::error("Fatal error in BrowseYearbooks: " . $e->getMessage());
            return view('livewire.student.browse-yearbooks', [
                'activeYearbook' => null,
                'pastYearbooks' => collect(),
                'userSubscriptions' => [],
                'error' => 'Unable to load yearbooks at this time. Please try again later.'
            ]);
        }
    }
}
