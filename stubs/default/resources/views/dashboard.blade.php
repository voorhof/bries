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

                @if(Route::has('cms.dashboard') && Auth::user()->can('access cms'))
                    <hr>

                    <p>
                        <a href="{{ route('cms.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-speedometer2 me-1"></i> {{ __("You have access to the CMS!") }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
