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
    public function render()
    {
        $user = Auth::user();
        $viewData = [];

        if ($user->role === 'student') {
            // Eager load the profile for the student view check
            $user->load('yearbookProfile'); // Load relationship
            $viewData['profile'] = $user->yearbookProfile; // Pass profile to the view
            return view('livewire.dashboard-student', $viewData); // Use dedicated view

        } else if (in_array($user->role, ['admin', 'superadmin'])) {
            // ... (existing admin stat fetching logic remains the same) ...
             $platformStatusSetting = Setting::where('key', 'yearbook_status')->first();
             $viewData['platformStatus'] = $platformStatusSetting?->value ?? 'closed';
             $viewData['pendingPaymentsCount'] = YearbookProfile::where('payment_status', 'pending')->where('profile_submitted', true)->count();
             $viewData['registeredPaidCount'] = YearbookProfile::where('payment_status', 'paid')->count();
             $viewData['totalProfilesSubmitted'] = YearbookProfile::where('profile_submitted', true)->count();
             $viewData['studentCount'] = User::where('role', 'student')->count();
             $viewData['adminCount'] = User::where('role', 'admin')->count();

            return view('livewire.dashboard-admin', $viewData); // Use dedicated view
        } else {
             // Fallback
             return view('livewire.dashboard-student', ['profile' => null]); // Pass null profile
        }
    }
}