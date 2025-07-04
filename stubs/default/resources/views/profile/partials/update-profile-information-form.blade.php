@php use Illuminate\Contracts\Auth\MustVerifyEmail; @endphp
<section>
    <header>
        <h2 class="h3">
            {{ __('Profile Information') }}
        </h2>

        <p>
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="needs-validation" novalidate>
        @csrf
        @method('patch')

        {{-- Name --}}
        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')"/>
            <x-input-text type="text" id="name" name="name" autocomplete="name"
                          :value="old('name', $user->name)" :isInvalid="$errors->has('name')"
                          required/>
            <x-input-error :messages="$errors->get('name')" :defaultMessage="__('The name field is required.')"/>
        </div>

        {{-- Email Address --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')"/>
            <x-input-text type="email" id="email" name="email" autocomplete="username"
                          :value="old('email', $user->email)" :isInvalid="$errors->has('email')"
                          required/>
            <x-input-error :messages="$errors->get('email')" :defaultMessage="__('The email field is required.')"/>

            @if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="small mb-2">
                        {{ __('Your email address is unverified.') }}
                    </p>

                    <x-button form="send-verification" class="btn-outline-secondary btn-sm">
                        {{ __('Click here to re-send the verification email.') }}
                    </x-button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="small mt-2 text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Submit --}}
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
            <x-button class="btn-primary">
                {{ __('Save') }}
            </x-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-success me-auto mb-0">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
