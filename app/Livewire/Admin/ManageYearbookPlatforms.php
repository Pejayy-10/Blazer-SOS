<?php

namespace App\Livewire\Admin;

use App\Models\YearbookPlatform;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Import Carbon for date comparison
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;


#[Layout('components.layouts.app')]
#[Title('Manage Yearbook Platforms')]
class ManageYearbookPlatforms extends Component
{
    use WithPagination, WithFileUploads;

    // Modal & Form State
    public bool $showPlatformModal = false;
    public ?int $editingPlatformId = null;

    // Form Fields
    #[Rule('required|integer|digits:4|min:2000')]
    public string $platformYear = '';
    #[Rule('required|string|max:255')]
    public string $platformName = '';
    #[Rule('nullable|string|max:255')]
    public string $platformThemeTitle = ''; // From previous addition
    #[Rule('required|string|in:setup,open,closed,printing,archived')]
    public string $platformStatus = 'setup';
    #[Rule('boolean')]
    public bool $platformIsActive = false;
    #[Rule('nullable|image|max:5120')]
    public $platformImageUpload; // From previous addition
    public ?string $existingImageUrl = null; // From previous addition

    // Stock and Price fields (from previous context, assuming they are now part of the form)
    #[Rule('nullable|integer|min:0')]
    public string $platformInitialStock = '0';
    #[Rule('nullable|numeric|min:0')]
    public string $platformPrice = '';


    public function openPlatformModal(?YearbookPlatform $platform = null)
    {
        $this->resetErrorBag();
        $this->reset('platformImageUpload');
        $this->editingPlatformId = $platform?->id;
        $this->platformYear = $platform?->year ?? '';
        $this->platformName = $platform?->name ?? '';
        $this->platformThemeTitle = $platform?->theme_title ?? '';
        $this->platformStatus = $platform?->status ?? 'setup';
        $this->platformIsActive = $platform?->is_active ?? false;
        $this->existingImageUrl = $platform?->backgroundImageUrl;

        // Load stock and price
        $this->platformInitialStock = $platform?->stock?->initial_stock ?? '0';
        $this->platformPrice = $platform?->stock?->price ?? '';

        $this->showPlatformModal = true;
    }

    public function closePlatformModal()
    {
        $this->showPlatformModal = false;
        $this->reset(['editingPlatformId', 'platformYear', 'platformName', 'platformThemeTitle', 'platformStatus', 'platformIsActive', 'platformImageUpload', 'existingImageUrl', 'platformInitialStock', 'platformPrice']);
        $this->resetErrorBag();
    }

