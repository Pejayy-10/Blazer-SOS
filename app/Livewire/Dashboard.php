<?php

namespace App\Livewire;

use App\Models\Setting; // Import Setting model
use App\Models\YearbookProfile; // Import YearbookProfile model
use App\Models\User; // Import User model (for total students/admins)
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    // // Remove these - no longer needed
    // public string $userRole = 'Guest';
    // public function mount()
    // {
    //     $this->userRole = session('simulated_role', 'Guest');
    // }

    public function render()
    {
        $user = Auth::user();
        $viewData = []; // Array to hold data passed to the view

        if ($user->role === 'student') {
            // For students, we don't need extra data besides what Bulletin fetches itself
            return view('livewire.dashboard-student'); // Use a dedicated student view
        }
        else if (in_array($user->role, ['admin', 'superadmin'])) {
            // Fetch stats for Admins/Superadmins
            $platformStatusSetting = Setting::where('key', 'yearbook_status')->first();
            $viewData['platformStatus'] = $platformStatusSetting?->value ?? 'closed'; // Default to closed

            // Count profiles based on status
            $viewData['pendingPaymentsCount'] = YearbookProfile::where('payment_status', 'pending')
                                                               ->where('profile_submitted', true) // Only count if profile is done
                                                               ->count();
            $viewData['registeredPaidCount'] = YearbookProfile::where('payment_status', 'paid') // Or other 'paid' statuses
                                                              ->count();
            $viewData['totalProfilesSubmitted'] = YearbookProfile::where('profile_submitted', true)->count();

            // Example: Count users by role (Optional)
            $viewData['studentCount'] = User::where('role', 'student')->count();
            $viewData['adminCount'] = User::where('role', 'admin')->count();


            // Pass stats to the admin dashboard view
            return view('livewire.dashboard-admin', $viewData); // Use a dedicated admin view
        }
        else {
             // Fallback for unexpected roles? Or redirect?
             // For now, maybe show student view
             return view('livewire.dashboard-student');
        }
    }
}