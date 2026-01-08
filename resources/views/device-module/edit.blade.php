<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Device Module') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        id: {{ $id }},
        form: {
            tag_code: '',
            device_module: '',
            description: ''
        },
        isLoading: false,
        isFetching: true,
        errors: {},

        init() {
            this.fetchData();
        },

        fetchData() {
            fetch(`/api/device-modules/${this.id}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        this.form = {
                            tag_code: data.tag_code,
                            device_module: data.device_module,
                            description: data.description
                        };
                    } else {
                        alert('Module not found');
                        window.location.href = '{{ route('device-module.index') }}';
                    }
                    this.isFetching = false;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    this.isFetching = false;
                });
        },

        submit() {
            this.isLoading = true;
            this.errors = {};

            fetch(`/api/device-modules/${this.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(this.form)
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '{{ route('device-module.index') }}';
                } else {
                    return response.json().then(data => {
                        throw data;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.isLoading = false;
                alert('Failed to update module. Please try again.');
            });
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div x-show="isFetching" class="flex justify-center py-4">
                        <svg class="animate-spin h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <form x-show="!isFetching" @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Tag Code -->
                            <div>
                                <label for="tag_code" class="block text-sm font-medium text-gray-700">Tag Code</label>
                                <input type="text" id="tag_code" x-model="form.tag_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                            <!-- Device Module -->
                            <div>
                                <label for="device_module" class="block text-sm font-medium text-gray-700">Device Module</label>
                                <input type="text" id="device_module" x-model="form.device_module" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" x-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <a href="{{ route('device-module.index') }}" class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                                    Cancel
                                </a>
                                <button type="submit" :disabled="isLoading" class="bg-red-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                                    <span x-show="!isLoading">Update</span>
                                    <span x-show="isLoading">Updating...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
