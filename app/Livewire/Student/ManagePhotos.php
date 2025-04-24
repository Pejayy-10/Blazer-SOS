<?php

namespace App\Livewire\Student;

use App\Models\YearbookPhoto; // Import the model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads; // Import the trait for file uploads
use Illuminate\Support\Collection; // Import Collection for type hinting

#[Layout('components.layouts.app')]
#[Title('Manage Yearbook Photos')]
class ManagePhotos extends Component
{
    use WithFileUploads; // Use the trait

    // Property for the file input binding
    #[Rule('image|max:5120')] // Example validation: image file, max 5MB (5120 KB)
    public $uploadedPhoto;

    // Collection to hold existing photos
    public Collection $photos;

    // Maximum number of photos allowed per user
    public int $maxPhotos = 3; // Set your limit here

    /**
     * Load existing photos when component mounts.
     */
    public function mount()
    {
        $this->loadPhotos();
    }

    /**
     * Helper function to reload photos.
     */
    protected function loadPhotos()
    {
        // Eager load is usually not needed here as we are getting them directly
        $this->photos = Auth::user()->yearbookPhotos()->orderBy('order')->get();
    }

    /**
     * Handle the photo upload and save process.
     */
    public function uploadPhoto()
    {
        // Validate the uploaded file
        $this->validate([
            'uploadedPhoto' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // Example: allow jpg, png, webp up to 5MB
        ]);

        // Check if the user has reached the photo limit
        if ($this->photos->count() >= $this->maxPhotos) {
             session()->flash('error', 'You have reached the maximum limit of ' . $this->maxPhotos . ' photos.');
             $this->reset('uploadedPhoto'); // Clear the input
             return;
         }

        // Store the file
        $user = Auth::user();
        $filename = $this->uploadedPhoto->hashName(); // Generate unique filename
        // Store in 'yearbook_photos/user_X' directory on the 'public' disk
        $path = $this->uploadedPhoto->store("yearbook_photos/user_{$user->id}", 'public');

        if ($path) {
            // Create database record
            YearbookPhoto::create([
                'user_id' => $user->id,
                'path' => $path,
                'original_filename' => $this->uploadedPhoto->getClientOriginalName(),
                'order' => $this->photos->count() + 1, // Simple ordering based on upload count
            ]);

            session()->flash('message', 'Photo uploaded successfully!');
            $this->reset('uploadedPhoto'); // Clear the file input
            $this->loadPhotos(); // Refresh the photo list
        } else {
             session()->flash('error', 'Could not save the uploaded photo.');
             $this->reset('uploadedPhoto');
        }
    }

    /**
     * Delete a specific photo.
     */
    public function deletePhoto(int $photoId)
    {
        // Find the photo belonging ONLY to the currently logged-in user
        $photo = Auth::user()->yearbookPhotos()->find($photoId);

        if ($photo) {
             // Deleting the model record will trigger the 'deleting' event in the model
             // which should delete the file from storage (if configured correctly in the model boot method).
            if ($photo->delete()) {
                session()->flash('message', 'Photo deleted successfully.');
            } else {
                session()->flash('error', 'Failed to delete photo record.');
            }
            $this->loadPhotos(); // Refresh the photo list
        } else {
            session()->flash('error', 'Photo not found or you do not have permission to delete it.');
        }
    }


    public function render()
    {
        return view('livewire.student.manage-photos');
    }
}