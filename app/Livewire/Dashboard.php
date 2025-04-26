<?php

namespace App\Livewire;

use App\Models\Setting; // Keep if still used elsewhere, otherwise remove
use App\Models\YearbookProfile;
use App\Models\User;
use App\Models\YearbookPlatform; // Import Platform model
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{

    // Add listener for the event emitted by the upload modal
    #[On('platform-image-updated')]
    public function refreshDashboardData()
    {
        // This method will be called when the image is updated.
        // Simply calling $this->render() isn't enough sometimes,
        // we might need to force re-rendering or re-fetch data if needed.
        // In this case, the render method already fetches the active platform,
        // so just letting it re-render might be sufficient.
        // If not, explicitly re-fetch here:
        // $this->activePlatform = YearbookPlatform::active(); // Example
        \Log::debug("Dashboard received platform-image-updated event."); // Log check
    }
    
    // No public properties needed just for display logic in render

    public function render()
    {
        $user = Auth::user();
        $viewData = []; // Array to hold data passed to the view

        // --- Fetch Active Platform for ALL users ---
        // Uses the 'active()' scope defined in the YearbookPlatform model
        $activePlatform = YearbookPlatform::active();
        $viewData['activePlatform'] = $activePlatform;
        // --- End Fetch Active Platform ---


        if ($user->role === 'student') {
            // Eager load the profile for the student view check
            $user->load('yearbookProfile'); // Load relationship
            $viewData['profile'] = $user->yearbookProfile; // Pass profile to the view
            return view('livewire.dashboard-student', $viewData); // Use dedicated view

        }
        else if (in_array($user->role, ['admin', 'superadmin'])) {
            // Fetch stats for Admins/Superadmins

            // Get status directly from the active platform if it exists
            $viewData['platformStatus'] = $activePlatform?->status ?? 'N/A'; // Use N/A if no active platform

            // Count profiles based on status (potentially filter by active platform if needed)
            $platformId = $activePlatform?->id; // Get active platform ID
            $viewData['pendingPaymentsCount'] = YearbookProfile::when($platformId, fn($q) => $q->where('yearbook_platform_id', $platformId))
                                                               ->where('payment_status', 'pending')
                                                               ->where('profile_submitted', true)
                                                               ->count();
            $viewData['registeredPaidCount'] = YearbookProfile::when($platformId, fn($q) => $q->where('yearbook_platform_id', $platformId))
                                                              ->where('payment_status', 'paid') // Or other 'paid' statuses
                                                              ->count();
            $viewData['totalProfilesSubmitted'] = YearbookProfile::when($platformId, fn($q) => $q->where('yearbook_platform_id', $platformId))
                                                                 ->where('profile_submitted', true)
                                                                 ->count();

            // User counts are global, not per-platform
            $viewData['studentCount'] = User::where('role', 'student')->count();
            $viewData['adminCount'] = User::where('role', 'admin')->count();


            // Pass stats and active platform to the admin dashboard view
            return view('livewire.dashboard-admin', $viewData); // Use dedicated admin view
        }
        else {
             // Fallback for unexpected roles? Show student view but pass platform data
             return view('livewire.dashboard-student', ['profile' => null, 'activePlatform' => $activePlatform]);
        }
    }
}