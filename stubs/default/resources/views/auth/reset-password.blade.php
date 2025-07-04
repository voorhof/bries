<x-auth-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Password Reset Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email Address --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-input-text type="email" id="email" name="email" autocomplete="username"
                          :value="old('email', $request->email)" :isInvalid="$errors->has('email')"
                          required autofocus />
            <x-input-error :messages="$errors->get('email')" :defaultMessage="__('The email field is required.')" />
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-input-text type="password" id="password" name="password" autocomplete="new-password"
                          :isInvalid="$errors->has('password')"
                          required />
            <x-input-error :messages="$errors->get('password')" :defaultMessage="__('The password field is required.')" />
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-input-text type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                          :isInvalid="$errors->has('password_confirmation')"
                          required />
            <x-input-error :messages="$errors->get('password_confirmation')" :defaultMessage="__('The password confirmation field is required.')" />
        </div>

        {{-- Submit --}}
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
            <x-button class="btn-primary">
                {{ __('Reset Password') }}
            </x-button>
        </div>
    </form>
</x-auth-layout>
