<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Course;
use App\Models\Major; // Assuming majors are used
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection as BaseCollection; // For type hinting empty collections

#[Layout('components.layouts.app')]
#[Title('Manage Academic Structure')]
class ManageAcademicStructure extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';

    // --- State for Display ---
    public ?int $selectedCollegeId = null; // STORE THE ID HERE
    public ?College $selectedCollege = null; // Property to hold the loaded College object

    public ?int $selectedCourseId = null; // STORE THE ID HERE
    public ?Course $selectedCourse = null; // Property to hold the loaded Course object

    // --- State for Modals/Forms ---
    public bool $showCollegeModal = false;
    public bool $showCourseModal = false;
    public bool $showMajorModal = false;

    public ?int $editingCollegeId = null;
    public ?int $editingCourseId = null;
    public ?int $editingMajorId = null;

    // --- College Form Fields ---
    #[Rule('required|string|max:255')]
    public string $collegeName = '';
    #[Rule('required|string|max:50')]
    public string $collegeAbbreviation = '';

    // --- Course Form Fields ---
    #[Rule('required|string|max:255')]
    public string $courseName = '';
    #[Rule('required|string|max:50')]
    public string $courseAbbreviation = '';
    // college_id will be taken from $selectedCollegeId

    // --- Major Form Fields ---
    #[Rule('required|string|max:255')]
    public string $majorName = '';
    // course_id will be taken from $selectedCourseId

    // --- Computed Property / Loading Logic ---
    // Load the College object when the ID changes
    // Using Livewire's magic property hook for computed-like behavior
    public function updatedSelectedCollegeId($id)
    {
        $this->selectedCollege = $id ? College::find($id) : null;
        // Reset selected course/majors when college changes
        $this->selectedCourseId = null; // Reset ID
        $this->selectedCourse = null;   // Reset Object
        $this->resetPage('coursesPage');
        $this->resetPage('majorsPage');
    }

    // Load the Course object when the ID changes
     public function updatedSelectedCourseId($id)
     {
         $this->selectedCourse = $id ? Course::find($id) : null;
         // Reset selected majors when course changes
         $this->resetPage('majorsPage');
     }

    // --- Modal Control ---
    public function openCollegeModal(?College $college = null) {
        $this->resetErrorBag();
        $this->editingCollegeId = $college?->id;
        $this->collegeName = $college?->name ?? '';
        $this->collegeAbbreviation = $college?->abbreviation ?? '';
        $this->showCollegeModal = true;
    }
    public function closeCollegeModal() { $this->showCollegeModal = false; $this->resetCollegeFields(); }
    private function resetCollegeFields() { $this->reset('editingCollegeId', 'collegeName', 'collegeAbbreviation'); }

    public function openCourseModal(?Course $course = null) {
         if (!$this->selectedCollegeId) return; // Must have a college selected
        $this->resetErrorBag();
        $this->editingCourseId = $course?->id;
        $this->courseName = $course?->name ?? '';
        $this->courseAbbreviation = $course?->abbreviation ?? '';
        $this->showCourseModal = true;
    }
    public function closeCourseModal() { $this->showCourseModal = false; $this->resetCourseFields(); }
    private function resetCourseFields() { $this->reset('editingCourseId', 'courseName', 'courseAbbreviation'); }

    public function openMajorModal(?Major $major = null) {
        if (!$this->selectedCourseId) return; // Must have a course selected
        $this->resetErrorBag();
        $this->editingMajorId = $major?->id;
        $this->majorName = $major?->name ?? '';
        $this->showMajorModal = true;
    }
    public function closeMajorModal() { $this->showMajorModal = false; $this->resetMajorFields(); }
    private function resetMajorFields() { $this->reset('editingMajorId', 'majorName'); }


    // --- CRUD Operations ---

    // Colleges
    public function saveCollege() {
        $rules = [
            'collegeName' => ['required', 'string', 'max:255'],
            'collegeAbbreviation' => ['nullable', 'string', 'max:50'],
        ];
        $rules['collegeName'][] = $this->editingCollegeId
            ? 'unique:colleges,name,' . $this->editingCollegeId
            : 'unique:colleges,name';

        $validated = $this->validate($rules, [], [
             'collegeName' => 'college name',
             'collegeAbbreviation' => 'abbreviation',
        ]);

        $college = College::updateOrCreate(
            ['id' => $this->editingCollegeId],
            [
                'name' => $validated['collegeName'],
                'abbreviation' => $validated['collegeAbbreviation']
            ]
        );
        // Re-select the college if it was just edited/created to reload data
         $this->selectedCollegeId = $college->id;
         $this->updatedSelectedCollegeId($college->id); // Trigger reload

        session()->flash('message', 'College saved successfully.');
        $this->closeCollegeModal();
    }
    public function deleteCollege(College $college) {
        $collegeIdToDelete = $college->id;
        $college->delete(); // Assumes cascading deletes handle courses/majors or checks are added
        session()->flash('message', 'College deleted successfully.');
        if ($this->selectedCollegeId === $collegeIdToDelete) {
             $this->selectedCollegeId = null; // Reset selection ID
             $this->selectedCollege = null;   // Reset selected object
             $this->selectedCourseId = null;
             $this->selectedCourse = null;
        }
    }

    // Courses
    public function saveCourse() {
        if (!$this->selectedCollegeId) return;
        $rules = [
            'courseName' => ['required', 'string', 'max:255'],
            'courseAbbreviation' => ['required', 'string', 'max:50'],
        ];
        // Unique validation within the selected college
        $uniqueRule = $this->editingCourseId
            ? 'unique:courses,name,' . $this->editingCourseId . ',id,college_id,' . $this->selectedCollegeId
            : 'unique:courses,name,NULL,id,college_id,' . $this->selectedCollegeId;
        $rules['courseName'][] = $uniqueRule;

         $validated = $this->validate($rules, [], [
             'courseName' => 'course name',
             'courseAbbreviation' => 'abbreviation',
         ]);

        Course::updateOrCreate(
            ['id' => $this->editingCourseId],
            [
                'college_id' => $this->selectedCollegeId,
                'name' => $validated['courseName'],
                'abbreviation' => $validated['courseAbbreviation']
            ]
        );
        session()->flash('message', 'Course saved successfully.');
        $this->closeCourseModal();
        $this->resetPage('coursesPage'); // Reset pagination to see new/updated item
    }
     public function deleteCourse(Course $course) {
         $courseIdToDelete = $course->id;
         $course->delete(); // Assumes cascading deletes handle majors or checks are added
         session()->flash('message', 'Course deleted successfully.');
         if ($this->selectedCourseId === $courseIdToDelete) {
             $this->selectedCourseId = null; // Reset selection ID
             $this->selectedCourse = null;   // Reset selection object
         }
          $this->resetPage('coursesPage'); // Reset pagination
     }

    // Majors
    public function saveMajor() {
        if (!$this->selectedCourseId) return;
         $rules = [
             'majorName' => ['required', 'string', 'max:255'],
         ];
         // Unique validation within the selected course
         $uniqueRule = $this->editingMajorId
            ? 'unique:majors,name,' . $this->editingMajorId . ',id,course_id,' . $this->selectedCourseId
            : 'unique:majors,name,NULL,id,course_id,' . $this->selectedCourseId;
         $rules['majorName'][] = $uniqueRule;

         $validated = $this->validate($rules, [], ['majorName' => 'major name']);

        Major::updateOrCreate(
            ['id' => $this->editingMajorId],
            [
                'course_id' => $this->selectedCourseId,
                'name' => $validated['majorName']
            ]
        );
         session()->flash('message', 'Major saved successfully.');
         $this->closeMajorModal();
          $this->resetPage('majorsPage'); // Reset pagination
    }
    public function deleteMajor(Major $major) {
        $major->delete();
        session()->flash('message', 'Major deleted successfully.');
         $this->resetPage('majorsPage'); // Reset pagination
    }

    // --- Render Method ---
    public function render()
    {
        $colleges = College::withCount('courses')->orderBy('name')->get();

        // Fetch courses based on the ID
        $courses = $this->selectedCollegeId
            ? Course::where('college_id', $this->selectedCollegeId)
                      ->withCount('majors') // Count majors for display
                      ->orderBy('name')
                      ->paginate(5, ['*'], 'coursesPage') // Use named pagination
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5, 1, ['pageName' => 'coursesPage']); // Empty paginator if no college selected


         // Fetch majors based on the ID
         $majors = $this->selectedCourseId
             ? Major::where('course_id', $this->selectedCourseId)
                     ->orderBy('name')
                     ->paginate(5, ['*'], 'majorsPage') // Use named pagination
             : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5, 1, ['pageName' => 'majorsPage']); // Empty paginator if no course selected


        return view('livewire.admin.manage-academic-structure', [
            'colleges' => $colleges,
            'courses' => $courses,
            'majors' => $majors,
            // Pass selected objects to view for convenience (e.g., displaying headers)
            'selectedCollegeObject' => $this->selectedCollege,
            'selectedCourseObject' => $this->selectedCourse,
        ]);
    }
}