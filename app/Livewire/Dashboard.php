<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Models\YearbookProfile;
use App\Models\User;
use App\Models\YearbookPlatform;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Make sure Log is imported
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\On;


#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{

    #[On('platform-image-updated')]
    public function refreshDashboardData()
    {
        Log::debug("Dashboard received platform-image-updated event.");
        // Force re-render might be needed if data depends on complex state
        // $this->render(); // Re-calling render directly isn't standard
    }

    public function render()
    {
        Log::debug("Dashboard render starting..."); // Log start

        $user = Auth::user();
        $viewData = [];

        // --- Fetch Active Platform ---
        $activePlatform = null; // Default to null
        try {
            // Force execution using get()->first() just to be overly explicit
            // Although ::active() which calls first() should be enough.
            $activePlatformQuery = YearbookPlatform::query()->where('is_active', true); // Get builder first
            $activePlatform = $activePlatformQuery->first(); // THEN execute with first()

            // Or even more explicit, though redundant:
            // $activePlatform = YearbookPlatform::where('is_active', true)->limit(1)->get()->first();


            Log::debug("Active Platform Fetched: " . ($activePlatform ? "ID " . $activePlatform->id : "None"));
        } catch (\Exception $e) {
            Log::error("Error fetching active platform: " . $e->getMessage());
        }
        $viewData['activePlatform'] = $activePlatform;
        // --- End Fetch ---


        if ($user->role === 'student') {
            $user->load('yearbookProfile');
            $viewData['profile'] = $user->yearbookProfile;
            Log::debug("Rendering student dashboard view.");
            return view('livewire.dashboard-student', $viewData);

        } else if (in_array($user->role, ['admin', 'superadmin'])) {

            // --- Calculate Admin Stats ---
            // Explicitly check if platform object exists before accessing status
            $currentPlatformStatus = 'N/A'; // Default status
            if ($activePlatform instanceof YearbookPlatform) { // Check if it's the correct object type
                $currentPlatformStatus = $activePlatform->status;
                Log::debug("Admin Dashboard: Active platform status is " . $currentPlatformStatus);
            } else {
                Log::debug("Admin Dashboard: No active platform found, setting status to N/A.");
            }
            $viewData['platformStatus'] = $currentPlatformStatus; // Pass the calculated status string

            // ... rest of stats calculations (these look safe) ...
            $platformId = $activePlatform?->id;
            $viewData['pendingPaymentsCount'] = YearbookProfile::when($platformId, fn($q) => $q->where('yearbook_platform_id', $platformId))->where('payment_status', 'pending')->where('profile_submitted', true)->count();
            $viewData['registeredPaidCount'] = YearbookProfile::when($platformId, fn($q) => $q->where('yearbook_platform_id', $platformId))->where('payment_status', 'paid')->count();
            $viewData['totalProfilesSubmitted'] = YearbookProfile::when($platformId, fn($q) => $q->where('yearbook_platform_id', $platformId))->where('profile_submitted', true)->count();
            $viewData['studentCount'] = User::where('role', 'student')->count();
            $viewData['adminCount'] = User::where('role', 'admin')->count();
            // --- End Admin Stats ---

            Log::debug("Rendering admin dashboard view.");
            return view('livewire.dashboard-admin', $viewData);

        } else {
             // Fallback
             Log::debug("Rendering fallback (student) dashboard view for unknown role.");
             return view('livewire.dashboard-student', ['profile' => null, 'activePlatform' => $activePlatform]);
        }
    }
}