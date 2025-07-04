<x-app-layout>
    <x-slot:header>
        <h1 class="mb-0">
            <i class="bi bi-person-circle text-primary me-1"></i>
            {{ __('Profile') }}
        </h1>
    </x-slot:header>

    <div class="container py-3">
        <div class="row mb-5">
            <div class="col-lg-8 col-xl-6">
                @include('profile.partials.update-profile-information-form')

                <hr class="my-5">

                @include('profile.partials.update-password-form')

                <hr class="my-5">

                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
