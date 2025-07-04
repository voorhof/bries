<section>
    <header>
        <h2 class="h3">
            {{ __('Delete Account') }}
        </h2>

        <p>
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-button class="btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Delete Account') }}
    </x-button>

    <x-modal id="confirmUserDeletionModal" :show="$errors->userDeletion->isNotEmpty()">
        <h3 class="h5">
            {{ __('Are you sure you want to delete your account?') }}
        </h3>

        <p class="small">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}" class="needs-validation" novalidate>
            @csrf
            @method('delete')

            {{--
            Hidden username (email field)
            see: https://www.chromium.org/developers/design-documents/create-amazing-password-forms/#use-hidden-fields-for-implicit-information
            --}}
            <input hidden type="text" name="username" autocomplete="username" value="{{ $user->email }}" aria-label="username">

            {{-- Password --}}
            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-input-text type="password" id="password" name="password" autocomplete="current-password"
                              :isInvalid="$errors->userDeletion->has('password')"
                              required placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" :defaultMessage="__('The password field is required.')" />
            </div>

            {{-- Submit --}}
            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
                <x-button type="button" class="btn-dark" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </x-button>

                <x-button class="btn-outline-danger">
                    {{ __('Delete Account') }}
                </x-button>
            </div>
        </form>
    </x-modal>
</section>
