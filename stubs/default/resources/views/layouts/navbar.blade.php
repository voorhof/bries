<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-lg">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <x-application-logo style="fill: var(--dc-primary); width: 30px;" class="d-inline-block align-top" />
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

                <x-nav-link :href="route('cheatsheet')" :active="request()->routeIs('cheatsheet')">
                    {{ __('Cheatsheet') }}
                </x-nav-link>

                <x-nav-link href="#">
                    {{ __('Link') }}
                </x-nav-link>

                <x-nav-link :disabled="true">
                    {{ __('Disabled') }}
                </x-nav-link>

                {{-- Dropdown profile --}}
                <x-dropdown align="right" class="nav-item ms-auto" togglerClasses="nav-link">
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
