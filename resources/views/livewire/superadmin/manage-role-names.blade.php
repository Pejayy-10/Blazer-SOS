<div>
    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Manage Role Names</h2>

    {{-- Add New Role Form --}}
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 shadow rounded-lg">
        <form wire:submit.prevent="addRoleName" class="flex items-end space-x-3">
            <div class="flex-grow">
                <label for="newRoleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Role Name</label>
                <input type="text" id="newRoleName" wire:model.lazy="newRoleName" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                       placeholder="e.g., Photojournalist">
                @error('newRoleName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-[#9A382F] py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-[#5F0104] focus:outline-none focus:ring-2 focus:ring-[#9A382F] focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                Add Role
            </button>
        </form>
    </div>

    {{-- Roles Table --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role Name</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($roles as $role)
                        <tr wire:key="role-{{ $role->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{-- Show input field if editing this role --}}
                                @if($editingRole && $editingRole->id === $role->id)
                                     <input type="text" wire:model.lazy="editingRoleNameValue"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white sm:text-sm
                                                   @error('editingRoleNameValue') border-red-500 @enderror">
                                     @error('editingRoleNameValue') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                @else
                                    {{ $role->name }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                 @if($editingRole && $editingRole->id === $role->id)
                                     {{-- Save and Cancel buttons during edit --}}
                                     <button wire:click="updateRoleName" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Save Changes">
                                        <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                     </button>
                                     <button wire:click="cancelEditing" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" title="Cancel Edit">
                                         <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                     </button>
                                 @else
                                     {{-- Edit and Delete buttons --}}
                                     <button wire:click="startEditing({{ $role->id }})" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit Role Name">
                                        <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                     </button>
                                     <button wire:click="deleteRoleName({{ $role->id }})" wire:confirm="Are you sure you want to delete the role '{{ $role->name }}'?"
                                             class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Delete Role Name">
                                         <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                     </button>
                                 @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No role names defined yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        @if ($roles->hasPages())
            <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
</div>