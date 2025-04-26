{{-- Modal Structure --}}
<div x-data="{ show: $wire.entangle('showModal').live }"
    x-show="show" x-on:keydown.escape.window="show = false"
    class="fixed inset-0 z-[60] overflow-y-auto" {{-- Ensure high z-index --}}
    aria-labelledby="modal-title-upload-bg" role="dialog" aria-modal="true"
    style="display: none;"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    {{-- Overlay --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-80" @click="show = false"></div>

    {{-- Modal Panel --}}
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div @click.stop
             class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-show="show"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <form wire:submit.prevent="saveImage">
                {{-- Modal Header --}}
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                     <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title-upload-bg">
                        Update Background Image for {{ $platform?->name ?? 'Platform' }}
                     </h3>
                </div>

                {{-- Modal Body --}}
                <div class="px-4 py-5 sm:p-6 space-y-4">
                    {{-- File Input --}}
                    <div>
                        <label for="newImageUpload" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select New Image *</label>
                        <input type="file" id="newImageUpload" wire:model="newImage" required accept="image/jpeg,image/png,image/webp,image/gif" {{-- Be specific with accepted types --}}
                               class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer focus:outline-none
                                      file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold
                                      file:bg-indigo-50 dark:file:bg-indigo-900/50 file:text-indigo-700 dark:file:text-indigo-300
                                      hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800/60">
                        <div wire:loading wire:target="newImage" class="mt-1 text-xs text-indigo-500 dark:text-indigo-400 animate-pulse">Processing upload...</div>
                        @error('newImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Preview Section --}}
                    <div class="mt-2 space-y-2">
                        @if ($newImage && !$errors->has('newImage'))
                            <div>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">New Image Preview:</span>
                                <img src="{{ $newImage->temporaryUrl() }}" alt="New image preview" class="mt-1 max-h-40 h-auto w-auto rounded shadow border dark:border-gray-600">
                            </div>
                        @elseif ($platform?->backgroundImageUrl) {{-- Use relationship directly if $platform is loaded --}}
                             <div>
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Current Image:</span>
                                <img src="{{ $platform->backgroundImageUrl }}" alt="Current image" class="mt-1 max-h-40 h-auto w-auto rounded shadow border dark:border-gray-600">
                            </div>
                        @endif
                    </div>
                </div>

                 {{-- Modal Footer --}}
                 <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveImage"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveImage">Save Image</span>
                        <span wire:loading wire:target="saveImage">Saving...</span>
                    </button>
                    <button type="button" wire:click="closeModal" @click="show = false" wire:loading.attr="disabled"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                 </div>
            </form>
        </div>
    </div>
</div>