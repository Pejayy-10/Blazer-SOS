<div>
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

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b pb-3 border-gray-200 dark:border-gray-700">
            My Academic Information
        </h2>

        {{-- Add Reminder if fields are empty on load --}}
        @if(!$selectedCollegeId || !$selectedCourseId || !$year_and_section)
        <div class="mb-4 mt-2 p-3 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
            <span class="font-medium">Reminder:</span> Please select your College, Course, and enter your Year & Section below.
        </div>
        @endif

        {{-- Edit Restriction Message --}}
        @if (!$canEdit)
             <div class="mb-4 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                Your academic information cannot be edited at this time. Please contact support if changes are needed.
             </div>
        @endif

        <form wire:submit.prevent="saveAcademicInfo" class="space-y-4">

            {{-- College Dropdown --}}
            <div>
                <label for="college" class="block text-sm font-medium text-gray-700 dark:text-gray-300">College *</label>
                <select id="college"
                        wire:model="selectedCollegeId" {{-- For binding --}}
                        wire:change="collegeSelected" {{-- Explicit action call --}}
                        required {{ !$canEdit ? 'disabled' : '' }}
                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                    <option value="">-- Select College --</option>
                    {{-- $colleges passed from render method --}}
                    @foreach ($colleges as $college)
                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                    @endforeach
                </select>
                @error('selectedCollegeId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Course Dropdown --}}
            <div>
                <label for="course" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course *</label>
                <select id="course"
                        wire:model="selectedCourseId" {{-- For binding --}}
                        wire:change="courseSelected" {{-- Explicit action call --}}
                        wire:key="course-select-{{ $selectedCollegeId }}" {{-- Add wire:key --}}
                        required {{ !$canEdit || $courses->isEmpty() ? 'disabled' : '' }} {{-- Disable if no college selected OR courses empty --}}
                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                    <option value="">-- Select Course --</option>
                    {{-- $courses passed from render method --}}
                    @if($courses->isNotEmpty())
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }} {{ $course->abbreviation ? "({$course->abbreviation})" : '' }}</option>
                        @endforeach
                    @endif
                </select>
                 @error('selectedCourseId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

             {{-- Major Dropdown (Optional) --}}
             <div>
                <label for="major" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Major (Optional)</label>
                <select id="major"
                        wire:model="selectedMajorId" {{-- For binding --}}
                        {{-- No wire:change needed unless it affects something else --}}
                        wire:key="major-select-{{ $selectedCourseId }}" {{-- Add wire:key --}}
                        {{ !$canEdit || $majors->isEmpty() ? 'disabled' : '' }} {{-- Disable if no course selected OR majors empty --}}
                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                    <option value="">-- Select Major (If Applicable) --</option>
                     {{-- $majors passed from render method --}}
                     @if($majors->isNotEmpty())
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                        @endforeach
                    @endif
                </select>
                 @error('selectedMajorId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>


            {{-- Year & Section (Keep as text input) --}}
            <div>
                <label for="year_and_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year & Section *</label>
                <input type="text" id="year_and_section" wire:model.lazy="year_and_section" required {{ !$canEdit ? 'disabled' : '' }}
                       placeholder="e.g., 4ITG"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                @error('year_and_section') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Save Button --}}
            @if($canEdit)
            <div class="pt-4 flex justify-end">
                <button type="submit"
                        wire:loading.attr="disabled" wire:target="saveAcademicInfo"
                        class="inline-flex justify-center rounded-md border border-transparent bg-[#9A382F] py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-[#5F0104] focus:outline-none focus:ring-2 focus:ring-[#9A382F] focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50">
                     <span wire:loading.remove wire:target="saveAcademicInfo">Save Changes</span>
                     <span wire:loading wire:target="saveAcademicInfo">Saving...</span>
                </button>
            </div>
            @endif

        </form>
    </div>
</div>