    public function savePlatform()
    {
        $rules = [
            'platformYear' => ['required', 'integer', 'digits:4', 'min:2000'],
            'platformName' => ['required', 'string', 'max:255'],
            'platformThemeTitle' => ['nullable', 'string', 'max:255'],
            'platformStatus' => ['required', 'string', 'in:setup,open,closed,printing,archived'],
            'platformIsActive' => ['boolean'],
            'platformImageUpload' => ['nullable', 'image', 'max:5120'],
            'platformInitialStock' => ['nullable', 'integer', 'min:0'],
            'platformPrice' => ['nullable', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'], // Allow up to 2 decimal places
        ];
        $rules['platformYear'][] = $this->editingPlatformId
            ? 'unique:yearbook_platforms,year,' . $this->editingPlatformId
            : 'unique:yearbook_platforms,year';

        $validated = $this->validate($rules);

        $imagePathToSave = $this->editingPlatformId ? YearbookPlatform::find($this->editingPlatformId)?->background_image_path : null;

        if ($this->platformImageUpload) {
            if ($this->editingPlatformId && $imagePathToSave && Storage::disk('public')->exists($imagePathToSave)) {
                Storage::disk('public')->delete($imagePathToSave);
            }
            $imagePathToSave = $this->platformImageUpload->store('platform_backgrounds', 'public');
        }

        try {
            $platform = YearbookPlatform::updateOrCreate(
                ['id' => $this->editingPlatformId],
                [
                    'year' => $validated['platformYear'],
                    'name' => $validated['platformName'],
                    'theme_title' => $validated['platformThemeTitle'],
                    'background_image_path' => $imagePathToSave,
                    'status' => $validated['platformStatus'],
                    'is_active' => $validated['platformIsActive'],
                ]
            );

             // Save/Update Stock Information
            if ($platform) {
                $platform->stock()->updateOrCreate(
                    ['yearbook_platform_id' => $platform->id],
                    [
                        'initial_stock' => $validated['platformInitialStock'] ?? 0,
                        // When admin sets initial stock, available stock should match
                        'available_stock' => $validated['platformInitialStock'] ?? 0,
                        'price' => $validated['platformPrice'] ?: null,
                    ]
                );
            }

            session()->flash('message', 'Yearbook Platform ' . ($this->editingPlatformId ? 'updated' : 'added') . ' successfully.');
            $this->closePlatformModal();
            $this->resetPage();
            $this->dispatch('platform-image-updated');

        } catch (\Exception $e) {
            Log::error("Error saving Yearbook Platform: " . $e->getMessage());
            session()->flash('error', 'Could not save platform. Details: ' . $e->getMessage());
        }
    }

    public function deletePlatform(YearbookPlatform $platform)
    {
        if ($platform->is_active) {
             session()->flash('error', 'Cannot delete the currently active platform. Please activate another platform first.');
             return;
        }
         if ($platform->yearbookProfiles()->exists()) {
             session()->flash('error', 'Cannot delete a platform with associated student profiles. Consider archiving instead.');
             return;
         }
         // Add check for stock/orders if necessary before deleting
        try {
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
     * Prevent activation of past year platforms.
     */
     public function activatePlatform(YearbookPlatform $platform)
     {
         // Check if the platform's year is in the past
         if ($platform->year < Carbon::now()->year) {
             session()->flash('error', 'Cannot activate a platform from a past year. Only current or future year platforms can be set active.');
             return;
         }

         if (!$platform->is_active) { // Double check it's not already active before update
            try {
                $platform->update(['is_active' => true]); // Boot method handles deactivating others
                session()->flash('message', $platform->name . ' activated successfully.');
            } catch (\Exception $e) {
                 Log::error("Error activating Yearbook Platform ID {$platform->id}: " . $e->getMessage());
                 session()->flash('error', 'Could not activate the platform.');
            }
        }
     }
     
     /**
      * Manage stock for a platform
      */
     public function updateStock(YearbookPlatform $platform, int $newStock)
     {
         if ($newStock < 0) {
             session()->flash('error', 'Stock cannot be negative.');
             return;
         }
         
         try {
             // Try to update through relationship
             $stockResult = $platform->stock()->updateOrCreate(
                 ['yearbook_platform_id' => $platform->id],
                 [
                     'available_stock' => $newStock,
                     // Don't update initial_stock here as it should be set once
                 ]
             );
             
             session()->flash('message', 'Stock updated successfully.');
         } catch (\Exception $e) {
             // If there's an error, try direct DB insert as fallback
             try {
                 \Illuminate\Support\Facades\DB::table('yearbook_stocks')->updateOrInsert(
                     ['yearbook_platform_id' => $platform->id],
                     [
                         'available_stock' => $newStock,
                         'initial_stock' => $newStock, // Set initial stock if this is a new record
                         'price' => 2300.00, // Default price
                         'created_at' => now(),
                         'updated_at' => now(),
                     ]
                 );
                 session()->flash('message', 'Stock updated successfully.');
             } catch (\Exception $innerEx) {
                 Log::error("Error updating stock for platform ID {$platform->id}: " . $e->getMessage() . ' | ' . $innerEx->getMessage());
                 session()->flash('error', 'Could not update stock. Please contact technical support.');
             }
         }
     }


    public function render()
    {
        try {
            // Try to load platforms with their stock
            $platforms = YearbookPlatform::with('stock')->orderBy('year', 'desc')->paginate(10);
        } catch (\Exception $e) {
            // If the with('stock') fails, try without it
            Log::error("Error loading platforms with stock: " . $e->getMessage());
            try {
                $platforms = YearbookPlatform::orderBy('year', 'desc')->paginate(10);
            } catch (\Exception $innerEx) {
                // If that also fails, return an empty collection
                Log::error("Error loading platforms: " . $innerEx->getMessage());
                $platforms = collect([])->paginate(10);
            }
        }
        
        $statusOptions = [
            'setup' => 'Setup (Not Visible/Usable)',
            'open' => 'Open (Accepting Submissions)',
            'closed' => 'Closed (Submissions Ended)',
            'printing' => 'Printing',
            'archived' => 'Archived (May be available for past purchase)',
        ];

        return view('livewire.admin.manage-yearbook-platforms', [
            'platforms' => $platforms,
            'statusOptions' => $statusOptions,
        ]);
    }
}