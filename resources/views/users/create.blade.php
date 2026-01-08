<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        form: {
            nip: '',
            name: '',
            password: '',
            password_confirmation: '',
            permissions: []
        },
        isLoading: false,
        submit() {
            if (this.form.password !== this.form.password_confirmation) {
                alert('Passwords do not match');
                return;
            }
            this.isLoading = true;
            fetch('/api/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(this.form)
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '{{ route('users.index') }}';
                } else {
                    alert('Failed to create user.');
                    this.isLoading = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.isLoading = false;
            });
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- NIP -->
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                                <input type="text" id="nip" x-model="form.nip" required class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" x-model="form.name" required class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" id="password" x-model="form.password" required class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" id="password_confirmation" x-model="form.password_confirmation" required class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Access Permissions -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Access</h3>
                            
                            <div class="space-y-4">
                                <!-- Add Reason -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">1. Add Reason</h4>
                                    <div class="flex items-center">
                                        <input type="checkbox" value="add_reason" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-900">Add Reason</label>
                                    </div>
                                </div>

                                <!-- Device Module -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">2. Device Module</h4>
                                    <div class="flex space-x-6">
                                        <div class="flex items-center">
                                            <input type="checkbox" value="add_device_module" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Add Device Module</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="delete_device_module" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Delete Device Module</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="edit_device_module" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Edit Device Module</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Command -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">3. Command</h4>
                                    <div class="flex space-x-6">
                                        <div class="flex items-center">
                                            <input type="checkbox" value="add_command" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Add Command</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="delete_command" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Delete Command</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="edit_command" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Edit Command</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Command Category -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">4. Command Category</h4>
                                    <div class="flex space-x-6">
                                        <div class="flex items-center">
                                            <input type="checkbox" value="add_category" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Add Category</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="delete_category" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Delete Category</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="edit_category" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Edit Category</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- User -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">5. User</h4>
                                    <div class="flex space-x-6">
                                        <div class="flex items-center">
                                            <input type="checkbox" value="add_user" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Add User</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="delete_user" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Delete User</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" value="edit_user" x-model="form.permissions" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-900">Edit User</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit" :disabled="isLoading" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <span x-show="!isLoading">Create</span>
                                <span x-show="isLoading">Creating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
