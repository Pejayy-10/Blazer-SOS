<div> {{-- Livewire components require a single root element --}}
    <form wire:submit.prevent="save" class="space-y-8">
        {{-- Flash Message --}}
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


        <div class="grid grid-cols-1 gap-x-8 gap-y-8 lg:grid-cols-3">

            {{-- Section 1: Basic Info & Academic --}}
            <div class="lg:col-span-2 space-y-6 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 border-gray-300 dark:border-gray-700">
                    Basic & Academic Information
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

                 {{-- === Academic Structure Dropdowns === --}}
                 <div>
                     <label for="profileCollege" class="block text-sm font-medium text-gray-700 dark:text-gray-300">College *</label>
                     <select id="profileCollege" wire:model="selectedCollegeId" wire:change="handleCollegeChange" {{-- Bind ID, Call Action --}}
                             required class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                         <option value="">-- Select College --</option>
                         @foreach ($colleges as $college)
                             <option value="{{ $college->id }}">{{ $college->name }}</option>
                         @endforeach
                     </select>
                     @error('selectedCollegeId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>
                 <div>
                     <label for="profileCourse" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course *</label>
                     <select id="profileCourse" wire:model="selectedCourseId" wire:change="handleCourseChange" {{-- Bind ID, Call Action --}}
                             required {{ $courses->isEmpty() ? 'disabled' : '' }}
                             wire:key="profile-course-{{ $selectedCollegeId }}"
                             class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                         <option value="">-- Select Course --</option>
                         @foreach ($courses as $course)
                             <option value="{{ $course->id }}">{{ $course->name }} {{ $course->abbreviation ? "({$course->abbreviation})" : '' }}</option>
                         @endforeach
                     </select>
                     @error('selectedCourseId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>
                 <div>
                    <label for="profileMajor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Major (Optional)</label>
                    <select id="profileMajor" wire:model="selectedMajorId"
                            {{ $majors->isEmpty() ? 'disabled' : '' }}
                            wire:key="profile-major-{{ $selectedCourseId }}"
                            class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                        <option value="">-- Select Major (If Applicable) --</option>
                         @foreach ($majors as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                        @endforeach
                    </select>
                     @error('selectedMajorId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                 <div> {{-- Keep Year & Section as text --}}
                     <label for="year_and_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year & Section *</label>
                     <input type="text" id="year_and_section" wire:model.lazy="year_and_section" required
                            placeholder="e.g., 4ITG"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                     @error('year_and_section') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>
                 {{-- === End Academic Structure === --}}

                 {{-- Age and Birth Date --}}
                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                         <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age</label>
                         <input type="number" id="age" wire:model.lazy="age" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                         @error('age') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>
                     <div>
                         <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birth Date</label>
                         <input type="date" id="birth_date" wire:model.lazy="birth_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                         @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>
                 </div>

                 {{-- Contact Number --}}
                 <div>
                     <label for="contact_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                     <input type="tel" id="contact_number" wire:model.lazy="contact_number" placeholder="e.g., 09171234567"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
                     @error('contact_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                 {{-- Address --}}
                 <div>
                     <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                     <textarea id="address" wire:model.lazy="address" rows="3" placeholder="Your full address"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm"></textarea>
                     @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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

            {{-- Section 2: Yearbook Content & Options --}}
            <div class="lg:col-span-1 space-y-6 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                 <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 border-gray-300 dark:border-gray-700">
                    Yearbook Details
                </h2>

                 {{-- Affiliations --}}
                 <div class="space-y-2">
                     <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Affiliations (Up to 3)</label>
                     <input type="text" wire:model.lazy="affiliation_1" placeholder="Affiliation 1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm ...">
                     @error('affiliation_1') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     <input type="text" wire:model.lazy="affiliation_2" placeholder="Affiliation 2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm ...">
                     @error('affiliation_2') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     <input type="text" wire:model.lazy="affiliation_3" placeholder="Affiliation 3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm ...">
                     @error('affiliation_3') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                 {{-- Awards --}}
                 <div>
                     <label for="awards" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Awards</label>
                     <textarea id="awards" wire:model.lazy="awards" rows="4" placeholder="List your significant awards"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm ..."></textarea>
                     @error('awards') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                  {{-- Mantra --}}
                 <div>
                     <label for="mantra" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mantra / Quote</label>
                     <textarea id="mantra" wire:model.lazy="mantra" rows="3" placeholder="Your personal motto or favorite quote"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm ..."></textarea>
                     @error('mantra') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

                 <hr class="border-gray-300 dark:border-gray-700">

                 {{-- Subscription Type --}}
                <div>
                    <label for="subscription_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type of Subscription *</label>
                    <select id="subscription_type" wire:model="subscription_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 ...">
                        <option value="Full">Full Payment</option>
                        <option value="Partial">Partial Payment</option>
                    </select>
                    @error('subscription_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Jacket Size --}}
                <div>
                    <label for="jacket_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batch Jacket Size *</label>
                    <select id="jacket_size" wire:model="jacket_size" required
                            class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 ...">
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
                 <div class="text-sm text-gray-600 dark:text-gray-400">
                    Manage your yearbook photos on the <a href="{{ route('student.photos') }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">'Photos' page</a>.
                 </div>

            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex justify-end pt-8 border-t border-gray-200 dark:border-gray-700">
            <button type="submit"
                    wire:loading.attr="disabled" wire:target="save"
                    class="inline-flex justify-center rounded-md border border-transparent bg-[#9A382F] py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-[#5F0104] focus:outline-none focus:ring-2 focus:ring-[#9A382F] focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50">
                 <span wire:loading.remove wire:target="save"> Save Profile </span>
                 <span wire:loading wire:target="save"> Saving... </span>
            </button>
        </div>
    </form>
</div>