<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Course;
use App\Models\Major;
use App\Models\YearbookProfile;
use App\Models\YearbookPlatform; // Import YearbookPlatform
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection; // Use Eloquent Collection
use Illuminate\Support\Collection as BaseCollection; // Use Base Collection
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YearbookProfilesExport;
use Illuminate\Support\Facades\Log;


#[Layout('components.layouts.app')]
#[Title('Yearbook Repository')]
class YearbookRepository extends Component
{
    use WithPagination;

    // Filters
    public $filterCollegeId = '';
    public $filterCourseId = '';
    public $filterMajorId = '';
    public $filterPaymentStatus = '';
    public $filterPlatformId = ''; // Filter by platform ID
    public string $search = '';
    public int $perPage = 12;

    // Export Option
    #[Rule('required|in:xlsx,csv,json')]
    public string $exportFormat = 'xlsx';

    // Filter Options (populated in mount/render)
    public EloquentCollection $colleges; // Use EloquentCollection for Models
    public EloquentCollection $platforms; // Use EloquentCollection for Models
    public EloquentCollection $courses;
    public EloquentCollection $majors;


    protected $queryString = [
        'search' => ['except' => ''],
        'filterCollegeId' => ['except' => '', 'as' => 'college'],
        'filterCourseId' => ['except' => '', 'as' => 'course'],
        'filterMajorId' => ['except' => '', 'as' => 'major'],
        'filterPaymentStatus' => ['except' => '', 'as' => 'payment'],
        'filterPlatformId' => ['except' => '', 'as' => 'platform'], // Use platform query string
    ];

    // Initialize collections in mount
    public function mount() {
        $this->colleges = College::orderBy('name')->get();
        $this->platforms = YearbookPlatform::orderBy('year', 'desc')->get(); // Load platforms
        $this->courses = new EloquentCollection(); // Initialize empty
        $this->majors = new EloquentCollection(); // Initialize empty

        // Optionally default filter to the latest platform?
        // Consider if 'active' is more appropriate default
        $activePlatform = YearbookPlatform::active(); // Find the active one
        if ($activePlatform && empty(request()->query('platform'))) {
            $this->filterPlatformId = $activePlatform->id;
        }
    }

    // Reset page and dependent filters when filters change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterPlatformId() { $this->resetPage(); } // Reset page when platform changes
    public function updatingFilterCollegeId() {
        $this->filterCourseId = '';
        $this->filterMajorId = '';
        $this->resetPage();
    }
    public function updatingFilterCourseId() {
        $this->filterMajorId = '';
        $this->resetPage();
    }
    public function updatingFilterMajorId() { $this->resetPage(); }
    public function updatingFilterPaymentStatus() { $this->resetPage(); }

    /**
     * Reset all filters to their default state.
     */
    public function resetFilters()
    {
        $this->reset(
            'search',
            'filterCollegeId',
            'filterCourseId',
            'filterMajorId',
            'filterPaymentStatus',
            'filterPlatformId' // Reset platform filter
        );
        $this->courses = collect(); // Reset course options
        $this->majors = collect();  // Reset major options
        $this->resetPage(); // Ensure pagination resets
    }

    /**
     * Build the base query for fetching profiles based on current filters.
     */
    private function buildFilteredQuery(): Builder
    {
        $query = YearbookProfile::query()
            ->with([
                'user', // Eager load user
                'college', // Eager load college
                'course', // Eager load course
                'major', // Eager load major
                'yearbookPlatform', // Eager load platform
                'user.yearbookPhotos' => function ($query) { // Load only the first photo for efficiency
                    $query->orderBy('order')->limit(1);
                }
            ])
            ->where('profile_submitted', true) // Usually only show submitted profiles
            ->join('users', 'yearbook_profiles.user_id', '=', 'users.id') // Join users for sorting/searching
            ->select('yearbook_profiles.*'); // Select only profile columns initially

        // Apply Search Filter
        if (!empty($this->search)) {
             $query->where(function ($q) {
                 $q->where('users.first_name', 'like', '%' . $this->search . '%')
                   ->orWhere('users.last_name', 'like', '%' . $this->search . '%')
                   ->orWhere('users.username', 'like', '%' . $this->search . '%')
                   ->orWhere('users.email', 'like', '%' . $this->search . '%')
                   ->orWhere('yearbook_profiles.nickname', 'like', '%' . $this->search . '%');
                 // Add more searchable fields if needed
             });
        }

        // Apply Academic Filters
        if (!empty($this->filterCollegeId)) { $query->where('yearbook_profiles.college_id', $this->filterCollegeId); }
        if (!empty($this->filterCourseId)) { $query->where('yearbook_profiles.course_id', $this->filterCourseId); }
        if (!empty($this->filterMajorId)) { $query->where('yearbook_profiles.major_id', $this->filterMajorId); }

        // Apply Payment Status Filter
        if (!empty($this->filterPaymentStatus)) { $query->where('yearbook_profiles.payment_status', $this->filterPaymentStatus); }

        // Apply Platform Filter (NEW)
        if (!empty($this->filterPlatformId)) {
             $query->where('yearbook_profiles.yearbook_platform_id', $this->filterPlatformId);
        }

        // Add Sorting
        $query->orderBy('users.last_name')->orderBy('users.first_name');

        return $query;
    }


    /**
     * Export the filtered data.
     */
    public function exportData()
    {
        $this->validateOnly('exportFormat');
        $query = $this->buildFilteredQuery();
        $filename = 'yearbook_data_' . now()->format('Ymd_His');
        $export = new YearbookProfilesExport($query); // Pass the filtered query

        try {
            if ($this->exportFormat === 'xlsx') {
                return Excel::download($export, $filename . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($this->exportFormat === 'csv') {
                return Excel::download($export, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
            } elseif ($this->exportFormat === 'json') {
                // Fetch all data matching query for JSON export
                $data = $query->get()->toArray();
                return response()->streamDownload(function () use ($data) {
                    echo json_encode($data, JSON_PRETTY_PRINT);
                }, $filename . '.json');
            }
        } catch (\Exception $e) {
            Log::error("Export Error: " . $e->getMessage());
            session()->flash('error', 'An error occurred during export. Please try again.');
            return null;
        }
         session()->flash('error', 'Invalid export format selected.');
         return null;
    }


    public function render()
    {
        // --- Populate Filter Dropdowns ---
        // Colleges & Platforms are loaded once in mount

        // Load courses dynamically based on selected college filter
        $this->courses = !empty($this->filterCollegeId)
            ? Course::where('college_id', $this->filterCollegeId)->orderBy('name')->get()
            : new EloquentCollection(); // Use EloquentCollection

        // Load majors dynamically based on selected course filter
        $this->majors = !empty($this->filterCourseId)
            ? Major::where('course_id', $this->filterCourseId)->orderBy('name')->get()
            : new EloquentCollection(); // Use EloquentCollection


        // --- Build Query using reusable method ---
        $query = $this->buildFilteredQuery();

        // Paginate for display
        $profiles = $query->paginate($this->perPage);

        return view('livewire.admin.yearbook-repository', [
            'profiles' => $profiles,
            // Pass collections needed for filters
            'colleges' => $this->colleges,
            'courses' => $this->courses,
            'majors' => $this->majors,
            'platforms' => $this->platforms,
        ]);
    }
}