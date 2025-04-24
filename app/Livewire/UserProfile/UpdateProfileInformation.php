<?php

namespace App\Livewire\UserProfile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // For checking enum-like values if needed
use Livewire\Attributes\Layout;
use Carbon\Carbon; // For date handling
use Livewire\Attributes\Rule as LivewireRule; // Use alias to avoid conflict with Validation Rule
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Validation\ValidationException;


#[Layout('components.layouts.app')]
#[Title('Update Profile')]
class UpdateProfileInformation extends Component
{
    // Form Fields bound to user model properties
    #[LivewireRule('required|string|max:255')]
    public string $first_name = '';

    #[LivewireRule('required|string|max:255')]
    public string $last_name = '';

    #[LivewireRule('nullable|string|max:100')]
    public string $middle_name = '';

    #[LivewireRule('nullable|string|max:50')]
    public string $suffix = '';

    #[LivewireRule('nullable|string|in:Male,Female,Other,Prefer not to say')] // Example validation
    public string $gender = '';

    #[LivewireRule('nullable|date')]
    public $birthdate = ''; // String for date input binding

    #[LivewireRule('nullable|string|max:50')]
    public string $contact_number = '';

    #[LivewireRule('nullable|string')]
    public string $address_line = '';

    #[LivewireRule('nullable|string|max:255')]
    public string $city_province = '';

    // Current Password for verification
    #[LivewireRule('required|string')]
    public string $current_password = '';

    /**
     * Load user data, potentially pre-filling from YearbookProfile if needed.
     */
    public function mount()
    {
        $user = Auth::user();
        $profile = $user->yearbookProfile; // Load yearbook profile if exists

        $this->first_name = $user->first_name ?? '';
        $this->last_name = $user->last_name ?? '';
        $this->middle_name = $user->middle_name ?? '';
        $this->suffix = $user->suffix ?? '';
        $this->gender = $user->gender ?? '';
        $this->birthdate = $user->birthdate ? $user->birthdate->format('Y-m-d') : '';

        // --- Pre-fill Logic (Example: Prioritize User table, fallback to YearbookProfile) ---
        $this->contact_number = $user->contact_number ?: ($profile?->contact_number ?? '');
        $this->address_line = $user->address_line ?: ($profile?->address ?? ''); // Map yearbook 'address' to 'address_line'
        $this->city_province = $user->city_province ?? ''; // No direct equivalent in yearbook profile

        // If user is student and some User fields are empty but Yearbook fields exist, pre-fill them
        // This helps students migrating data, but admins won't have this fallback.
        if ($user->role === 'student' && $profile) {
            $this->birthdate = $this->birthdate ?: ($profile->birth_date ? $profile->birth_date->format('Y-m-d') : '');
            // Add other fields as needed
        }

    }

    /**
     * Define validation rules.
     */
    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', Rule::in(['Male', 'Female', 'Other', 'Prefer not to say'])],
            'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            'contact_number' => ['nullable', 'string', 'max:50'], // Add regex later if needed
            'address_line' => ['nullable', 'string', 'max:1000'], // Max length for text
            'city_province' => ['nullable', 'string', 'max:255'],
            'current_password' => ['required', 'string'], // Always required to save profile changes
        ];
    }

     /**
     * Define custom attribute names for validation messages.
     */
    protected function validationAttributes(): array
    {
         return [
             'first_name' => 'first name',
             'last_name' => 'last name',
             'middle_name' => 'middle name',
             'birthdate' => 'birthdate',
             'contact_number' => 'contact number',
             'address_line' => 'address',
             'city_province' => 'city/province',
             'current_password' => 'current password',
         ];
    }


    /**
     * Update the user's profile information.
     */
    public function updateProfile()
    {
        $user = Auth::user();

        // Validate all profile fields first
        $validatedData = $this->validate(); // This uses rules() method

        // Check current password
        if (!Hash::check($this->current_password, $user->password)) {
             throw ValidationException::withMessages([
                 'current_password' => __('auth.password'), // Incorrect password message
             ]);
        }

        // Prepare data for update (exclude current_password)
        $updateData = collect($validatedData)->except('current_password')->all();

         // Ensure null is saved if date is empty, handle Carbon parsing if needed
        $updateData['birthdate'] = !empty($validatedData['birthdate']) ? Carbon::parse($validatedData['birthdate'])->toDateString() : null;


        // Update the user model
        $user->forceFill($updateData)->save();

        // Reset the current password field after successful save
        $this->reset('current_password');

        session()->flash('message', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.user-profile.update-profile-information');
    }
}