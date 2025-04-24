<?php

namespace App\Livewire\Student;

use App\Models\YearbookProfile;
use App\Models\College;
use App\Models\Course;
use App\Models\Major;
use App\Models\YearbookPlatform; // Import Platform
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

#[Layout('components.layouts.app')]
#[Title('Edit Yearbook Profile')]
class YearbookProfileForm extends Component
{
    // --- Other Profile Fields ---
    #[Rule('nullable|string|max:100')] public $nickname = '';
    #[Rule('nullable|integer|min:0')] public $age = null;
    #[Rule('nullable|date')] public $birth_date = '';
    #[Rule('nullable|string')] public $address = '';
    #[Rule('nullable|string|max:50')] public $contact_number = '';
    #[Rule('nullable|string|max:255')] public $mother_name = '';
    #[Rule('nullable|string|max:255')] public $father_name = '';
    #[Rule('nullable|string|max:255')] public $affiliation_1 = '';
    #[Rule('nullable|string|max:255')] public $affiliation_2 = '';
    #[Rule('nullable|string|max:255')] public $affiliation_3 = '';
    #[Rule('nullable|string')] public $awards = '';
    #[Rule('nullable|string')] public $mantra = '';
    #[Rule('required|string|in:Full,Partial')] public $subscription_type = 'Full';
    #[Rule('required|string|in:XS,S,M,L,XL,2XL,3XL')] public $jacket_size = 'M';
    #[Rule('required|string|max:100')] public $year_and_section = '';

    // --- Academic Structure IDs ---
    #[Rule('required|integer|exists:colleges,id')] // Required on this main form
    public $selectedCollegeId = null;
    #[Rule('required|integer|exists:courses,id')]
    public $selectedCourseId = null;
    #[Rule('nullable|integer|exists:majors,id')]
    public $selectedMajorId = null;

    // Read-only user info
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';

    public function mount()
    {
        $user = Auth::user();
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;

        $profile = $user->yearbookProfile;
        if ($profile) {
            // Load existing profile data
            $this->nickname = $profile->nickname;
            $this->age = $profile->age;
            $this->birth_date = $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '';
            $this->address = $profile->address;
            $this->contact_number = $profile->contact_number;
            $this->mother_name = $profile->mother_name;
            $this->father_name = $profile->father_name;
            $this->affiliation_1 = $profile->affiliation_1;
            $this->affiliation_2 = $profile->affiliation_2;
            $this->affiliation_3 = $profile->affiliation_3;
            $this->awards = $profile->awards;
            $this->mantra = $profile->mantra;
            $this->subscription_type = $profile->subscription_type ?? 'Full';
            $this->jacket_size = $profile->jacket_size ?? 'M';
            $this->year_and_section = $profile->year_and_section;

            // Load academic IDs
            $this->selectedCollegeId = $profile->college_id;
            $this->selectedCourseId = $profile->course_id;
            $this->selectedMajorId = $profile->major_id ?? null;
        }
    }

    // ACTION METHOD for College change
    public function handleCollegeChange()
    {
        $this->selectedCourseId = null;
        $this->selectedMajorId = null;
        $this->resetValidation(['selectedCourseId', 'selectedMajorId']);
        Log::debug("[ProfileForm] College Selection Changed via Action. New College ID: " . $this->selectedCollegeId);
    }

    // ACTION METHOD for Course change
    public function handleCourseChange()
    {
        $this->selectedMajorId = null;
        $this->resetValidation('selectedMajorId');
        Log::debug("[ProfileForm] Course Selection Changed via Action. New Course ID: " . $this->selectedCourseId);
    }

    public function save()
    {
        // Define all validation rules for the entire form
        $validatedData = $this->validate([
            'nickname' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:0',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:50',
            'mother_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'affiliation_1' => 'nullable|string|max:255',
            'affiliation_2' => 'nullable|string|max:255',
            'affiliation_3' => 'nullable|string|max:255',
            'awards' => 'nullable|string',
            'mantra' => 'nullable|string',
            'subscription_type' => 'required|string|in:Full,Partial',
            'jacket_size' => 'required|string|in:XS,S,M,L,XL,2XL,3XL',
            'selectedCollegeId' => 'required|integer|exists:colleges,id', // Academic fields
            'selectedCourseId' => 'required|integer|exists:courses,id',
            'selectedMajorId' => 'nullable|integer|exists:majors,id',
            'year_and_section' => 'required|string|max:100',
        ]);

         // Get active platform
        $activePlatform = YearbookPlatform::active();
        if (!$activePlatform) {
             session()->flash('error', 'The yearbook platform is not currently active. Cannot save profile.');
             Log::error('Attempted to save profile with no active yearbook platform for user: ' . Auth::id());
             return;
         }

        // Prepare data, map selected IDs correctly
        $profileData = $validatedData; // Start with validated data
        $profileData['college_id'] = $validatedData['selectedCollegeId'];
        $profileData['course_id'] = $validatedData['selectedCourseId'];
        $profileData['major_id'] = $validatedData['selectedMajorId'];
        $profileData['yearbook_platform_id'] = $activePlatform->id;
        $profileData['profile_submitted'] = true; // Mark as submitted on main form save
        $profileData['submitted_at'] = now();
         // Keep existing payment status unless explicitly changed elsewhere
        $profileData['payment_status'] = Auth::user()->yearbookProfile?->payment_status ?? 'pending';

        // Remove the temporary selectedId keys as they don't exist in the DB table
        unset($profileData['selectedCollegeId'], $profileData['selectedCourseId'], $profileData['selectedMajorId']);

        try {
             YearbookProfile::updateOrCreate(
                 ['user_id' => Auth::id()],
                 $profileData
             );
             session()->flash('message', 'Yearbook profile saved successfully!');
        } catch (\Exception $e) {
             Log::error("Error saving profile for user " . Auth::id() . ": " . $e->getMessage());
             session()->flash('error', 'An error occurred while saving your profile.');
        }
    }

    public function render()
    {
        // Fetch options for dropdowns
        $colleges = College::orderBy('name')->get();
        $courses = $this->selectedCollegeId
            ? Course::where('college_id', $this->selectedCollegeId)->orderBy('name')->get()
            : new EloquentCollection();
        $majors = $this->selectedCourseId
            ? Major::where('course_id', $this->selectedCourseId)->orderBy('name')->get()
            : new EloquentCollection();

        return view('livewire.student.yearbook-profile-form', [
            'colleges' => $colleges,
            'courses' => $courses,
            'majors' => $majors,
        ]);
    }
}