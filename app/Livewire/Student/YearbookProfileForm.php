<?php

namespace App\Livewire\Student;

use App\Models\YearbookProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\YearbookPlatform; // Import Platform model


#[Layout('components.layouts.app')]
#[Title('Edit Yearbook Profile')]
class YearbookProfileForm extends Component
{
    public ?YearbookProfile $profile = null;

    // --- Profile Form Fields (Excluding Academic Selection) ---
    #[Rule('nullable|string|max:100')]
    public $nickname = '';

    // Year & Section remains here as it's often separate text input
    #[Rule('required|string|max:100')]
    public $year_and_section = ''; // Keep this one

    #[Rule('nullable|integer|min:0')]
    public $age = null;
    #[Rule('nullable|date')]
    public $birth_date = '';
    #[Rule('nullable|string')]
    public $address = '';
    #[Rule('nullable|string|max:50')]
    public $contact_number = '';
    #[Rule('nullable|string|max:255')]
    public $mother_name = '';
    #[Rule('nullable|string|max:255')]
    public $father_name = '';
    #[Rule('nullable|string|max:255')]
    public $affiliation_1 = '';
    #[Rule('nullable|string|max:255')]
    public $affiliation_2 = '';
    #[Rule('nullable|string|max:255')]
    public $affiliation_3 = '';
    #[Rule('nullable|string')]
    public $awards = '';
    #[Rule('nullable|string')]
    public $mantra = '';

    // --- Subscription Type / Package ---
    #[Rule('required|string|in:full_package,inclusions_only')]
    public $subscription_type = 'full_package';

    // --- Jacket Size ---
    #[Rule('required|string|in:XS,S,M,L,XL,2XL,3XL')]
    public $jacket_size = 'M';

    // Display fields (read-only)
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';

    public function mount()
    {
        $user = Auth::user();
        // Eager load profile - no need to eager load college/course/major here
        $user->load('yearbookProfile');
        $this->profile = $user->yearbookProfile ?? null;

        // Pre-fill form fields if profile exists
        if ($this->profile) {
            $this->nickname = $this->profile->nickname;
            $this->year_and_section = $this->profile->year_and_section; // Keep this
            $this->age = $this->profile->age;
            $this->birth_date = $this->profile->birth_date ? $this->profile->birth_date->format('Y-m-d') : '';
            $this->address = $this->profile->address;
            $this->contact_number = $this->profile->contact_number;
            $this->mother_name = $this->profile->mother_name;
            $this->father_name = $this->profile->father_name;
            $this->affiliation_1 = $this->profile->affiliation_1;
            $this->affiliation_2 = $this->profile->affiliation_2;
            $this->affiliation_3 = $this->profile->affiliation_3;
            $this->awards = $this->profile->awards;
            $this->mantra = $this->profile->mantra;
            $this->subscription_type = $this->profile->subscription_type ?? 'full_package';
            $this->jacket_size = $this->profile->jacket_size ?? 'M';
        }

        // Load user's base info
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;
    }

    public function save()
    {
        // Validate only the fields present in this component
        $validatedData = $this->validate(); // This will now only validate properties with #[Rule] defined above

        // Find active platform
        $activePlatform = YearbookPlatform::active();
         if (!$activePlatform) {
             session()->flash('error', 'No active yearbook platform found. Cannot save profile.');
             return;
         }

        // Prepare data - EXCLUDE academic IDs (college_id, course_id, major_id)
        $profileData = [
            'yearbook_platform_id' => $activePlatform->id,
            'nickname' => $validatedData['nickname'],
            'year_and_section' => $validatedData['year_and_section'], // Keep this one
            'age' => $validatedData['age'],
            'birth_date' => $validatedData['birth_date'] ?: null,
            'address' => $validatedData['address'],
            'contact_number' => $validatedData['contact_number'],
            'mother_name' => $validatedData['mother_name'],
            'father_name' => $validatedData['father_name'],
            'affiliation_1' => $validatedData['affiliation_1'],
            'affiliation_2' => $validatedData['affiliation_2'],
            'affiliation_3' => $validatedData['affiliation_3'],
            'awards' => $validatedData['awards'],
            'mantra' => $validatedData['mantra'],
            'subscription_type' => $validatedData['subscription_type'],
            'jacket_size' => $validatedData['jacket_size'],
            // Mark profile as submitted - important step!
            'profile_submitted' => true,
             // Set submitted_at only if it's not already set (i.e., first submission)
            'submitted_at' => $this->profile?->submitted_at ?? now(),
             // Payment status defaults to 'pending' or remains unchanged if editing
             // It should NOT be set here, but rather handled by Admin confirmation.
             // 'payment_status' => $this->profile?->payment_status ?? 'pending',
        ];

        // Use updateOrCreate to handle both creating & updating
        Auth::user()->yearbookProfile()->updateOrCreate(
            ['user_id' => Auth::id()], // Condition to find existing profile
            $profileData               // Data to update or create with
        );

        session()->flash('message', 'Yearbook profile information saved successfully!');
        // Optional redirect
        // $this->redirect(route('app.dashboard'), navigate: true);
    }

    public function render()
    {
        // No need to pass academic dropdown options here
        return view('livewire.student.yearbook-profile-form');
    }
}