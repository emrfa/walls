<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" x-data="{
                name: '{{ $user->name }}',
                nip: '{{ $user->nip }}',
                message: '',
                updateProfile() {
                    if (!this.name) return;
                    // Mock update
                    this.message = 'Profile updated successfully.';
                    setTimeout(() => this.message = '', 2000);
                }
            }">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Profile Information
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Update your account's profile information.
                            </p>
                        </header>
    
                        <form @submit.prevent="updateProfile" class="mt-6 space-y-6">
                            <div>
                                <x-input-label for="nip" :value="__('NIP')" />
                                <x-text-input id="nip" type="text" class="mt-1 block w-full bg-gray-100" x-model="nip" disabled />
                            </div>
    
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" type="text" class="mt-1 block w-full" x-model="name" required autofocus autocomplete="name" />
                            </div>
    
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
    
                                <p x-show="message" x-transition class="text-sm text-gray-600" x-text="message"></p>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
    
            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" x-data="{
                current_password: '',
                new_password: '',
                new_password_confirmation: '',
                message: '',
                updatePassword() {
                    if (!this.current_password || !this.new_password || this.new_password !== this.new_password_confirmation) {
                        return;
                    }
                    // Mock update
                    this.message = 'Password updated successfully.';
                    this.current_password = '';
                    this.new_password = '';
                    this.new_password_confirmation = '';
                    setTimeout(() => this.message = '', 2000);
                }
            }">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Update Password
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Ensure your account is using a long, random password to stay secure.
                            </p>
                        </header>
    
                        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
                            <div>
                                <x-input-label for="current_password" :value="__('Current Password')" />
                                <x-text-input id="current_password" type="password" class="mt-1 block w-full" x-model="current_password" autocomplete="current-password" required />
                            </div>
    
                            <div>
                                <x-input-label for="new_password" :value="__('New Password')" />
                                <x-text-input id="new_password" type="password" class="mt-1 block w-full" x-model="new_password" autocomplete="new-password" required />
                            </div>
    
                            <div>
                                <x-input-label for="new_password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="new_password_confirmation" type="password" class="mt-1 block w-full" x-model="new_password_confirmation" autocomplete="new-password" required />
                            </div>
    
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
    
                                <p x-show="message" x-transition class="text-sm text-gray-600" x-text="message"></p>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
