<div x-data="commandPalette" @keydown.window.prevent.ctrl.k="toggle()" @keydown.window.prevent.cmd.k="toggle()" @keydown.window.escape="isOpen = false" @keydown.window="onKeydown($event)" class="relative z-50" x-show="isOpen" style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity" x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
        <div class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all" @click.away="isOpen = false" x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Search Input -->
            <div class="relative">
                <svg class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
                <input type="text" x-model="search" x-ref="searchInput" class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-800 placeholder-gray-400 focus:ring-0 sm:text-sm" placeholder="Search commands..." role="combobox" aria-expanded="false" aria-controls="options">
            </div>

            <!-- Results -->
            <ul class="max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800" id="options" role="listbox">
                <template x-for="(action, index) in filteredActions" :key="index">
                    <li @click="selectAction(action)" @mouseenter="selectedIndex = index" :class="{'bg-indigo-600 text-white': selectedIndex === index, 'text-gray-900': selectedIndex !== index}" class="cursor-pointer select-none px-4 py-2" role="option" tabindex="-1">
                        <div class="flex items-center">
                            <!-- Icon -->
                            <svg x-show="action.type === 'page'" class="h-6 w-6 flex-none" :class="{'text-white': selectedIndex === index, 'text-gray-400': selectedIndex !== index}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <svg x-show="action.type === 'action'" class="h-6 w-6 flex-none" :class="{'text-white': selectedIndex === index, 'text-gray-400': selectedIndex !== index}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="ml-3 flex-auto truncate" x-text="action.title"></span>
                            <span x-show="action.shortcut" class="ml-3 flex-none text-xs font-semibold text-gray-400" :class="{'text-indigo-200': selectedIndex === index}" x-text="action.shortcut"></span>
                        </div>
                    </li>
                </template>
                
                <li x-show="filteredActions.length === 0" class="px-4 py-2 text-gray-500">No results found.</li>
            </ul>
            
            <!-- Footer -->
            <div class="flex flex-wrap items-center bg-gray-50 py-2.5 px-4 text-xs text-gray-700">
                Type <kbd class="mx-1 flex h-5 w-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2 border-gray-400 text-gray-900">↵</kbd> <span class="sm:hidden">to select</span><span class="hidden sm:inline">to select</span>
                <span class="mx-2 border-r border-gray-300 h-4"></span>
                Type <kbd class="mx-1 flex h-5 w-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2 border-gray-400 text-gray-900">esc</kbd> <span class="sm:hidden">to close</span><span class="hidden sm:inline">to close</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('commandPalette', () => ({
            isOpen: false,
            search: '',
            selectedIndex: 0,
            actions: [
                // Pages
                { title: 'Open HMI Command Timeline', url: '{{ route("hmi-command.timeline") }}', type: 'page' },
                { title: 'Open Device Modules', url: '{{ route("device-module.index") }}', type: 'page' },
                { title: 'Open Commands', url: '{{ route("command-master.index") }}', type: 'page' },
                { title: 'Open Command Categories', url: '{{ route("command-category.index") }}', type: 'page' },
                { title: 'Open Users', url: '{{ route("users.index") }}', type: 'page' },
                { title: 'Open Profile', url: '{{ route("profile") }}', type: 'page' },
                
                // Actions (Create)
                { title: 'Create New Device Module', url: '{{ route("device-module.create") }}', type: 'action' },
                { title: 'Create New Command', url: '{{ route("command-master.create") }}', type: 'action' },
                { title: 'Create New Category', url: '{{ route("command-category.create") }}', type: 'action' },
                { title: 'Create New User', url: '{{ route("users.create") }}', type: 'action' },
            ],
            init() {
                this.$watch('isOpen', value => {
                    if (value) {
                        this.$nextTick(() => this.$refs.searchInput.focus());
                        this.search = '';
                        this.selectedIndex = 0;
                    }
                });
                this.$watch('search', () => {
                    this.selectedIndex = 0;
                });
            },
            toggle() {
                this.isOpen = !this.isOpen;
            },
            get filteredActions() {
                if (this.search === '') {
                    return this.actions;
                }
                return this.actions.filter(action => {
                    return action.title.toLowerCase().includes(this.search.toLowerCase());
                });
            },
            selectAction(action) {
                window.location.href = action.url;
            },
            onKeydown(e) {
                if (!this.isOpen) return;

                // Arrow Down
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.selectedIndex = (this.selectedIndex + 1) % this.filteredActions.length;
                }
                // Arrow Up
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    this.selectedIndex = (this.selectedIndex - 1 + this.filteredActions.length) % this.filteredActions.length;
                }
                // Enter
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (this.filteredActions.length > 0) {
                        this.selectAction(this.filteredActions[this.selectedIndex]);
                    }
                }
            }
        }));
    });
</script>
