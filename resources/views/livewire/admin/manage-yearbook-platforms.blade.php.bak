<div x-data="{ showModal: $wire.entangle('showPlatformModal').live }">
    {{-- Flash Message --}}
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

    {{-- Header and Add Button --}}
    <div class="md:flex md:items-center md:justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Manage Yearbook Platforms</h2>
        <button wire:click="openPlatformModal()" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            <svg class="h-5 w-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /> </svg>
            Add New Platform
        </button>
    </div>

    {{-- Platforms Table --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Year</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name / Theme</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Active</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Initial Stock</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Available</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($platforms as $platform)
                        <tr wire:key="platform-{{ $platform->id }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $platform->year }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $platform->name }}
                                @if($platform->theme_title)
                                    <span class="block text-xs text-gray-400 dark:text-gray-500 italic">{{ $platform->theme_title }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' => $platform->status === 'setup' || $platform->status === 'archived',
                                    'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' => $platform->status === 'open',
                                    'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100' => $platform->status === 'closed',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100' => $platform->status === 'printing',
                                ])>
                                    {{ ucfirst($platform->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                @if($platform->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"> <circle cx="4" cy="4" r="3" /> </svg>
                                        Active
                                    </span>
                                @else
                                    @if($platform->year >= now()->year) {{-- Only allow activating current or future year platforms --}}
                                         <button wire:click="activatePlatform({{ $platform->id }})" wire:loading.attr="disabled" wire:target="activatePlatform({{ $platform->id }})"
                                                 class="text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 hover:text-indigo-800 py-1 px-2 rounded transition-all duration-200 dark:bg-indigo-800 dark:text-indigo-200 dark:hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-wait" title="Click to Activate">
                                             <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Set Active
                                             </span>
                                         </button>
                                    @else
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">Past Year</span>
                                            @if($platform->status === 'archived' && ($platform->stock?->available_stock ?? 0) > 0)
                                                <span class="text-xs text-green-600 dark:text-green-400">
                                                    Available for purchase
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $platform->stock?->initial_stock ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $platform->stock?->available_stock ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $platform->stock?->price ? '₱'.number_format($platform->stock->price, 2) : 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <button wire:click="openPlatformModal({{ $platform->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">
                                    Edit
                                </button>
                                <button wire:click="deletePlatform({{ $platform->id }})" wire:confirm="Delete Platform '{{ $platform->year }} - {{ $platform->name }}'? Ensure no profiles are linked and it's not active."
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                No Yearbook Platforms found. Add one to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        @if ($platforms->hasPages())
            <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $platforms->links() }}
            </div>
        @endif
    </div>

     {{-- Add/Edit Platform Modal --}}
     <div x-show="showModal"
          class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-platform" role="dialog" aria-modal="true"
          style="display: none;"
          x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
          x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
         <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-80" @click="showModal = false"></div>
         <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div @click.stop
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="savePlatform">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                         <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title-platform">
                            {{ $editingPlatformId ? 'Edit Platform' : 'Add New Platform' }}
                         </h3>
                         <div class="space-y-4">
                             {{-- Year --}}
                             <div>
                                 <label for="platformYear" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year *</label>
                                 <input type="number" id="platformYear" wire:model.lazy="platformYear" required min="2000" max="2100" step="1" placeholder="YYYY" autofocus
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                 @error('platformYear') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                             {{-- Name --}}
                              <div>
                                 <label for="platformName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Name *</label>
                                 <input type="text" id="platformName" wire:model.lazy="platformName" required placeholder="e.g., AY 2024-2025 Yearbook"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                 @error('platformName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                             {{-- Theme Title --}}
                             <div>
                                 <label for="platformThemeTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Theme Title</label>
                                 <input type="text" id="platformThemeTitle" wire:model.lazy="platformThemeTitle" placeholder="e.g., Defying Gravity - The Flight of..."
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                 @error('platformThemeTitle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                              {{-- Background Image Upload --}}
                             <div>
                                 <label for="platformImageUpload" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Background Image</label>
                                 <input type="file" id="platformImageUpload" wire:model="platformImageUpload" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800 cursor-pointer">
                                 <div wire:loading wire:target="platformImageUpload" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Uploading...</div>
                                 @error('platformImageUpload') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                 @if ($platformImageUpload && !$errors->has('platformImageUpload'))
                                     <div class="mt-2"><span class="text-xs text-gray-500 dark:text-gray-400">New Preview:</span><img src="{{ $platformImageUpload->temporaryUrl() }}" class="mt-1 max-h-24 rounded shadow"></div>
                                 @elseif ($existingImageUrl)
                                      <div class="mt-2"><span class="text-xs text-gray-500 dark:text-gray-400">Current:</span><img src="{{ $existingImageUrl }}" class="mt-1 max-h-24 rounded shadow"></div>
                                 @endif
                             </div>
                             {{-- Status --}}
                             <div>
                                 <label for="platformStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status *</label>
                                  <select id="platformStatus" wire:model="platformStatus" required
                                         class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                     @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                     @endforeach
                                 </select>
                                 @error('platformStatus') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                            {{-- Stock and Price --}}
                            <hr class="my-4 border-gray-300 dark:border-gray-600">
                            <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">Stock & Pricing (for past purchases)</h4>
                             <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                 <div>
                                     <label for="platformInitialStock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial Stock</label>
                                     <input type="number" id="platformInitialStock" wire:model.lazy="platformInitialStock" min="0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                     @error('platformInitialStock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                 </div>
                                 <div>
                                     <label for="platformPrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (PHP)</label>
                                     <input type="text" id="platformPrice" wire:model.lazy="platformPrice" placeholder="e.g., 1500.00"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                     @error('platformPrice') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                 </div>
                             </div>

                              {{-- Is Active --}}
                             <div class="relative flex items-start mt-4">
                                 <div class="flex items-center h-5">
                                     <input id="platformIsActive" wire:model="platformIsActive" type="checkbox"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-900 dark:border-gray-600">
                                 </div>
                                 <div class="ml-3 text-sm">
                                      <label for="platformIsActive" class="font-medium text-gray-700 dark:text-gray-300">Set as Active Platform?</label>
                                      <p class="text-xs text-gray-500 dark:text-gray-400">Mark this as the current platform for new student registrations and profile submissions. Only one platform can be active at a time.</p>
                                  </div>
                             </div>

                         </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                         <button type="submit" wire:loading.attr="disabled" wire:target="savePlatform"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                           <span wire:loading.remove wire:target="savePlatform">Save Platform</span>
                           <span wire:loading wire:target="savePlatform">Saving...</span>
                        </button>
                        <button type="button" wire:click="closePlatformModal" @click="showModal = false" wire:loading.attr="disabled"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                    </div>
                </form>
             </div>
        </div>
     </div>

</div>