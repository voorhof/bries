<x-auth-layout>
    <p class="small">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    {{-- Session Status --}}
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
        @csrf

        {{-- Email Address --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-input-text type="email" id="email" name="email" autocomplete="username"
                          :value="old('email')" :isInvalid="$errors->has('email')"
                          required autofocus />
            <x-input-error :messages="$errors->get('email')" :defaultMessage="__('The email field is required.')" />
        </div>

        {{-- Submit --}}
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
            <x-button class="btn-primary">
                {{ __('Email Password Reset Link') }}
            </x-button>
        </div>
    </form>
</x-auth-layout>
