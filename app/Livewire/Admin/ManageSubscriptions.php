<?php

namespace App\Livewire\Admin;

use App\Models\YearbookProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination; // Use pagination trait

#[Layout('components.layouts.app')]
#[Title('Manage Subscriptions')]
class ManageSubscriptions extends Component
{
    use WithPagination; // Enable pagination
    
    protected $paginationTheme = 'tailwind';

    public string $activeTab = 'pending'; // Default tab ('pending', 'registered', 'no_writeup', 'deleted')
    public int $perPage = 10; // Results per page
    public string $search = ''; // Search term

    protected $queryString = [
        'activeTab' => ['except' => 'pending', 'as' => 'tab'], // Allow tab switching via URL query string
        'search' => ['except' => '','as' => 's'], // Allow searching via URL query string
    ];

    /**
     * Switch the active tab.
     */
    public function setTab(string $tabName)
    {
        $this->activeTab = $tabName;
        $this->resetPage(); // Reset pagination when changing tabs or searching
    }

    /**
     * Reset pagination when search term is updated.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Mark a student's profile as paid and record the admin.
     */
    public function confirmPayment(int $profileId)
    {
        $profile = YearbookProfile::find($profileId);
        if ($profile && $profile->payment_status === 'pending') {
            $profile->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'payment_confirmed_by' => Auth::id(), // <-- STORE CURRENT ADMIN ID
            ]);
            session()->flash('message', 'Payment confirmed successfully for ' . $profile->user?->username ?? 'user');
        } else {
            session()->flash('error', 'Could not confirm payment. Profile not found or already paid.');
        }
    }

     /**
      * Soft delete a student's yearbook profile.
      */
    public function deleteProfile(int $profileId)
    {
        $profile = YearbookProfile::find($profileId);
        if ($profile) {
            $profile->delete(); // Soft delete
            session()->flash('message', 'Profile deleted successfully for ' . $profile->user->username);
        } else {
             session()->flash('error', 'Could not delete profile. Profile not found.');
        }
    }

    /**
     * Restore a soft-deleted profile.
     */
    public function restoreProfile(int $profileId)
    {
        $profile = YearbookProfile::onlyTrashed()->find($profileId);
        if ($profile) {
            $profile->restore();
            session()->flash('message', 'Profile restored successfully for ' . $profile->user->username);
        } else {
            session()->flash('error', 'Could not restore profile. Profile not found in trash.');
        }
    }

    public function render()
    {
        $query = YearbookProfile::query()
            // *** EAGER LOAD RELATIONSHIPS HERE ***
            ->with(['user', 'college', 'course', 'yearbookPlatform', 'paymentConfirmer']) // Load user, college, and course data efficiently
            ->orderBy('created_at', 'desc');

        // Apply filtering based on the active tab
        switch ($this->activeTab) {
            case 'pending':
                $query->where('profile_submitted', true) // Assuming profile must be submitted first
                      ->where('payment_status', 'pending')
                      ->whereNotNull('college_id') // Added: Ensure academic info IS present for pending payment
                      ->whereNotNull('course_id')
                      ->whereNull('deleted_at');
                break;
            case 'registered':
                 $query->where('profile_submitted', true)
                       ->where('payment_status', 'paid') // Or other paid statuses if applicable
                       ->whereNull('deleted_at');
                 break;
            case 'missing_academic': // <-- New Tab Logic
                  $query->where('profile_submitted', true) // Profile details submitted
                        ->where(function ($q) {
                            $q->whereNull('college_id') // But College OR Course is missing
                              ->orWhereNull('course_id');
                        })
                        ->whereNull('deleted_at'); // Not deleted
                  break;
            case 'deleted':
                    $query->onlyTrashed();
                    break;
                default: // Default case if tab name is invalid
                    $query->whereRaw('1 = 0'); // Return no results
           }

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                // Search User fields via relationship
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('first_name', 'like', '%' . $this->search . '%')
                              ->orWhere('last_name', 'like', '%' . $this->search . '%')
                              ->orWhere('username', 'like', '%' . $this->search . '%')
                              ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                // Search College name via relationship
                ->orWhereHas('college', function($collegeQuery) {
                   $collegeQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('abbreviation', 'like', '%' . $this->search . '%');
                })
                // Search Course name via relationship
                ->orWhereHas('course', function($courseQuery) {
                   $courseQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('abbreviation', 'like', '%' . $this->search . '%');
                });
                // Add orWhereHas for 'major' if needed
            });
       }

       $profiles = $query->paginate($this->perPage);

       return view('livewire.admin.manage-subscriptions', [
           'profiles' => $profiles,
       ]);
   }
}