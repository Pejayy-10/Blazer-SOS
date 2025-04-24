<?php

namespace App\Livewire\Admin;

use App\Models\YearbookProfile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Optional for explicit handling

#[Layout('components.layouts.app')]
#[Title('View Subscription Details')]
class ViewSubscriptionDetails extends Component
{
    // Use PHP 8 constructor property promotion if desired, or declare publicly
    public YearbookProfile $profile; // Route model binding injects this

    // public function mount(int $profileId) // Manual loading example
    // {
    //     try {
    //          // Eager load the user relationship
    //          $this->profile = YearbookProfile::with('user')->findOrFail($profileId);
    //      } catch (ModelNotFoundException $e) {
    //          session()->flash('error', 'Subscription profile not found.');
    //          return redirect()->route('admin.subscriptions.index');
    //      }
    // }


    // Mount method using Route Model Binding
    // Laravel automatically finds the YearbookProfile or throws a 404
    public function mount(YearbookProfile $profile)
    {
        // Eager load the user relationship if not automatically loaded
        // (often good practice to be explicit)
        $this->profile = $profile->load(['user', 'college', 'course', 'major']); // Added 'major'
    }


    public function render()
    {
        // Pass the profile (already loaded in mount) to the view
        return view('livewire.admin.view-subscription-details');
    }
}