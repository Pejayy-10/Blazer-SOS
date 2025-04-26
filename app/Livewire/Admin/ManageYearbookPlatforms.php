<?php

namespace App\Livewire\Admin;

use App\Models\YearbookPlatform;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // <-- Add File Upload Trait
use Illuminate\Support\Facades\Auth; // Need Auth for permission check potentially
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- Import Storage

#[Layout('components.layouts.app')]
#[Title('Manage Yearbook Platforms')]
class ManageYearbookPlatforms extends Component
{
    use WithPagination, WithFileUploads; // <-- Use File Upload Trait

    // Modal & Form State
    public bool $showPlatformModal = false;
    public ?int $editingPlatformId = null;

    // Form Fields
    #[Rule('required|integer|digits:4|min:2000')]
    public string $platformYear = '';
    #[Rule('required|string|max:255')]
    public string $platformName = '';
    #[Rule('nullable|string|max:255')]
    public string $platformThemeTitle = ''; // Optional theme title
    #[Rule('required|string|in:setup,open,closed,printing,archived')]
    public string $platformStatus = 'setup';
    #[Rule('boolean')]
    public bool $platformIsActive = false;

    // Image Upload Property
    #[Rule('nullable|image|max:5120')] // Optional image, max 5MB
    public $platformImageUpload; // Temp file object
    public ?string $existingImageUrl = null; // Store existing image URL for display in modal

    /**
     * Open the modal for adding or editing.
     */
    public function openPlatformModal(?YearbookPlatform $platform = null)
    {
        $this->resetErrorBag();
        $this->reset('platformImageUpload'); // Reset file input
        $this->editingPlatformId = $platform?->id;
        $this->platformYear = $platform?->year ?? '';
        $this->platformName = $platform?->name ?? '';
        $this->platformThemeTitle = $platform?->theme_title ?? ''; // Load theme
        $this->platformStatus = $platform?->status ?? 'setup';
        $this->platformIsActive = $platform?->is_active ?? false;
        $this->existingImageUrl = $platform?->backgroundImageUrl; // Load image URL via accessor
        $this->showPlatformModal = true;
    }

    /**
     * Close the modal and reset form fields.
     */
    public function closePlatformModal()
    {
        $this->showPlatformModal = false;
        $this->reset(['editingPlatformId', 'platformYear', 'platformName', 'platformThemeTitle', 'platformStatus', 'platformIsActive', 'platformImageUpload', 'existingImageUrl']);
        $this->resetErrorBag();
    }

    /**
     * Save or update a yearbook platform.
     */
    public function savePlatform()
    {
        // Define base rules
        $rules = [
            'platformYear' => ['required', 'integer', 'digits:4', 'min:2000'],
            'platformName' => ['required', 'string', 'max:255'],
            'platformThemeTitle' => ['nullable', 'string', 'max:255'],
            'platformStatus' => ['required', 'string', 'in:setup,open,closed,printing,archived'],
            'platformIsActive' => ['boolean'],
            'platformImageUpload' => ['nullable', 'image', 'max:5120'], // Validate image
        ];

        // Add unique validation rule for year, ignoring self if editing
        $rules['platformYear'][] = $this->editingPlatformId
            ? 'unique:yearbook_platforms,year,' . $this->editingPlatformId
            : 'unique:yearbook_platforms,year';

        // Define custom attribute names
        $validationAttributes = [
            'platformYear' => 'year',
            'platformName' => 'display name',
            'platformThemeTitle' => 'theme title',
            'platformStatus' => 'status',
            'platformIsActive' => 'active status',
            'platformImageUpload' => 'background image',
        ];

        $validated = $this->validate($rules, [], $validationAttributes);

        // Find existing image path if editing
        $imagePath = $this->editingPlatformId ? YearbookPlatform::find($this->editingPlatformId)?->background_image_path : null;

        // Handle file upload
        if ($this->platformImageUpload) {
             // Delete old image if editing and new one is uploaded and old one exists
             if ($this->editingPlatformId && $imagePath && Storage::disk('public')->exists($imagePath)) {
                 Storage::disk('public')->delete($imagePath);
                 Log::info("Deleted old background image: " . $imagePath);
             }
             // Store new image
             $filename = $this->platformImageUpload->hashName();
             $imagePath = $this->platformImageUpload->store('platform_backgrounds', 'public'); // Store in specific folder
             Log::info("Stored new background image: " . $imagePath);
         }
         // If not uploading a new image while editing, $imagePath retains the existing path (or null if none existed).

        try {
            YearbookPlatform::updateOrCreate(
                ['id' => $this->editingPlatformId], // Conditions
                [                                  // Data
                    'year' => $validated['platformYear'],
                    'name' => $validated['platformName'],
                    'theme_title' => $validated['platformThemeTitle'],
                    'background_image_path' => $imagePath, // Save path (new or existing)
                    'status' => $validated['platformStatus'],
                    'is_active' => $validated['platformIsActive'],
                ]
            );

            session()->flash('message', 'Yearbook Platform ' . ($this->editingPlatformId ? 'updated' : 'added') . ' successfully.');
            $this->closePlatformModal();
            $this->resetPage();

        } catch (\Exception $e) {
            Log::error("Error saving Yearbook Platform: " . $e->getMessage());
            session()->flash('error', 'Could not save platform. Please check the data and try again.');
        }
    }

    /**
     * Delete a platform.
     */
    public function deletePlatform(YearbookPlatform $platform)
    {
        // Safety Checks
        if ($platform->is_active) {
             session()->flash('error', 'Cannot delete the currently active platform.');
             return;
        }
         if ($platform->yearbookProfiles()->exists()) {
             session()->flash('error', 'Cannot delete a platform with associated student profiles.');
             return;
         }

        try {
            // The 'deleting' boot method in the model handles image file deletion
            $platform->delete();
            session()->flash('message', 'Yearbook Platform deleted successfully.');
            $this->resetPage();
        } catch (\Exception $e) {
             Log::error("Error deleting Yearbook Platform ID {$platform->id}: " . $e->getMessage());
             session()->flash('error', 'Could not delete the platform.');
        }
    }

    /**
     * Activate a specific platform.
     */
     public function activatePlatform(YearbookPlatform $platform)
     {
         if (!$platform->is_active) {
            try {
                // Boot method in model handles deactivating others
                $platform->update(['is_active' => true]);
                session()->flash('message', $platform->name . ' activated successfully.');
            } catch (\Exception $e) {
                 Log::error("Error activating Yearbook Platform ID {$platform->id}: " . $e->getMessage());
                 session()->flash('error', 'Could not activate the platform.');
            }
        }
     }


    public function render()
    {
        $platforms = YearbookPlatform::orderBy('year', 'desc')->paginate(10);

        $statusOptions = [
            'setup' => 'Setup (Not Visible/Usable)',
            'open' => 'Open (Accepting Submissions)',
            'closed' => 'Closed (Submissions Ended)',
            'printing' => 'Printing',
            'archived' => 'Archived',
        ];

        return view('livewire.admin.manage-yearbook-platforms', [
            'platforms' => $platforms,
            'statusOptions' => $statusOptions,
        ]);
    }
}