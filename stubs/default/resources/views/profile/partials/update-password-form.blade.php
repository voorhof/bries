<section>
    <header>
        <h2 class="h3">
            {{ __('Update Password') }}
        </h2>

        <p>
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
        @csrf
        @method('put')

        {{--
        Hidden username (email field)
        see: https://www.chromium.org/developers/design-documents/create-amazing-password-forms/#use-hidden-fields-for-implicit-information
        --}}
        <input hidden type="text" name="username" autocomplete="username" value="{{ $user->email }}" aria-label="username">

        {{-- Password --}}
        <div class="mb-3">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-input-text type="password" id="update_password_current_password" name="current_password" autocomplete="current-password"
                          :isInvalid="$errors->updatePassword->has('current_password')"
                          required />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" :defaultMessage="__('The password field is required.')" />
        </div>

        {{-- New password --}}
        <div class="mb-3">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-input-text type="password" id="update_password_password" name="password" autocomplete="new-password"
                          :isInvalid="$errors->updatePassword->has('password')"
                          required />
            <x-input-error :messages="$errors->updatePassword->get('password')" :defaultMessage="__('The password field is required.')" />
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-input-text type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password"
                          :isInvalid="$errors->has('password_confirmation')"
                          required />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" :defaultMessage="__('The password confirmation field is required.')" />
        </div>

        {{-- Submit --}}
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
            <x-button class="btn-primary">
                {{ __('Save') }}
            </x-button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-success me-auto mb-0">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
