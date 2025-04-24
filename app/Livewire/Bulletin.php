<?php

namespace App\Livewire;

use App\Models\Bulletin as BulletinModel; // Use Alias for the Model
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Bulletin extends Component // Livewire Component class name stays Bulletin
{
    use WithPagination, WithFileUploads;

    // Modal & Form State
    public bool $showBulletinModal = false;
    public ?int $editingBulletinId = null;

    // Form Fields
    #[Rule('required|string|max:255')]
    public string $bulletinTitle = '';
    #[Rule('required|string')]
    public string $bulletinContent = '';
    #[Rule('boolean')]
    public bool $bulletinIsPublished = false;

    // Image Upload Property
    #[Rule('nullable|image|max:2048')] // Optional image, max 2MB
    public $bulletinImage;

    // Property to display current image URL in modal when editing
    public ?string $existingImageUrl = null;

    /**
     * Open the modal for adding or editing a bulletin.
     * Includes authorization check for editing.
     */
    public function openBulletinModal(?BulletinModel $bulletin = null) // Use Alias
    {
        $this->resetErrorBag();
        $this->reset('bulletinImage');

        // Authorization Check for Editing
        // Check if trying to edit ($bulletin exists), if user is 'admin', AND if user is NOT the author
        if ($bulletin?->exists && Auth::user()->role === 'admin' && $bulletin->user_id !== Auth::id()) {
             session()->flash('error', 'You can only edit your own bulletin posts.');
             // Optionally log this attempt: Log::warning('User '.Auth::id().' attempted to edit bulletin '.$bulletin->id.' without permission.');
             return; // Stop execution, modal won't open
         }
         // If execution continues, user is Superadmin OR (Admin AND Author) OR adding new.

        if ($bulletin?->exists) { // Editing existing
            $this->editingBulletinId = $bulletin->id;
            $this->bulletinTitle = $bulletin->title;
            $this->bulletinContent = $bulletin->content;
            $this->bulletinIsPublished = $bulletin->is_published;
            $this->existingImageUrl = $bulletin->imageUrl; // Get URL via accessor
        } else { // Adding new
            $this->editingBulletinId = null;
            $this->bulletinTitle = '';
            $this->bulletinContent = '';
            $this->bulletinIsPublished = false;
            $this->existingImageUrl = null;
        }
        $this->showBulletinModal = true;
    }

    /**
     * Close the modal and reset all form-related properties.
     */
    public function closeBulletinModal()
    {
        $this->showBulletinModal = false;
        $this->reset(['editingBulletinId', 'bulletinTitle', 'bulletinContent', 'bulletinIsPublished', 'bulletinImage', 'existingImageUrl']);
        $this->resetErrorBag();
    }

    /**
     * Save or update a bulletin post. Includes authorization check for saving edits.
     */
    public function saveBulletin()
    {
        // Basic permission check for add/edit action
        if(!Auth::check() || !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
             session()->flash('error', 'You do not have permission to perform this action.');
             $this->closeBulletinModal();
             return;
         }

        // Find existing bulletin if editing
        $currentBulletin = $this->editingBulletinId ? BulletinModel::find($this->editingBulletinId) : null; // Use Alias

        // Authorization Check for Saving Edit
        // Check if editing ($currentBulletin exists), if user is 'admin', AND if user is NOT the author
        if ($currentBulletin && Auth::user()->role === 'admin' && $currentBulletin->user_id !== Auth::id()) {
             session()->flash('error', 'You can only save edits to your own bulletin posts.');
             $this->closeBulletinModal(); // Close modal as save failed
             return;
         }
         // If execution continues, user is Superadmin OR (Admin AND Author) OR adding new.

        // Validation Rules
        $validated = $this->validate([
            'bulletinTitle' => 'required|string|max:255',
            'bulletinContent' => 'required|string',
            'bulletinIsPublished' => 'boolean',
            'bulletinImage' => 'nullable|image|max:2048', // Validate image if present
        ]);

        $imagePath = $currentBulletin?->image_path; // Default to existing path if editing

        // Handle file upload
        if ($this->bulletinImage) {
            // Delete old image if editing and new one is uploaded
            if ($currentBulletin?->image_path) {
                Log::info("Deleting old bulletin image: " . $currentBulletin->image_path);
                Storage::disk('public')->delete($currentBulletin->image_path);
            }
            // Store the new image
            $imagePath = $this->bulletinImage->store('bulletin_images', 'public');
             Log::info("Stored new bulletin image: " . $imagePath);
        }
        // If creating and no image provided, $imagePath remains null (as initialized or from $currentBulletin)

        // Prepare data for DB
        $data = [
            'title' => $validated['bulletinTitle'],
            'content' => $validated['bulletinContent'],
            'image_path' => $imagePath, // Save null if no image, or the path
            'is_published' => $validated['bulletinIsPublished'],
            // Only set user_id when CREATING a new post
            // Do NOT overwrite user_id when updating
            'user_id' => $currentBulletin ? $currentBulletin->user_id : Auth::id(),
            'published_at' => null, // Default to null
        ];

         // Handle published_at logic
         if ($validated['bulletinIsPublished']) {
             $data['published_at'] = $currentBulletin?->published_at ?? now();
         }
         // If unpublishing, published_at remains null

        // Update or Create the record using Alias
        BulletinModel::updateOrCreate(['id' => $this->editingBulletinId], $data); // Use Alias

        session()->flash('message', 'Bulletin ' . ($this->editingBulletinId ? 'updated' : 'added') . ' successfully.');
        $this->closeBulletinModal(); // Close modal on success
        $this->resetPage(); // Go back to first page of pagination
    }

    /**
     * Delete a bulletin post. Includes authorization check.
     * Accepts Bulletin model instance via Livewire's model binding. Use alias for type hint.
     */
    public function deleteBulletin(BulletinModel $bulletin) // Use Alias
    {
         // Authorization Check for Deleting
         // Allow if user is Superadmin OR if user is Admin AND is the author
         $user = Auth::user();
         if (!$user || ($user->role !== 'superadmin' && ($user->role !== 'admin' || $bulletin->user_id !== $user->id))) {
             session()->flash('error', 'You do not have permission to delete this post.');
             return;
         }

        try {
            Log::info("Attempting to delete bulletin ID: " . $bulletin->id . " by User ID: " . $user->id);
            $bulletin->delete(); // Model's deleting event handles file removal
            session()->flash('message', 'Bulletin deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Bulletin Deletion Error: " . $e->getMessage(), ['bulletin_id' => $bulletin->id, 'user_id' => $user->id]);
            session()->flash('error', 'Could not delete bulletin.');
        }
         $this->resetPage(); // Go back to first page
    }

    /**
     * Render the component view.
     */
    public function render()
    {
        $user = Auth::user();
        // Determine if the current user can manage bulletins
        $canManage = $user && in_array($user->role, ['admin', 'superadmin']);

        // Build the query using Alias
        $query = BulletinModel::query() // Use Alias
                    ->with('author') // Eager load author relationship
                    ->orderBy('published_at', 'desc') // Show published posts first by date
                    ->orderBy('created_at', 'desc'); // Then order by creation date

        // Filter for non-admins: only show published posts
        if (!$canManage) {
            $query->where('is_published', true)->whereNotNull('published_at');
        }

        // Paginate the results
        $bulletins = $query->paginate(5); // Adjust items per page as needed

        return view('livewire.bulletin', [ // Keep view name as 'livewire.bulletin'
            'bulletins' => $bulletins,
            'canManage' => $canManage,
        ]);
    }
}