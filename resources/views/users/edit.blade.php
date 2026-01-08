<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        form: {
            nip: '',
            name: '',
            password: '',
            permissions: []
        },
        showPasswordModal: false,
        passwordForm: {
            new_password: '',
            confirm_password: ''
        },
        isLoading: false,
        init() {
            this.fetchData();
        },
        fetchData() {
            this.isLoading = true;
            fetch(`/api/users/{{ $id }}`)
                .then(response => response.json())
                .then(data => {
                    this.form = data;
                    // Ensure permissions is an array
                    if (!this.form.permissions) {
                        this.form.permissions = [];
                    }
                    this.isLoading = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.isLoading = false;
                });
        },
        submit() {
            this.isLoading = true;
            fetch(`/api/users/{{ $id }}`, {
                method: 'PUT',
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
                    alert('Failed to update user.');
                    this.isLoading = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.isLoading = false;
            });
        },
        changePassword() {
            if (this.passwordForm.new_password !== this.passwordForm.confirm_password) {
                alert('Passwords do not match');
                return;
            }
            // In a real app, we would send a separate request to update password
            // For this simulation, we'll just update the local form data and submit
            this.form.password = this.passwordForm.new_password;
            this.submit();
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
                            
                            <!-- Change Password Button -->
                            <div class="md:col-span-2">
                                <button type="button" @click="showPasswordModal = true" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Change Password
                                </button>
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
                                <span x-show="!isLoading">Update</span>
                                <span x-show="isLoading">Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div x-show="showPasswordModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Change Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" x-model="passwordForm.new_password" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" x-model="passwordForm.confirm_password" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="changePassword" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Change Password
                        </button>
                        <button type="button" @click="showPasswordModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
