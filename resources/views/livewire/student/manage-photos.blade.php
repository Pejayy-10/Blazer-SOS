<div> {{-- Single root element --}}

    {{-- Flash Messages --}}
    <div class="mb-4">
        @if (session()->has('message'))
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
             <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                 {{ session('error') }}
             </div>
        @endif
    </div>

    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Manage Yearbook Photos (Max: {{ $maxPhotos }})</h2>

    {{-- Existing Photos Grid --}}
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Your Uploaded Photos ({{ $photos->count() }}/{{ $maxPhotos }})</h3>
        @if($photos->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($photos as $photo)
                    {{-- Container Div --}}
                    <div wire:key="photo-{{ $photo->id }}"
                        class="relative aspect-square"
                        x-data="{ hovered: false }"
                        @mouseenter="hovered = true"
                        @mouseleave="hovered = false">
                    
                       {{-- Image tag --}}
                       <img src="{{ $photo->url }}" alt="Yearbook Photo {{ $loop->iteration }}"
                            class="object-cover w-full h-full rounded-lg shadow">
                    
                       {{-- Overlay Div - Control with visibility --}}
                       <div class="absolute inset-0 bg-black bg-opacity-50 {{-- Keep semi-transparent background --}}
                                   transition-opacity duration-200 {{-- Keep transition if desired --}}
                                   flex items-center justify-center rounded-lg"
                            :class="{ 'visible': hovered, 'invisible': !hovered }"> {{-- Use visibility classes --}}
                    
                           {{-- Delete Button - Also control with visibility --}}
                           <button wire:click="deletePhoto({{ $photo->id }})"
                                   wire:confirm="Are you sure you want to delete this photo?"
                                   class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                                          transition-opacity duration-200" {{-- Keep transition --}}
                                   :class="{ 'visible': hovered, 'invisible': !hovered }" {{-- Use visibility classes --}}
                                   title="Delete Photo">
                               <svg class="h-5 w-5" ...></svg>
                           </button>
                       </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Message when no photos uploaded --}}
            <div class="text-center py-6 px-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <p class="text-gray-500 dark:text-gray-400">You haven't uploaded any photos yet.</p>
            </div>
        @endif
    </div>

    {{-- Upload Form Area --}}
    @if($photos->count() < $maxPhotos) {{-- Only show upload if limit not reached --}}
        <div class="mt-6 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
             <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Upload New Photo</h3>
             <form wire:submit.prevent="uploadPhoto">
                 <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                     {{-- File Input --}}
                     <div class="flex-grow w-full">
                        <label for="photo-upload" class="sr-only">Choose photo</label>
                        <input type="file" id="photo-upload" wire:model="uploadedPhoto"
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-l-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 dark:file:bg-indigo-900
                                      file:text-indigo-700 dark:file:text-indigo-300
                                      hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800"
                               accept="image/jpeg,image/png,image/webp"> {{-- Match mimes from validation --}}

                         @error('uploadedPhoto') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>

                    {{-- Upload Button --}}
                    <div class="flex-shrink-0">
                         <button type="submit"
                                 wire:loading.attr="disabled" wire:target="uploadPhoto"
                                 class="inline-flex items-center justify-center rounded-md border border-transparent bg-[#9A382F] py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-[#5F0104] focus:outline-none focus:ring-2 focus:ring-[#9A382F] focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50">
                              <span wire:loading.remove wire:target="uploadPhoto">
                                <svg class="h-5 w-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L6.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                                Upload
                              </span>
                             <span wire:loading wire:target="uploadPhoto">Uploading...</span>
                         </button>
                     </div>
                 </div>

                 {{-- Upload Progress & Preview --}}
                 <div wire:loading wire:target="uploadedPhoto" class="mt-3 text-sm text-gray-600 dark:text-gray-400">Uploading...</div>
                 @if ($uploadedPhoto && !$errors->has('uploadedPhoto')) {{-- Show preview only if valid file selected --}}
                    <div class="mt-4">
                         <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                         <img src="{{ $uploadedPhoto->temporaryUrl() }}" alt="Upload Preview" class="max-h-48 rounded-lg shadow">
                    </div>
                 @endif
             </form>
        </div>
    @else
        {{-- Message when max photos reached --}}
        <div class="mt-6 p-4 bg-blue-100 dark:bg-blue-900 border border-blue-300 dark:border-blue-700 text-blue-800 dark:text-blue-200 rounded-lg shadow text-center">
            You have uploaded the maximum number of photos ({{ $maxPhotos }}). Delete an existing photo to upload a new one.
        </div>
    @endif

</div>