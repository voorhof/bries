<x-auth-layout>
    <p class="small">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="needs-validation" novalidate>
        @csrf

        {{-- Password --}}
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-input-text type="password" id="password" name="password" autocomplete="current-password"
                          :isInvalid="$errors->has('password')"
                          required />
            <x-input-error :messages="$errors->get('password')" :defaultMessage="__('The password field is required.')" />
        </div>

        {{-- Submit --}}
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
            <x-button class="btn-primary">
                {{ __('Confirm') }}
            </x-button>
        </div>
    </form>
</x-auth-layout>
