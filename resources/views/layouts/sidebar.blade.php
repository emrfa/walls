<div :class="sidebarOpen ? 'w-64' : 'w-20'" class="flex flex-col bg-white border-r border-gray-100 h-full transition-all duration-300 ease-in-out">
    <!-- Logo Area -->
    <div class="flex items-center justify-between h-16 border-b border-gray-100 px-4">
        <div x-show="sidebarOpen" class="transition-opacity duration-300">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col flex-1 overflow-y-auto py-4">
        <nav class="flex-1 px-2 space-y-1">


            <!-- Log HMI Command Menu -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 focus:outline-none">
                    <!-- Icon -->
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="flex-1 text-left transition-opacity duration-300 whitespace-nowrap">Log HMI Command</span>
                    <!-- Chevron -->
                    <svg x-show="sidebarOpen" :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-150 text-gray-400 group-hover:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <!-- Sub-menu -->
                <div x-show="open && sidebarOpen" class="space-y-1 pl-11">

                    <a href="{{ route('hmi-command.timeline') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('hmi-command.timeline') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        HMI Command
                    </a>
                    <a href="{{ route('device-module.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('device-module.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        Device Module
                    </a>
                    <a href="{{ route('command-master.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('command-master.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        Command
                    </a>
                    <a href="{{ route('command-category.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('command-category.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        Command Category
                    </a>
                    <a href="{{ route('users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        User
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- User Profile Section -->
    @php
        $currentUser = app(\App\Services\ApiService::class)->getCurrentUser();
    @endphp
    <div class="border-t border-gray-100 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    {{ substr($currentUser->name, 0, 1) }}
                </div>
            </div>
            <div x-show="sidebarOpen" class="ml-3 transition-opacity duration-300">
                <p class="text-sm font-medium text-gray-700">{{ $currentUser->name }}</p>
                <p class="text-xs text-gray-500">NIP: {{ $currentUser->nip }}</p>
            </div>
            <a href="{{ route('profile') }}" x-show="sidebarOpen" class="ml-auto text-gray-400 hover:text-gray-600 transition-opacity duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
        </div>
    </div>
</div>
