<x-app-layout>
    <x-slot name="header">
        Profil Ayarları
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="glass-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="glass-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-weather-settings-form')
                </div>
            </div>

            <div class="glass-card border-red-500/10">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
