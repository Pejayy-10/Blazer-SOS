<div> {{-- Livewire components require a single root element --}}
    <form wire:submit.prevent="save" class="space-y-8">

        {{-- Success/Error Messages --}}
        <div class="mb-4">
            @if (session()->has('message'))
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    <span class="font-medium">Success!</span> {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                 <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                     <span class="font-medium">Error!</span> {{ session('error') }}
                 </div>
            @endif
        </div>


        {{-- Form Sections --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            {{-- Section 1: Basic Info --}}
            <div class="lg:col-span-2 space-y-6 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 border-gray-300 dark:border-gray-700">
                    Basic Information
                </h2>

                {{-- Read-only User Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 p-2.5 bg-gray-100 dark:bg-gray-700 rounded">{{ $firstName }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 p-2.5 bg-gray-100 dark:bg-gray-700 rounded">{{ $lastName }}</p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 p-2.5 bg-gray-100 dark:bg-gray-700 rounded">{{ $email }}</p>
                    </div>
                </div>

                 {{-- Nickname --}}
                 <div>
                     <label for="nickname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nickname</label>
                     <input type="text" id="nickname" wire:model.lazy="nickname"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm"
                            placeholder="Your preferred nickname">
                     @error('nickname') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                {{-- Middle Name (NEW) --}}
                <div>
                    <label for="middle_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Middle Name / Initial</label>
                    <input type="text" id="middle_name" wire:model.lazy="middle_name"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm"
                           placeholder="Optional">
                    @error('middle_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>


                 {{-- Year & Section --}}
                 <div>
                    <label for="year_and_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year & Section *</label>
                    <input type="text" id="year_and_section" wire:model.lazy="year_and_section" required
                           placeholder="e.g., 4ITG"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                    @error('year_and_section') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>


                 {{-- Birth Date & Calculated Age --}}
                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                     <div>
                         <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birth Date</label>
                         <input type="date" id="birth_date" wire:model.lazy="birth_date" {{-- Removed required as age is calculated --}}
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                         @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>
                     <div>
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Calculated Age</label>
                         <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 p-2.5 bg-gray-100 dark:bg-gray-700 rounded-md">
                             {{ $this->calculatedAge ?? 'N/A (Enter Birthdate)' }}
                         </p>
                         {{-- @error('age') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror --}}
                     </div>
                 </div>

                 {{-- Contact Number --}}
                 <div>
                     <label for="contact_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                     <input type="tel" id="contact_number" wire:model.lazy="contact_number" placeholder="e.g., 09171234567"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                     @error('contact_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                 {{-- Detailed Address Fields --}}
                 <div>
                    <label for="street_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Street Address <span class="text-xs text-gray-500 dark:text-gray-400">(House No., Street, Barangay)</span></label>
                    <input type="text" id="street_address" wire:model.lazy="street_address"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                    @error('street_address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                {{-- Country Dropdown --}}
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                    <select id="country" wire:model="country_id" wire:change="updateCities"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    @error('country_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Province/State (User input first, then filter cities) --}}
                    <div>
                        <label for="province_state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Province/State</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" id="province_state" wire:model.lazy="province_state" wire:change="updateCities"
                                   placeholder="Enter province or state"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                            <span class="hidden sm:inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300">
                                <button type="button" wire:click="updateCities" title="Update cities list based on province">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </span>
                        </div>
                        @error('province_state') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- City Dropdown --}}
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                        <select id="city" wire:model="city_id" wire:change="updateCityInfo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                            <option value="">-- Select City --</option>
                            @foreach($cities as $cityItem)
                                <option value="{{ $cityItem->id }}">{{ $cityItem->name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <span>Can't find your city?</span>
                            <button type="button" wire:click="$set('city_id', null)" class="ml-1 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Enter manually
                            </button>
                        </div>
                        @error('city_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Manual city input (shows when no city is selected) --}}
                    <div x-data="{ show: {{ $city_id ? 'false' : 'true' }} }">
                        <div x-show="show || {{ empty($city_id) ? 'true' : 'false' }}">
                            <label for="city_manual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City (Manual)</label>
                            <input type="text" id="city_manual" wire:model.lazy="city"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                            @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    {{-- Zip Code --}}
                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Zip Code</label>
                        <input type="text" id="zip_code" wire:model.lazy="zip_code"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                        @error('zip_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Address Search Button --}}
                    <div class="flex items-end">
                        <button type="button" wire:click="searchAddressViaAPI"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Find Address
                        </button>
                    </div>
                </div>

                  {{-- Parents --}}
                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                     <div>
                         <label for="mother_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's Name</label>
                         <input type="text" id="mother_name" wire:model.lazy="mother_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                         @error('mother_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>
                     <div>
                         <label for="father_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's Name</label>
                         <input type="text" id="father_name" wire:model.lazy="father_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                         @error('father_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>
                 </div>
            </div>

            {{-- Section 2: Yearbook Write-up & Options --}}
            <div class="lg:col-span-1 space-y-6 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                 <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 border-gray-300 dark:border-gray-700">
                    Yearbook Write-up
                </h2>

                 {{-- Affiliations --}}
                 <div class="space-y-2">
                     <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Affiliations (Up to 3)</label>
                     <input type="text" wire:model.lazy="affiliation_1" placeholder="Affiliation 1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                     @error('affiliation_1') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     <input type="text" wire:model.lazy="affiliation_2" placeholder="Affiliation 2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                     @error('affiliation_2') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     <input type="text" wire:model.lazy="affiliation_3" placeholder="Affiliation 3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                     @error('affiliation_3') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                 {{-- Awards --}}
                 <div>
                     <label for="awards" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Awards</label>
                     <textarea id="awards" wire:model.lazy="awards" rows="4" placeholder="List your significant awards"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm"></textarea>
                     @error('awards') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                  {{-- Mantra --}}
                 <div>
                     <label for="mantra" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mantra / Quote</label>
                     <textarea id="mantra" wire:model.lazy="mantra" rows="3" placeholder="Your personal motto or favorite quote"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm"></textarea>
                     @error('mantra') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                 <hr class="border-gray-300 dark:border-gray-700 my-6">

                 {{-- Subscription Type / Package --}}
                 <div>
                     <label for="subscription_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Yearbook Package *</label>
                     <select id="subscription_type" wire:model="subscription_type" required
                             class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                         <option value="full_package">Full Package (₱2,300)</option>
                         <option value="inclusions_only">Inclusions Only (₱1,500)</option>
                     </select>
                     @error('subscription_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        <p><strong class="font-medium text-gray-700 dark:text-gray-300">Full Package Includes:</strong> Profile spot in dividers, Physical yearbook copy.</p>
                        <p><strong class="font-medium text-gray-700 dark:text-gray-300">Inclusions Only Includes:</strong> Profile spot in dividers (No physical copy).</p>
                     </div>
                 </div>

                 {{-- Jacket Size --}}
                 <div class="mt-4">
                     <label for="jacket_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batch Jacket Size *</label>
                     <select id="jacket_size" wire:model="jacket_size" required
                             class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                         <option value="XS">XS</option>
                         <option value="S">S</option>
                         <option value="M">M</option>
                         <option value="L">L</option>
                         <option value="XL">XL</option>
                         <option value="2XL">2XL</option>
                         <option value="3XL">3XL</option>
                     </select>
                      @error('jacket_size') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                 {{-- Link to Photo Uploads page --}}
                 <div class="text-sm text-gray-600 dark:text-gray-400 mt-4 border-t pt-4 border-gray-200 dark:border-gray-700">
                    Remember to upload your photos on the <a href="{{ route('student.photos') }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">'Photos' page</a>.
                 </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="inline-flex justify-center rounded-md border border-transparent bg-[#9A382F] py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-[#5F0104] focus:outline-none focus:ring-2 focus:ring-[#9A382F] focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50">
                 <span wire:loading.remove wire:target="save"> Save Profile </span>
                 <span wire:loading wire:target="save"> Saving... </span>
            </button>
        </div>
    </form>
</div>