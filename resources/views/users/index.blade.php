<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        users: [],
        sortField: 'nip',
        sortDirection: 'asc',
        filters: {
            nip: '',
            name: ''
        },
        page: 1,
        perPage: 10,
        total: 0,
        lastPage: 1,
        isLoading: false,
        showDeleteModal: false,
        userToDelete: null,

        init() {
            this.fetchData();
            
            this.$watch('filters', () => {
                this.page = 1;
                this.fetchData();
            });
        },

        fetchData() {
            this.isLoading = true;
            
            const params = new URLSearchParams({
                page: this.page,
                per_page: this.perPage,
                sort_field: this.sortField,
                sort_direction: this.sortDirection
            });

            Object.entries(this.filters).forEach(([key, value]) => {
                if (value) params.append(`filter[${key}]`, value);
            });

            fetch(`/api/users?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    this.users = data.data;
                    this.total = data.meta.total;
                    this.lastPage = data.meta.last_page;
                    this.isLoading = false;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    this.isLoading = false;
                });
        },

        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
            this.fetchData();
        },

        nextPage() {
            if (this.page < this.lastPage) {
                this.page++;
                this.fetchData();
            }
        },
        
        prevPage() {
            if (this.page > 1) {
                this.page--;
                this.fetchData();
            }
        },

        deleteUser(id) {
            this.userToDelete = id;
            this.showDeleteModal = true;
        },

        confirmDelete() {
            if (this.userToDelete) {
                this.isLoading = true;
                fetch(`/api/users/${this.userToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        this.fetchData();
                        this.showDeleteModal = false;
                        this.userToDelete = null;
                    } else {
                        alert('Failed to delete user.');
                        this.isLoading = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.isLoading = false;
                });
            }
        },

        cancelDelete() {
            this.showDeleteModal = false;
            this.userToDelete = null;
        }
    }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Add User
                        </a>
                    </div>
                    
                    <div class="relative">
                        <!-- Loading Overlay -->
                        <div x-show="isLoading" class="absolute inset-0 bg-white bg-opacity-50 flex items-center justify-center z-10">
                            <svg class="animate-spin h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('nip')">
                                        <div class="flex items-center justify-between">
                                            NIP
                                            <template x-if="sortField === 'nip'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('name')">
                                        <div class="flex items-center justify-between">
                                            User
                                            <template x-if="sortField === 'name'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700">Password</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Action</th>
                                </tr>
                                <!-- Filter Row -->
                                <tr>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.nip" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.name" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-gray-200"></th>
                                    <th class="px-6 py-2 bg-white border-b border-gray-200"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-for="(user, index) in users" :key="user.nip">
                                    <tr class="hover:bg-gray-200 transition-colors duration-150" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-100'">
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="user.nip"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="user.name"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-mono tracking-widest">••••••</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a :href="`/users/${user.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900 focus:outline-none">
                                                <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="users.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No data found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing page <span class="font-medium" x-text="page"></span> of <span class="font-medium" x-text="lastPage"></span>
                            (<span class="font-medium" x-text="total"></span> total)
                        </div>
                        <div class="flex space-x-2">
                            <button @click="prevPage" :disabled="page === 1" class="px-3 py-1 border rounded-md text-sm font-medium" :class="page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'">
                                Previous
                            </button>
                            <button @click="nextPage" :disabled="page === lastPage" class="px-3 py-1 border rounded-md text-sm font-medium" :class="page === lastPage ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete User
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete this user? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="confirmDelete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button" @click="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
