<nav class="navbar navbar-expand-lg bg-primary shadow-sm" data-bs-theme="dark">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <x-application-logo style="fill: currentColor; width: 30px;" class="d-inline-block align-top" />
            {{ config('app.name', 'Laravel') }}
        </a>

        {{-- Hamburger --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Collapse navigation --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            {{-- Navigation links --}}
            <div class="navbar-nav flex-grow-1">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>

                @if(Route::has('posts'))
                    <x-nav-link :href="route('posts')" :active="request()->routeIs('posts')">
                        {{ __('Posts') }}
                    </x-nav-link>
                @endif

                @if(Route::has('cheatsheet'))
                    <x-nav-link :href="route('cheatsheet')" :active="request()->routeIs('cheatsheet')">
                        {{ __('Cheatsheet') }}
                    </x-nav-link>
                @endif

                <x-nav-link href="#">
                    {{ __('Example') }}
                </x-nav-link>

                <x-nav-link :disabled="true">
                    {{ __('Disabled example') }}
                </x-nav-link>

                {{-- Dropdown profile --}}
                <x-dropdown align="right" class="nav-item ms-lg-auto" togglerClasses="nav-link">
                    <x-slot:trigger>
                        {{ Auth::user()->name }}
                    </x-slot:trigger>

                    <x-slot:content>
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="bi bi-person-circle me-1"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link>
                            <i class="bi bi-box me-1"></i> Another action
                        </x-dropdown-link>

                        <hr class="dropdown-divider">

                        @if(Route::has(config('cms.route_name_prefix').'.dashboard') && Auth::user()->can('access cms'))
                            <x-dropdown-link :href="route(config('cms.route_name_prefix').'.dashboard')" class="link-info">
                                <i class="bi bi-speedometer2 me-1"></i> {{ __('CMS') }}
                            </x-dropdown-link>

                            <hr class="dropdown-divider">
                        @endif

                        {{-- Log out form --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-button class="dropdown-item btn-link">
                                <i class="bi bi-power me-1"></i> {{ __('Log Out') }}
                            </x-button>
                        </form>
                    </x-slot:content>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
