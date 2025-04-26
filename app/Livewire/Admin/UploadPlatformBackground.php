<?php

namespace App\Livewire\Admin;

use App\Models\YearbookPlatform;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On; // Import On attribute for listeners
use Livewire\Attributes\Rule;

// This component doesn't need a layout as it's just a modal
class UploadPlatformBackground extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public ?YearbookPlatform $platform = null; // Platform to update
    public ?int $platformId = null;

    #[Rule('required|image|max:5120')] // Required image, max 5MB
    public $newImage;

    // Listen for the event dispatched from the header button
    #[On('open-background-upload-modal')]
    public function openModal(int $platformId)
    {
        $this->platform = YearbookPlatform::find($platformId);
        if ($this->platform) {
            $this->platformId = $platformId;
            $this->reset('newImage'); // Reset file input
            $this->resetErrorBag();
            $this->showModal = true;
        } else {
            // Handle error: Platform not found
            $this->dispatch('swal:alert', type: 'error', message: 'Platform not found.'); // Example using SweetAlert event
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['platform', 'platformId', 'newImage']);
    }

    public function saveImage()
    {
        if (!$this->platform) {
            $this->dispatch('swal:alert', type: 'error', message: 'Platform not loaded.');
            return;
        }

        $validated = $this->validate();

        try {
             // Delete old image if exists
             if ($this->platform->background_image_path && Storage::disk('public')->exists($this->platform->background_image_path)) {
                 Storage::disk('public')->delete($this->platform->background_image_path);
             }

             // Store new image
             $path = $validated['newImage']->store('platform_backgrounds', 'public');

             // Update platform record
             $this->platform->update(['background_image_path' => $path]);

             session()->flash('message', 'Platform background image updated successfully.'); // Use session flash for main page
             $this->closeModal();

             // Emit event to tell parent (Dashboard) to refresh platform data
             $this->dispatch('platform-image-updated');

         } catch (\Exception $e) {
             Log::error("Error uploading platform background: " . $e->getMessage());
             $this->addError('newImage', 'Failed to upload image. Please try again.');
         }
    }


    public function render()
    {
        // Render the modal view
        return view('livewire.admin.upload-platform-background');
    }
}