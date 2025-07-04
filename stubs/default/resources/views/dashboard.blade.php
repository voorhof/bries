<x-app-layout>
    <x-slot:header>
        <h1 class="mb-0">
            <i class="bi bi-speedometer text-primary me-1"></i>
            {{ __('Dashboard') }}
        </h1>
    </x-slot:header>

    <div class="container py-3">
        <div class="row">
            <div class="col">
                <p>
                    {{ __("You're logged in!") }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
