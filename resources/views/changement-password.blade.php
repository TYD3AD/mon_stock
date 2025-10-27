<x-guest-layout>
    <!-- Statut de la session (succès, erreur, etc.) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PUT')

        <!-- Mot de passe actuel -->
        <div>
            <x-input-label for="current_password" :value="__('Mot de passe actuel')" />
            <x-text-input id="current_password"
                          class="block mt-1 w-full"
                          type="password"
                          name="current_password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <!-- Nouveau mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Nouveau mot de passe')" />
            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmation du mot de passe -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le nouveau mot de passe')" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Bouton de soumission -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Mettre à jour le mot de passe') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
