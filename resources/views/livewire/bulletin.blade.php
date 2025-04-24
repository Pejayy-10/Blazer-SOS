<div x-data="{ showModal: $wire.entangle('showBulletinModal').live, lightboxOpen: false, lightboxImage: '' }">
    <div class="space-y-6">
        {{-- Header + Add Button --}}
        <div class="flex justify-between items-center">
             <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Bulletin Board</h2>
             {{-- Show 'Add New' only if user is admin or superadmin --}}
             @if($canManage)
                <button wire:click="openBulletinModal()" type="button"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                     <svg class="h-4 w-4 mr-1 -ml-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    Add New
                </button>
             @endif
        </div>

         {{-- Flash Messages --}}
        <div class="mb-4">
            @if (session()->has('message'))
                <div wire:key="flash-message-success" class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('message') }}
                </div>
            @endif
             @if (session()->has('error'))
                 <div wire:key="flash-message-error" class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                     {{ session('error') }}
                 </div>
            @endif
        </div>


        {{-- Bulletin List --}}
        @if($bulletins->isEmpty())
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No bulletins posted yet.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($bulletins as $bulletin)
                    <div wire:key="bulletin-card-{{ $bulletin->id }}"
                         class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden
                                border-l-4 {{ $bulletin->is_published ? 'border-green-500' : 'border-yellow-500' }}">

                        {{-- Image Display (Optional) --}}
                        @if($bulletin->imageUrl)
                            <div class="w-full bg-gray-200 dark:bg-gray-700 cursor-pointer" @click="lightboxImage = '{{ $bulletin->imageUrl }}'; lightboxOpen = true">
                                <img src="{{ $bulletin->imageUrl }}" alt="{{ $bulletin->title }}" class="w-full h-auto max-h-60 md:max-h-72 object-contain">
                            </div>
                        @endif

                        {{-- Card Body --}}
                        <div class="p-5">
                            <div class="sm:flex sm:justify-between sm:items-start mb-2">
                                {{-- Title and Meta --}}
                                <div class="mb-2 sm:mb-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $bulletin->title }}</h3>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Posted {{ $bulletin->published_at ? $bulletin->published_at->diffForHumans() : $bulletin->created_at->diffForHumans() }}
                                        @if($bulletin->author) by {{ $bulletin->author->username }} @endif
                                        @if(!$bulletin->is_published && $canManage) <span class="ml-2 font-bold text-yellow-600">[Draft]</span> @endif
                                    </span>
                                </div>
                                 {{-- Edit/Delete buttons Section --}}
                                 @if($canManage) {{-- Only show section if user is admin/superadmin --}}
                                    <div class="flex-shrink-0 space-x-2">
                                         {{-- Edit Button: Show if Superadmin OR if Admin is the author --}}
                                         @if(Auth::user()->role === 'superadmin' || (Auth::user()->role === 'admin' && $bulletin->user_id === Auth::id()))
                                            <button wire:click="openBulletinModal({{ $bulletin->id }})" class="inline-flex items-center px-2 py-1 border border-gray-300 rounded-md shadow-sm text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600" title="Edit">Edit</button>
                                         @else {{-- Otherwise, show disabled button for non-author Admins --}}
                                            <button class="inline-flex items-center px-2 py-1 border border-gray-300 rounded-md shadow-sm text-xs font-medium text-gray-400 bg-white dark:bg-gray-700 dark:text-gray-500 dark:border-gray-600 cursor-not-allowed" title="Cannot edit other admins' posts" disabled>Edit</button>
                                         @endif

                                         {{-- Delete Button: Show if Superadmin OR if Admin is the author --}}
                                         @if(Auth::user()->role === 'superadmin' || (Auth::user()->role === 'admin' && $bulletin->user_id === Auth::id()))
                                            <button wire:click="deleteBulletin({{ $bulletin->id }})" wire:confirm="Are you sure you want to delete the bulletin '{{ $bulletin->title }}'?" class="inline-flex items-center px-2 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800" title="Delete">Delete</button>
                                         @else {{-- Otherwise, show disabled button --}}
                                             <button class="inline-flex items-center px-2 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-red-300 dark:bg-red-800 cursor-not-allowed" title="Cannot delete other admins' posts" disabled>Delete</button>
                                         @endif
                                    </div>
                                 @endif {{-- End $canManage check --}}
                            </div>
                            {{-- Content --}}
                            <div class="prose prose-sm dark:prose-invert max-w-none mt-3 text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($bulletin->content)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

         {{-- Pagination Links --}}
         @if ($bulletins->hasPages())
            <div class="mt-6">
                {{ $bulletins->links() }}
            </div>
        @endif
    </div>


     {{-- Add/Edit Bulletin Modal --}}
     <div x-show="showModal" x-trap.noscroll="showModal"
          class="fixed inset-0 z-50 overflow-y-auto"
          aria-labelledby="modal-title-bulletin" role="dialog" aria-modal="true"
          style="display: none;"
          x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
          x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
         <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-80" @click="showModal = false"></div>
         <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div @click.stop class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" x-show="showModal" x-transition...>
                <form wire:submit.prevent="saveBulletin">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                         <div class="flex justify-between items-start">
                             <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title-bulletin">
                                {{ $editingBulletinId ? 'Edit Bulletin' : 'Add New Bulletin' }}
                             </h3>
                             <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                             </button>
                         </div>

                         <div class="mt-4 space-y-4">
                             {{-- Title --}}
                             <div>
                                 <label for="modalBulletinTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title *</label>
                                 <input type="text" id="modalBulletinTitle" wire:model.lazy="bulletinTitle" required autofocus
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                 @error('bulletinTitle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                             {{-- Content --}}
                             <div>
                                 <label for="modalBulletinContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content *</label>
                                 <textarea id="modalBulletinContent" wire:model.lazy="bulletinContent" required rows="10"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"></textarea>
                                 @error('bulletinContent') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                             {{-- Image Upload --}}
                            <div>
                                <label for="modalBulletinImageUpload" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Optional Image (Max 2MB)</label>
                                <input type="file" id="modalBulletinImageUpload" wire:model="bulletinImage" accept="image/jpeg,image/png,image/webp,image/gif"
                                       class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold
                                              file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100
                                              dark:file:bg-indigo-900 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800
                                              cursor-pointer">
                                <div wire:loading wire:target="bulletinImage" class="mt-1 text-xs text-gray-500">Uploading...</div>
                                @error('bulletinImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                {{-- Image Preview --}}
                                <div class="mt-2 flex items-start space-x-4">
                                    @if ($bulletinImage && !$errors->has('bulletinImage'))
                                        <div>
                                            <span class="block text-xs font-medium text-gray-500 mb-1">New Image Preview:</span>
                                            <img src="{{ $bulletinImage->temporaryUrl() }}" alt="New image preview" class="h-20 w-auto rounded shadow">
                                        </div>
                                    @endif
                                    @if ($existingImageUrl)
                                         <div>
                                            <span class="block text-xs font-medium text-gray-500 mb-1">Current Image:</span>
                                            <img src="{{ $existingImageUrl }}" alt="Current image" class="h-20 w-auto rounded shadow">
                                        </div>
                                    @endif
                                </div>
                            </div>

                             {{-- Published Status --}}
                             <div class="flex items-center pt-2">
                                 <input id="bulletinIsPublished" wire:model="bulletinIsPublished" type="checkbox"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-900 dark:border-gray-600">
                                 <label for="bulletinIsPublished" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                     Published (Visible to Students)
                                 </label>
                             </div>
                         </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                         <button type="submit" wire:loading.attr="disabled" wire:target="saveBulletin"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <span wire:loading wire:target="saveBulletin">Saving...</span>
                            <span wire:loading.remove wire:target="saveBulletin">Save Bulletin</span>
                        </button>
                        <button type="button" wire:click="closeBulletinModal" @click="showModal = false" wire:loading.attr="disabled"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                    </div>
                </form>
             </div>
         </div>
     </div> {{-- End Add/Edit Modal --}}


     {{-- Image Lightbox Modal --}}
    <div x-show="lightboxOpen"
         x-on:keydown.escape.window="lightboxOpen = false"
         class="fixed inset-0 z-[99] flex items-center justify-center p-4 bg-black bg-opacity-80"
         style="display: none;"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.outside="lightboxOpen = false" class="relative max-w-[90vw] max-h-[85vh]" x-show="lightboxOpen" x-transition...>
            <img :src="lightboxImage" alt="Bulletin Image Lightbox" class="block max-w-full max-h-[85vh] object-contain rounded-lg shadow-lg">
             <button @click="lightboxOpen = false" class="absolute -top-2 -right-2 m-2 p-1 bg-white/70 hover:bg-white rounded-full text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                 <span class="sr-only">Close lightbox</span>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
             </button>
        </div>
    </div>{{-- End Lightbox --}}

</div>