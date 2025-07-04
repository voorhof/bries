<x-auth-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    {{-- Session Status --}}
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success small">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between py-1">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-button class="btn-outline-primary">
                {{ __('Resend Verification Email') }}
            </x-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-button class="btn-outline-secondary">
                {{ __('Log Out') }}
            </x-button>
        </form>
    </div>
</x-auth-layout>
