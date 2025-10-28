<x-app-layout>

        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-semibold mb-6">Créer un nouvel utilisateur</h1>
            <div class="ml-56 mr-4">
                @if(session('error'))
                    <div class="bg-red-500 text-white p-4 rounded-lg" name="messageError">{!! session('error') !!}</div>
                @elseif(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg" name="messageSuccess">{!! session('success') !!}</div>
                @endif

                <script>
                    setTimeout(function () {
                        const errorMessage = document.querySelector('[name="messageError"]');
                        const successMessage = document.querySelector('[name="messageSuccess"]');

                        if (errorMessage) errorMessage.style.display = 'none';
                        if (successMessage) successMessage.style.display = 'none';
                    }, 3000);
                </script>
            </div>
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block font-medium">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}"
                           class="border rounded w-full p-2" required>
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block font-medium">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                           class="border rounded w-full p-2" required>
                </div>

                <!-- Identifiant -->
                <div>
                    <label for="identifiant" class="block font-medium">Identifiant</label>
                    <input type="text" id="identifiant" name="identifiant" value="{{ old('identifiant') }}"
                           class="border rounded w-full p-2" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="border rounded w-full p-2" required>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block font-medium">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           class="border rounded w-full p-2" required>
                </div>

                <!-- Confirmation du mot de passe -->
                <div>
                    <label for="password_confirmation" class="block font-medium">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="border rounded w-full p-2" required>
                </div>

                <!-- antenne -->
                <div>
                    <label for="antenne" class="block font-medium">Antenne</label>
                    <select id="antenne" name="antenne" class="border rounded w-full p-2">
                        @foreach($antennes as $antenne)
                            <option value="{{ $antenne->id }}"
                                {{ collect(old('antenne'))->contains($antenne->id) ? 'selected' : '' }}>
                                {{ $antenne->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="responsable" class="block font-medium">Est responsable d'antenne ?</label>
                    <input type="radio" id="responsable_yes" name="responsable" value="1"
                           {{ old('responsable') == '1' ? 'checked' : '' }}>
                    <label for="responsable_yes"  class="mr-6">Oui</label>

                    <input type="radio" id="responsable_no" name="responsable" value="0"
                           {{ old('responsable', '0') == '0' ? 'checked' : '' }}>
                    <label for="responsable_no">Non</label>
                </div>

                <!-- Bouton -->
                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Créer l’utilisateur
                    </button>
                </div>
            </form>
        </div>
</x-app-layout>
