<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Course;
use App\Models\Major;
use App\Models\YearbookProfile;
use App\Models\YearbookPlatform; // Import YearbookPlatform
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule; // Import Rule attribute
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection as BaseCollection; // Use Base Collection
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YearbookProfilesExport;
use Illuminate\Support\Facades\Log;


#[Layout('components.layouts.app')]
#[Title('Yearbook Repository')]
class YearbookRepository extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';

    // --- Filters - ADD RULES HERE ---
    #[Rule('nullable|integer|exists:colleges,id')] // Allow empty or valid college ID
    public $filterCollegeId = '';

    #[Rule('nullable|integer|exists:courses,id')] // Allow empty or valid course ID
    public $filterCourseId = '';

    #[Rule('nullable|integer|exists:majors,id')] // Allow empty or valid major ID
    public $filterMajorId = '';

    #[Rule('nullable|string|in:pending,paid')] // Allow empty or specific strings
    public $filterPaymentStatus = '';

    #[Rule('nullable|integer|exists:yearbook_platforms,id')] // Allow empty or valid platform ID
    public $filterPlatformId = '';

    #[Rule('nullable|string|max:255')] // Allow empty or string (limit length for safety)
    public string $search = '';

    // --- Pagination ---
    public int $perPage = 12;

    // --- Export Option ---
    #[Rule('required|in:xlsx,csv,json')]
    public string $exportFormat = 'xlsx';

    // --- Filter Options Properties ---
    // These hold data *for* the dropdowns, not user input requiring validation here.
    public BaseCollection $colleges;
    public BaseCollection $courses;
    public BaseCollection $majors;
    public BaseCollection $platforms;


    // --- Query String Mapping ---
    protected $queryString = [
        'search' => ['except' => ''],
        'filterCollegeId' => ['except' => '', 'as' => 'college'],
        'filterCourseId' => ['except' => '', 'as' => 'course'],
        'filterMajorId' => ['except' => '', 'as' => 'major'],
        'filterPaymentStatus' => ['except' => '', 'as' => 'payment'],
        'filterPlatformId' => ['except' => '', 'as' => 'platform'],
    ];

    // --- Initialization ---
    public function mount() {
        $this->colleges = College::orderBy('name')->get();
        $this->platforms = YearbookPlatform::orderBy('year', 'desc')->get();
        $this->courses = collect(); // Initialize as empty
        $this->majors = collect();  // Initialize as empty

        // Optionally default filter to the latest platform?
        // $latestPlatform = $this->platforms->first();
        // if ($latestPlatform && empty(request()->query('platform'))) {
        //     $this->filterPlatformId = $latestPlatform->id;
        //     // Trigger course/major loading if needed based on default platform (might need adjustment)
        //     $this->courses = !empty($this->filterCollegeId) ? Course::where('college_id', $this->filterCollegeId)->orderBy('name')->get() : collect();
        //     $this->majors = !empty($this->filterCourseId) ? Major::where('course_id', $this->filterCourseId)->orderBy('name')->get() : collect();
        // }
    }

    // --- Reset page when filters change ---
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterPlatformId() { $this->resetPage(); }
    public function updatingFilterPaymentStatus() { $this->resetPage(); }
    public function updatingFilterMajorId() { $this->resetPage(); }

    // When College changes, reset Course and Major filters AND pagination
    public function updatingFilterCollegeId() {
        $this->filterCourseId = '';
        $this->filterMajorId = '';
        $this->resetPage();
    }
    // When Course changes, reset Major filter AND pagination
    public function updatingFilterCourseId() {
        $this->filterMajorId = '';
        $this->resetPage();
    }

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
            'filterPlatformId'
        );
        // Explicitly reset course/major dropdown options
        $this->courses = collect();
        $this->majors = collect();
        $this->resetPage(); // Ensure pagination resets
    }

    /**
     * Build the base query for fetching profiles based on current filters.
     * Reusable for both rendering and exporting.
     */
    private function buildFilteredQuery(): Builder
    {
        $query = YearbookProfile::query()
            ->with(['user', 'college', 'course', 'major', 'user.yearbookPhotos', 'yearbookPlatform'])
            ->where('profile_submitted', true) // Usually only want submitted profiles
            ->join('users', 'yearbook_profiles.user_id', '=', 'users.id')
            ->select('yearbook_profiles.*'); // Select profile columns to avoid ambiguity

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

        // Apply Platform Filter
        if (!empty($this->filterPlatformId)) {
             $query->where('yearbook_profiles.yearbook_platform_id', $this->filterPlatformId);
        }

        // Apply Academic Filters
        if (!empty($this->filterCollegeId)) {
            $query->where('yearbook_profiles.college_id', $this->filterCollegeId);
        }
        if (!empty($this->filterCourseId)) {
            $query->where('yearbook_profiles.course_id', $this->filterCourseId);
        }
        if (!empty($this->filterMajorId)) {
            $query->where('yearbook_profiles.major_id', $this->filterMajorId);
        }

        // Apply Payment Status Filter
        if (!empty($this->filterPaymentStatus)) {
             $query->where('yearbook_profiles.payment_status', $this->filterPaymentStatus);
        }

        // Add Sorting
         $query->orderBy('users.last_name')->orderBy('users.first_name');

        return $query;
    }


    /**
     * Export the filtered data with enhanced styling and platform-specific naming.
     */
    public function exportData()
    {
        // Validate only the export format property before proceeding
        $this->validateOnly('exportFormat');

        $query = $this->buildFilteredQuery(); // Get the query with filters applied
        
        // Generate better filename with platform info if filter is set
        $platformInfo = "";
        if (!empty($this->filterPlatformId)) {
            $platform = YearbookPlatform::find($this->filterPlatformId);
            if ($platform) {
                $platformInfo = "-{$platform->year}-{$platform->name}";
                // Clean up filename by removing spaces or special characters
                $platformInfo = preg_replace('/[^a-zA-Z0-9-]/', '_', $platformInfo);
            }
        }
        
        $date = now()->format('Ymd_His');
        $baseFilename = "yearbook_profiles{$platformInfo}_{$date}";
        $export = new YearbookProfilesExport($query); // Create an instance of the Export class

        try {
            if ($this->exportFormat === 'xlsx') {
                $filename = $baseFilename . '.xlsx';
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($this->exportFormat === 'csv') {
                $filename = $baseFilename . '.csv';
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
            } elseif ($this->exportFormat === 'json') {
                // For JSON export, include some metadata about the export
                $data = [
                    'metadata' => [
                        'generated_at' => now()->toIso8601String(),
                        'filters' => [
                            'platform' => !empty($this->filterPlatformId) ? YearbookPlatform::find($this->filterPlatformId)?->name : 'All',
                            'college' => !empty($this->filterCollegeId) ? College::find($this->filterCollegeId)?->name : 'All',
                            'course' => !empty($this->filterCourseId) ? Course::find($this->filterCourseId)?->name : 'All',
                            'payment_status' => $this->filterPaymentStatus ?: 'All',
                        ],
                        'record_count' => $query->count(),
                    ],
                    'data' => $query->get()->toArray(),
                ];
                
                $filename = $baseFilename . '.json';
                return response()->streamDownload(function () use ($data) {
                    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }, $filename, ['Content-Type' => 'application/json']);
            }

        } catch (\Exception $e) {
            Log::error("Export Error: " . $e->getMessage());
            session()->flash('error', 'An error occurred during export: ' . $e->getMessage());
            return null;
        }

        // Should not be reached if validation passes
        session()->flash('error', 'Invalid export format selected.');
        return null;
    }


    public function render()
    {
        // --- Populate Dynamic Filter Dropdowns ---
        // Colleges & Platforms are loaded once in mount.

        // Load courses based on the current filterCollegeId
        $this->courses = !empty($this->filterCollegeId)
            ? Course::where('college_id', $this->filterCollegeId)->orderBy('name')->get()
            : collect(); // Use base collection for empty state

        // Load majors based on the current filterCourseId
        $this->majors = !empty($this->filterCourseId)
            ? Major::where('course_id', $this->filterCourseId)->orderBy('name')->get()
            : collect(); // Use base collection for empty state


        // --- Build Query using reusable method ---
        $query = $this->buildFilteredQuery();

        // Paginate for display
        $profiles = $query->paginate($this->perPage);

        // Pass necessary data to the view
        return view('livewire.admin.yearbook-repository', [
            'profiles' => $profiles,
            // Pass filter options loaded in mount or here
            'colleges' => $this->colleges,
            'courses' => $this->courses,
            'majors' => $this->majors,
            'platforms' => $this->platforms,
        ]);
    }
}