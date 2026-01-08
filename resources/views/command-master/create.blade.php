<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Command') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        form: {
            device_module: '',
            code: '',
            command: '',
            set_reset: 'Set',
            opposite: '',
            category: ''
        },
        isLoading: false,
        submit() {
            this.isLoading = true;
            fetch('/api/commands', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(this.form)
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '{{ route('command-master.index') }}';
                } else {
                    alert('Failed to create command.');
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Device Module -->
                            <div>
                                <label for="device_module" class="block text-sm font-medium text-gray-700">Device Module</label>
                                <input type="text" id="device_module" x-model="form.device_module" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                                <input type="text" id="code" x-model="form.code" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Command -->
                            <div class="col-span-2">
                                <label for="command" class="block text-sm font-medium text-gray-700">Command</label>
                                <input type="text" id="command" x-model="form.command" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Set/Reset -->
                            <div>
                                <label for="set_reset" class="block text-sm font-medium text-gray-700">Set/Reset</label>
                                <select id="set_reset" x-model="form.set_reset" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option>Set</option>
                                    <option>Reset</option>
                                </select>
                            </div>

                            <!-- Opposite -->
                            <div>
                                <label for="opposite" class="block text-sm font-medium text-gray-700">Opposite Code</label>
                                <input type="text" id="opposite" x-model="form.opposite" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <input type="text" id="category" x-model="form.category" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('command-master.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
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
