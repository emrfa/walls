<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HMI Command') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        commands: [],
        sortField: 'set_time',
        sortDirection: 'desc',
        filters: {
            tag_id: '',
            area: '',
            command: '',
            set_time: '',
            reset_time: '',
            user: '',
            node: ''
        },
        page: 1,
        perPage: 10,
        total: 0,
        lastPage: 1,
        isLoading: false,
        showModal: false,
        selectedCommandId: null,
        newReason: '',
        newActions: [''],
        newUsedParts: [''],
        newStatus: 'Open',
        stats: { open: 1, pending: 2, close: 3 },

        init() {
            this.fetchData();
            this.fetchStats();
            
            this.$watch('filters', () => {
                this.page = 1;
                this.fetchData();
                this.fetchStats();
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

            fetch(`/api/timeline?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    this.commands = data.data;
                    this.total = data.meta.total;
                    this.lastPage = data.meta.last_page;
                    this.isLoading = false;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    this.isLoading = false;
                });
        },

        fetchStats() {
            const params = new URLSearchParams();
            Object.entries(this.filters).forEach(([key, value]) => {
                if (value) params.append(`filter[${key}]`, value);
            });

            fetch(`/api/timeline/stats?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    this.stats = data;
                })
                .catch(error => {
                    console.error('Error fetching stats:', error);
                    // Fallback for demo
                    // this.stats = { open: 0, pending: 0, close: 0 };
                });
        },

        openReasonModal(commandId) {
            this.selectedCommandId = commandId;
            this.newReason = '';
            this.newActions = [''];
            this.newUsedParts = [''];
            this.newStatus = 'Open';
            this.showModal = true;
        },
        addAction() {
            this.newActions.push('');
        },
        removeAction(index) {
            this.newActions.splice(index, 1);
        },
        addUsedPart() {
            this.newUsedParts.push('');
        },
        removeUsedPart(index) {
            this.newUsedParts.splice(index, 1);
        },
        saveReason() {
            if (!this.newReason) return;
            
            const commandIndex = this.commands.findIndex(c => c.id === this.selectedCommandId);
            if (commandIndex > -1) {
                const now = new Date();
                const dateStr = now.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }).toUpperCase();
                const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }).toLowerCase();
                
                // Pastikan tidak ada string kosong
                const cleanActions = this.newActions.filter(a => a.trim() !== '');
                const cleanUsedParts = this.newUsedParts.filter(p => p.trim() !== '');

                this.commands[commandIndex].reasons.unshift({
                    date: dateStr,
                    time: timeStr,
                    user_name: 'Current User', // Placeholder
                    user_role: '',
                    message: this.newReason,
                    actions: cleanActions,
                    used_parts: cleanUsedParts,
                    status: this.newStatus
                });
                this.commands[commandIndex].status = this.newStatus;
            }
            this.showModal = false;
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
        }
    }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Open Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Open Events</h3>
                                <p class="text-2xl font-semibold text-red-600" x-text="stats.open || 0"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Pending Events</h3>
                                <p class="text-2xl font-semibold text-yellow-600" x-text="stats.pending || 0"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Closed Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Closed Events</h3>
                                <p class="text-2xl font-semibold text-green-600" x-text="stats.close || 0"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <div class="relative">
                        <!-- Loading Overlay -->
                        <div x-show="isLoading" class="absolute inset-0 bg-white bg-opacity-50 flex items-center justify-center z-10">
                            <svg class="animate-spin h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 table-fixed">
                            <thead class="bg-red-600">
                                <tr>
                                    <th scope="col" class="w-10 px-6 py-3 border-r border-red-700"></th> <!-- Expand/Collapse Icon -->
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('tag_id')">
                                        <div class="flex items-center justify-between">
                                            Tag ID
                                            <template x-if="sortField === 'tag_id'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('area')">
                                        <div class="flex items-center justify-between">
                                            Area
                                            <template x-if="sortField === 'area'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('command')">
                                        <div class="flex items-center justify-between">
                                            Command
                                            <template x-if="sortField === 'command'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                     <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('airpressure')">
                                        <div class="flex items-center justify-between">
                                            Air Pressure
                                            <template x-if="sortField === 'airpressure'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('set_time')">
                                        <div class="flex items-center justify-between">
                                            Set Time
                                            <template x-if="sortField === 'set_time'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('reset_time')">
                                        <div class="flex items-center justify-between">
                                            Reset Time
                                            <template x-if="sortField === 'reset_time'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('user')">
                                        <div class="flex items-center justify-between">
                                            User
                                            <template x-if="sortField === 'user'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-red-700 cursor-pointer hover:bg-red-700" @click="sortBy('node')">
                                        <div class="flex items-center justify-between">
                                            Node
                                            <template x-if="sortField === 'node'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" /></svg>
                                            </template>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Reason</th>
                                </tr>
                                <!-- Filter Row -->
                                <tr>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200"></th>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.tag_id" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.area" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.command" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.airpressure" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="datetime-local" x-model.debounce.500ms="filters.set_time" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="datetime-local" x-model.debounce.500ms="filters.reset_time" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>

                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.user" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-r border-gray-200">
                                        <input type="text" x-model.debounce.500ms="filters.node" class="block w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search...">
                                    </th>
                                    <th class="px-6 py-2 bg-white border-b border-gray-200"></th> <!-- Reason -->
                                </tr>
                            </thead>
                            <template x-for="(command, index) in commands" :key="command.id">
                                <tbody x-data="{ expanded: false }" class="border-b border-gray-200 divide-y divide-gray-200" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-100'">
                                    <tr class="hover:bg-gray-200 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-500 cursor-pointer" @click="expanded = !expanded">
                                            <svg x-show="!expanded" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            <svg x-show="expanded" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium" x-text="command.tag_id || '-'"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="command.area"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="command.command"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="command.airpressure"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="command.set_time"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="command.reset_time"></td>

                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'text-red-600 bg-red-100': (command.status || 'Open') === 'Open',
                                                    'text-yellow-600 bg-yellow-100': command.status === 'Pending',
                                                    'text-green-600 bg-green-100': command.status === 'Close',
                                                    'text-gray-600 bg-gray-100': !['Open', 'Pending', 'Close'].includes(command.status)
                                                }"
                                                x-text="command.status || 'Open'">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="command.user"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900" x-text="command.node"></td>
                                        <td class="px-6 py-4 text-sm text-gray-500" x-text="command.reasons && command.reasons.length ? command.reasons.map(r => r.message).join(', ') : '-'"></td>
                                    </tr>
                                    <tr x-show="expanded" class="bg-gray-50" style="display: none;">
                                        <td colspan="10" class="px-6 py-4">
                                            <div class="p-4 bg-white rounded-lg shadow-inner">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h4 class="text-lg font-semibold text-gray-700">History</h4>
                                                    <button type="button" @click="openReasonModal(command.id)" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Add Action
                                                    </button>
                                                </div>

                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action By</th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used Parts</th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        <template x-for="reason in command.reasons">
                                                            <tr>
                                                                <td class="px-6 py-4 text-sm text-gray-900" x-text="reason.message"></td>
                                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                                    <template x-if="reason.actions && reason.actions.length > 0">
                                                                        <ul class="list-disc list-inside">
                                                                            <template x-for="action in reason.actions">
                                                                                <li x-text="action"></li>
                                                                            </template>
                                                                        </ul>
                                                                    </template>
                                                                    <template x-if="!reason.actions || reason.actions.length === 0">
                                                                        <span>-</span>
                                                                    </template>
                                                                </td>
                                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                                    <div x-text="reason.date"></div>
                                                                    <div class="text-xs text-gray-500" x-text="reason.time"></div>
                                                                </td>
                                                                <td class="px-6 py-4 text-sm text-gray-900" x-text="reason.user_name"></td>
                                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                                    <template x-if="reason.used_parts && reason.used_parts.length > 0">
                                                                        <ul class="list-disc list-inside">
                                                                            <template x-for="part in reason.used_parts">
                                                                                <li x-text="part"></li>
                                                                            </template>
                                                                        </ul>
                                                                    </template>
                                                                    <template x-if="!reason.used_parts || reason.used_parts.length === 0">
                                                                        <span>-</span>
                                                                    </template>
                                                                </td>
                                                                <td class="px-6 py-4 text-sm">
                                                                    <template x-if="reason.status">
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                                            :class="{
                                                                                'text-red-600 bg-red-100': reason.status === 'Open',
                                                                                'text-yellow-600 bg-yellow-100': reason.status === 'Pending',
                                                                                'text-green-600 bg-green-100': reason.status === 'Close',
                                                                                'text-gray-600 bg-gray-100': !['Open', 'Pending', 'Close'].includes(reason.status)
                                                                            }"
                                                                            x-text="reason.status">
                                                                        </span>
                                                                    </template>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <tr x-show="!command.reasons || command.reasons.length === 0">
                                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 italic">No history available.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                            <tr x-show="commands.length === 0">
                                <td colspan="10" class="px-6 py-4 text-center text-gray-500">No data found</td>
                            </tr>
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

        <!-- Add Reason Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Add Action
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Please enter the reason for this command.
                                    </p>
                                    
                                    <div class="mt-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                        <select id="status" x-model="newStatus" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="Open">Open</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Close">Close</option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                        <textarea id="reason" x-model="newReason" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter reason here..."></textarea>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Actions</label>
                                        <template x-for="(action, index) in newActions" :key="index">
                                            <div class="flex gap-2 mb-2">
                                                <input type="text" x-model="newActions[index]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter action taken...">
                                                <button type="button" @click="removeAction(index)" class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" x-show="newActions.length > 1">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <button type="button" @click="addAction()" class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" x-show="index === newActions.length - 1">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Used Parts</label>
                                        <template x-for="(part, index) in newUsedParts" :key="index">
                                            <div class="flex gap-2 mb-2">
                                                <input type="text" x-model="newUsedParts[index]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter parts used...">
                                                <button type="button" @click="removeUsedPart(index)" class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" x-show="newUsedParts.length > 1">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <button type="button" @click="addUsedPart()" class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" x-show="index === newUsedParts.length - 1">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="saveReason" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
