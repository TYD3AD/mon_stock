<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Mot de passe oublié ?') }} </br>
        {{ __('Saisissez votre adresse mail, et nous vous enverrons un mail afin de réinitialiser votre mot de passe') }}
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Réinitialiser mon mot de passe') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
