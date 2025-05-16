<div x-data="{}"> {{-- Add root x-data for Alpine entangle if not already present --}}
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

    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Manage Academic Structure</h2>

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Column 1: Colleges List --}}
        <div class="md:col-span-1 bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col" style="min-height: 400px;"> 
            <div class="flex justify-between items-center mb-3 flex-shrink-0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Colleges</h3>
                <button wire:click="openCollegeModal()" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Add New
                </button>
            </div>
            <div class="flex-grow overflow-y-auto pr-2">
                <ul class="space-y-2">
                    @forelse ($colleges as $college)
                        <li wire:key="college-list-{{ $college->id }}"
                            class="p-2 rounded-md cursor-pointer border
                                {{-- Use selectedCollegeId for highlighting --}}
                                {{ $selectedCollegeId === $college->id ? 'bg-indigo-100 dark:bg-indigo-900 border-indigo-300 dark:border-indigo-700' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                            wire:click="$set('selectedCollegeId', {{ $college->id }})"> {{-- Set the ID --}}
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $college->name }} {{ $college->abbreviation ? "({$college->abbreviation})" : '' }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $college->courses_count }} {{ Str::plural('Course', $college->courses_count) }})</span>
                            </div>
                             {{-- Use selectedCollegeId for showing buttons --}}
                             @if($selectedCollegeId === $college->id)
                                <div class="mt-1 text-right space-x-1">
                                    <button wire:click.stop="openCollegeModal({{ $college->id }})" class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">Edit</button>
                                    <button wire:click.stop="deleteCollege({{ $college->id }})" wire:confirm="Delete '{{ $college->name }}'? This will also delete associated courses and majors!" class="text-xs text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">Delete</button>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="p-3 text-center text-sm text-gray-500 dark:text-gray-400">No colleges found.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Column 2: Courses List --}}
        <div class="md:col-span-1 bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col {{ !$selectedCollegeId ? 'opacity-50 pointer-events-none' : '' }}" style="min-height: 400px;">
            <div class="flex justify-between items-center mb-3 flex-shrink-0">
                 <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                      {{-- Use selectedCollegeObject for header display --}}
                     Courses {{ $selectedCollegeObject ? 'in ' . $selectedCollegeObject->abbreviation : '' }}
                 </h3>
                 @if($selectedCollegeId)
                    <button wire:click="openCourseModal()" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        Add New
                    </button>
                 @endif
            </div>
             <div class="flex-grow overflow-y-auto pr-2">
                @if($selectedCollegeId)
                    <ul class="space-y-2">
                        @forelse ($courses as $course)
                           <li wire:key="course-list-{{ $course->id }}"
                               class="p-2 rounded-md cursor-pointer border
                                      {{-- Use selectedCourseId for highlighting --}}
                                      {{ $selectedCourseId === $course->id ? 'bg-indigo-100 dark:bg-indigo-900 border-indigo-300 dark:border-indigo-700' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                                wire:click="$set('selectedCourseId', {{ $course->id }})"> {{-- Set the ID --}}
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $course->name }} {{ $course->abbreviation ? "({$course->abbreviation})" : '' }}</span>
                                     <span class="text-xs text-gray-500 dark:text-gray-400">({{ $course->majors_count }} {{ Str::plural('Major', $course->majors_count) }})</span>
                                </div>
                                {{-- Use selectedCourseId for showing buttons --}}
                                @if($selectedCourseId === $course->id)
                                    <div class="mt-1 text-right space-x-1">
                                        <button wire:click.stop="openCourseModal({{ $course->id }})" class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">Edit</button>
                                        <button wire:click.stop="deleteCourse({{ $course->id }})" wire:confirm="Delete '{{ $course->name }}'? This will also delete associated majors!" class="text-xs text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">Delete</button>
                                    </div>
                                 @endif
                            </li>
                        @empty
                            <li class="p-3 text-center text-sm text-gray-500 dark:text-gray-400">No courses found for this college.</li>
                        @endforelse
                    </ul>
                     {{-- Pagination --}}
                     @if ($courses->hasPages())
                        <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-2">
                            {{ $courses->links() }}
                        </div>
                    @endif
                @else
                     <p class="p-3 text-center text-sm text-gray-500 dark:text-gray-400">Select a college to view courses.</p>
                @endif
            </div>
        </div>

        {{-- Column 3: Majors List --}}
        <div class="md:col-span-1 bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col {{ !$selectedCourseId ? 'opacity-50 pointer-events-none' : '' }}" style="min-height: 400px;">
             <div class="flex justify-between items-center mb-3 flex-shrink-0">
                 <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                     {{-- Use selectedCourseObject for header display --}}
                     Majors {{ $selectedCourseObject ? 'in ' . $selectedCourseObject->abbreviation : '' }}
                 </h3>
                 @if($selectedCourseId)
                    <button wire:click="openMajorModal()" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        Add New
                    </button>
                 @endif
             </div>
              <div class="flex-grow overflow-y-auto pr-2">
                 @if($selectedCourseId)
                    <ul class="space-y-2">
                         @forelse ($majors as $major)
                            <li wire:key="major-list-{{ $major->id }}" class="p-2 rounded-md border border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $major->name }}</span>
                                <div class="space-x-1 flex-shrink-0">
                                    <button wire:click="openMajorModal({{ $major->id }})" class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">Edit</button>
                                    <button wire:click="deleteMajor({{ $major->id }})" wire:confirm="Delete Major '{{ $major->name }}'?" class="text-xs text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">Delete</button>
                                </div>
                            </li>
                         @empty
                            <li class="p-3 text-center text-sm text-gray-500 dark:text-gray-400">No majors found for this course.</li>
                         @endforelse
                    </ul>
                    {{-- Pagination --}}
                     @if ($majors->hasPages())
                        <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-2">
                            {{ $majors->links() }}
                        </div>
                    @endif
                 @else
                      <p class="p-3 text-center text-sm text-gray-500 dark:text-gray-400">Select a course to view majors.</p>
                 @endif
            </div>
        </div>

    </div> {{-- End Main Grid --}}


    {{-- MODALS --}}

    {{-- College Add/Edit Modal --}}
    <div x-data="{ show: $wire.entangle('showCollegeModal').live }" {{-- Use .live modifier --}}
         x-show="show" x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-college" role="dialog" aria-modal="true"
         style="display: none;"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-80" @click="show = false"></div>
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div @click.stop
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-show="show"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <form wire:submit.prevent="saveCollege">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                         <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title-college">
                            {{ $editingCollegeId ? 'Edit College' : 'Add New College' }}
                         </h3>
                         <div class="space-y-4">
                             <div>
                                 <label for="modalCollegeName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">College Name *</label>
                                 <input type="text" id="modalCollegeName" wire:model.lazy="collegeName" required autofocus
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                 @error('collegeName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                             <div>
                                  <label for="modalCollegeAbbreviation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Abbreviation</label>
                                  <input type="text" id="modalCollegeAbbreviation" wire:model.lazy="collegeAbbreviation"
                                         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                  @error('collegeAbbreviation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                             </div>
                         </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                         <button type="submit" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            Save College
                        </button>
                        <button type="button" wire:click="closeCollegeModal" @click="show = false" wire:loading.attr="disabled"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                    </div>
                </form>
             </div>
        </div>
    </div>

     {{-- Course Add/Edit Modal --}}
     <div x-data="{ show: $wire.entangle('showCourseModal').live }" {{-- Use .live modifier --}}
          x-show="show" x-on:keydown.escape.window="show = false"
          class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-course" role="dialog" aria-modal="true"
          style="display: none;"
          x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
          x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-80" @click="show = false"></div>
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div @click.stop
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-show="show"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
               <form wire:submit.prevent="saveCourse">
                   <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                       <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title-course">
                            {{-- Use selectedCollegeObject for display if available --}}
                           {{ $editingCourseId ? 'Edit Course' : 'Add New Course' }} {{ $selectedCollegeObject ? 'for ' . $selectedCollegeObject->name : '' }}
                       </h3>
                       <div class="space-y-4 mt-4">
                           {{-- Course Name --}}
                           <div>
                               <label for="modalCourseName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course Name *</label>
                               <input type="text" id="modalCourseName" wire:model.lazy="courseName" required autofocus
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                               @error('courseName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                           </div>
                           {{-- Course Abbreviation --}}
                            <div>
                                <label for="modalCourseAbbreviation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Abbreviation</label>
                                <input type="text" id="modalCourseAbbreviation" wire:model.lazy="courseAbbreviation"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                @error('courseAbbreviation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                       </div>
                   </div>
                   <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                           Save Course
                       </button>
                       <button type="button" wire:click="closeCourseModal" @click="show = false" wire:loading.attr="disabled"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                           Cancel
                       </button>
                   </div>
               </form>
           </div>
        </div>
     </div>

      {{-- Major Add/Edit Modal --}}
      <div x-data="{ show: $wire.entangle('showMajorModal').live }" {{-- Use .live modifier --}}
           x-show="show" x-on:keydown.escape.window="show = false"
           class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-major" role="dialog" aria-modal="true"
           style="display: none;"
           x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
           x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
         <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-80" @click="show = false"></div>
         <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div @click.stop
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-show="show"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
               <form wire:submit.prevent="saveMajor">
                   <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title-major">
                             {{-- Use selectedCourseObject for display if available --}}
                            {{ $editingMajorId ? 'Edit Major' : 'Add New Major' }} {{ $selectedCourseObject ? 'for ' . $selectedCourseObject->name : '' }}
                        </h3>
                       <div class="space-y-4 mt-4">
                           {{-- Major Name --}}
                           <div>
                               <label for="modalMajorName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Major Name *</label>
                               <input type="text" id="modalMajorName" wire:model.lazy="majorName" required autofocus
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                               @error('majorName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                           </div>
                       </div>
                   </div>
                   <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            Save Major
                        </button>
                        <button type="button" wire:click="closeMajorModal" @click="show = false" wire:loading.attr="disabled"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                   </div>
               </form>
           </div>
        </div>
      </div>

</div>