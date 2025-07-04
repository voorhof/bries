<x-auth-layout>
    {{-- Session Status --}}
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
        @csrf

        {{-- Email Address --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-input-text type="email" id="email" name="email" autocomplete="username"
                          :value="old('email')" :isInvalid="$errors->has('email')"
                          required autofocus />
            <x-input-error :messages="$errors->get('email')" :defaultMessage="__('The email field is required.')" />
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-input-text type="password" id="password" name="password" autocomplete="current-password"
                          :isInvalid="$errors->has('password')"
                          required />
            <x-input-error :messages="$errors->get('password')" :defaultMessage="__('The password field is required.')" />
        </div>

        {{-- Remember Me --}}
        <div class="form-check mb-3">
            <input type="checkbox" id="remember" name="remember" class="form-check-input">
            <label class="form-check-label" for="remember">
                {{ __('Remember me') }}
            </label>
        </div>

        {{-- Submit --}}
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
            <x-button class="btn-primary">
                {{ __('Log in') }}
            </x-button>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>
</x-auth-layout>
