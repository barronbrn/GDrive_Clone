
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#00529B] leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>



    <div class="py-6" style="background-color: #F3F4F6;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            @include('partials.breadcrumbs')
        </div>
        <div class="max-w-7xl space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" >
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

