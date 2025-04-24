<?php

namespace App\Livewire\Student;

use App\Models\College;
use App\Models\Course;
use App\Models\Major;
use App\Models\YearbookProfile;
use App\Models\YearbookPlatform; // Import YearbookPlatform
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection; // Use Eloquent Collection

#[Layout('components.layouts.app')]
#[Title('Academic Area')]
class AcademicArea extends Component
{
    // Selected IDs for dropdowns
    #[Rule('required|integer|exists:colleges,id')] // College is required on this page
    public $selectedCollegeId = null;

    #[Rule('required|integer|exists:courses,id')] // Course is required on this page
    public $selectedCourseId = null;

    // Ensure major_id exists in yearbook_profiles migration and YearbookProfile model $fillable
    #[Rule('nullable|integer|exists:majors,id')] // Major is optional
    public $selectedMajorId = null;

    // Other form fields
    #[Rule('required|string|max:100')]
    public $year_and_section = '';

    // Control editability
    public bool $canEdit = true;

    /**
     * Initialize dropdowns and load existing data.
     */
    public function mount()
    {
        // Load existing profile data if available
        $profile = Auth::user()->yearbookProfile;
        if ($profile) {
            $this->selectedCollegeId = $profile->college_id;
            $this->selectedCourseId = $profile->course_id;
            $this->selectedMajorId = $profile->major_id ?? null; // Handle potential null major_id
            $this->year_and_section = $profile->year_and_section;

            // Initial data for dropdown options will be loaded in the first render() call based on these IDs
        }

         // Add edit restriction logic here if needed based on profile status
         // Example:
         // if ($profile && $profile->profile_submitted && $profile->payment_status === 'paid') {
         //     $this->canEdit = false;
         // }
    }

    /**
     * ACTION METHOD: Called explicitly via wire:change when the College dropdown changes.
     */
    public function collegeSelected()
    {
        // Reset course and major selections
        $this->selectedCourseId = null;
        $this->selectedMajorId = null;
        $this->resetValidation(['selectedCourseId', 'selectedMajorId']); // Reset validation state for dependent fields
        Log::debug("[AcademicArea] College Selection Changed via Action. New College ID: " . $this->selectedCollegeId);
        // The component will re-render automatically, and render() will fetch courses/majors.
    }

     /**
      * ACTION METHOD: Called explicitly via wire:change when the Course dropdown changes.
      */
      public function courseSelected()
      {
         // Reset major selection
         $this->selectedMajorId = null;
         $this->resetValidation('selectedMajorId'); // Reset validation state for major
         Log::debug("[AcademicArea] Course Selection Changed via Action. New Course ID: " . $this->selectedCourseId);
         // The render() method will fetch the appropriate majors.
      }

    /**
     * Save selected academic info.
     */
    public function saveAcademicInfo()
    {
        if (!$this->canEdit) {
            session()->flash('error', 'Academic information cannot be edited at this time.');
            return;
        }

        // Validate the currently selected IDs and year/section
        $validated = $this->validate([
            'selectedCollegeId' => 'required|integer|exists:colleges,id',
            'selectedCourseId' => 'required|integer|exists:courses,id',
            'selectedMajorId' => 'nullable|integer|exists:majors,id',
            'year_and_section' => 'required|string|max:100',
        ]);

        // Get the currently active platform ID
        $activePlatform = YearbookPlatform::active(); // Use the scope
        if (!$activePlatform) {
            session()->flash('error', 'No active yearbook platform found. Cannot save profile.');
            Log::warning('Attempted to save academic info with no active yearbook platform for user: ' . Auth::id());
            return;
        }

        // Prepare data array
        $profileData = [
            'college_id' => $validated['selectedCollegeId'],
            'course_id' => $validated['selectedCourseId'],
            'major_id' => $validated['selectedMajorId'], // Will be null if not selected
            'year_and_section' => $validated['year_and_section'],
            'yearbook_platform_id' => $activePlatform->id, // Assign active platform ID
        ];

        try {
            // Use standard updateOrCreate on the Model directly
            $profile = YearbookProfile::updateOrCreate(
                ['user_id' => Auth::id()], // Attributes to find by
                $profileData              // Attributes to update or create with
            );

            // If the main profile form handles submission status, don't update it here.
            // If this page *can* submit the profile, uncomment below:
            // if (!$profile->profile_submitted) {
            //     $profile->update(['profile_submitted' => true, 'submitted_at' => now()]);
            // }

            session()->flash('message', 'Academic information updated successfully.');

        } catch (\Exception $e) {
            Log::error("Error saving academic info for user " . Auth::id() . ": " . $e->getMessage());
            session()->flash('error', 'An error occurred while saving academic information.');
        }
    }

    /**
     * Render the view and pass the necessary dropdown options based on current state.
     */
    public function render()
    {
        // Always fetch all colleges for the first dropdown
        $colleges = College::orderBy('name')->get();

        // Fetch courses ONLY if a college ID is currently selected
        $courses = $this->selectedCollegeId
            ? Course::where('college_id', $this->selectedCollegeId)->orderBy('name')->get()
            : new EloquentCollection(); // Return empty Eloquent collection

        // Fetch majors ONLY if a course ID is currently selected
        $majors = $this->selectedCourseId
            ? Major::where('course_id', $this->selectedCourseId)->orderBy('name')->get()
            : new EloquentCollection(); // Return empty Eloquent collection

        Log::debug("[AcademicArea] Rendering - Selected College: {$this->selectedCollegeId}, Courses Count: " . $courses->count() . ", Selected Course: {$this->selectedCourseId}, Majors Count: " . $majors->count());

        // Pass the fetched collections to the view
        return view('livewire.student.academic-area', [
            'colleges' => $colleges,
            'courses' => $courses,
            'majors' => $majors,
        ]);
    }
}