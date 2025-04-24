<?php

namespace App\Livewire\Admin;

use App\Models\YearbookPlatform; // Use the new model
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log; // For logging errors

#[Layout('components.layouts.app')]
#[Title('Manage Yearbook Platforms')]
class ManageYearbookPlatforms extends Component
{
    use WithPagination;

    // Modal & Form State
    public bool $showPlatformModal = false;
    public ?int $editingPlatformId = null;

    // Form Fields
    #[Rule('required|integer|digits:4|min:2000')] // Base year validation
    public string $platformYear = ''; // Input type="number" but Livewire binds as string initially

    #[Rule('required|string|max:255')]
    public string $platformName = ''; // e.g., AY 2024-2025 Yearbook

    #[Rule('required|string|in:setup,open,closed,printing,archived')] // Valid statuses
    public string $platformStatus = 'setup';

    #[Rule('boolean')]
    public bool $platformIsActive = false; // Allow setting active status

    /**
     * Open the modal for adding or editing.
     */
    public function openPlatformModal(?YearbookPlatform $platform = null)
    {
        $this->resetErrorBag();
        $this->editingPlatformId = $platform?->id;
        $this->platformYear = $platform?->year ?? '';
        $this->platformName = $platform?->name ?? '';
        $this->platformStatus = $platform?->status ?? 'setup';
        $this->platformIsActive = $platform?->is_active ?? false;
        $this->showPlatformModal = true;
    }

    /**
     * Close the modal and reset form fields.
     */
    public function closePlatformModal()
    {
        $this->showPlatformModal = false;
        $this->reset(['editingPlatformId', 'platformYear', 'platformName', 'platformStatus', 'platformIsActive']);
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
            'platformStatus' => ['required', 'string', 'in:setup,open,closed,printing,archived'],
            'platformIsActive' => ['boolean'],
        ];

        // Add unique validation rule for year, ignoring the current record if editing
        $rules['platformYear'][] = $this->editingPlatformId
            ? 'unique:yearbook_platforms,year,' . $this->editingPlatformId // Rule for editing
            : 'unique:yearbook_platforms,year'; // Rule for creating

        // Define custom attribute names for user-friendly validation messages
        $validationAttributes = [
            'platformYear' => 'year',
            'platformName' => 'display name',
            'platformStatus' => 'status',
            'platformIsActive' => 'active status',
        ];

        $validated = $this->validate($rules, [], $validationAttributes);

        try {
            YearbookPlatform::updateOrCreate(
                ['id' => $this->editingPlatformId], // Conditions for finding existing record
                [                                  // Data to update or create with
                    'year' => $validated['platformYear'],
                    'name' => $validated['platformName'],
                    'status' => $validated['platformStatus'],
                    'is_active' => $validated['platformIsActive'], // Boot method handles deactivating others
                ]
            );

            session()->flash('message', 'Yearbook Platform ' . ($this->editingPlatformId ? 'updated' : 'added') . ' successfully.');
            $this->closePlatformModal();
            $this->resetPage(); // Reset pagination if needed

        } catch (\Exception $e) {
            Log::error("Error saving Yearbook Platform: " . $e->getMessage());
            session()->flash('error', 'Could not save platform. Please check the data and try again.');
            // Optionally add specific error back to form field if possible
            // $this->addError('platformYear', 'An error occurred saving the platform.');
        }
    }

    /**
     * Delete a platform.
     */
    public function deletePlatform(YearbookPlatform $platform)
    {
        // --- Safety Checks (adjust as needed) ---
        if ($platform->is_active) {
             session()->flash('error', 'Cannot delete the currently active platform. Please activate another platform first.');
             return;
        }
        // Check if profiles are linked (adjust relationship name if needed)
         if ($platform->yearbookProfiles()->exists()) {
             session()->flash('error', 'Cannot delete a platform with associated student profiles. Consider archiving instead.');
             return;
         }
         // --- End Safety Checks ---

        try {
            $platform->delete();
            session()->flash('message', 'Yearbook Platform deleted successfully.');
            $this->resetPage(); // Reset pagination
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
                // Setting is_active to true will trigger the boot method
                // in the model to deactivate any other active platform.
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
        // Fetch platforms, newest year first
        $platforms = YearbookPlatform::orderBy('year', 'desc')->paginate(10);

        // Define options for the status dropdown in the modal
        $statusOptions = [
            'setup' => 'Setup (Not Visible/Usable)',
            'open' => 'Open (Accepting Submissions)',
            'closed' => 'Closed (Submissions Ended)',
            'printing' => 'Printing',
            'archived' => 'Archived',
        ];

        return view('livewire.admin.manage-yearbook-platforms', [
            'platforms' => $platforms,
            'statusOptions' => $statusOptions, // Pass options to the view
        ]);
    }
}