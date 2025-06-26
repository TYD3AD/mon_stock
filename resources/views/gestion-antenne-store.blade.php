<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des antennes') }}
        </h2>
    </x-slot>

    <div class="lg:ml-56 md:ml-56 p-6 space-y-10">
        @foreach($tableauAntennes as $data)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6"
                 x-data="userSelector({{ $data['antenne']->id }}, @json($data['utilisateurs']->pluck('user.id')))">

                <div class="flex flex-col md:flex-row gap-8">

                {{-- Partie gauche : tableau des utilisateurs --}}
                <div class="flex-1 overflow-x-auto">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">
                        Antenne : <span class="text-orange-600">{{ $data['antenne']->nom }}</span>
                    </h3>

                    <table class="min-w-full table-auto bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm font-semibold tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left w-2/6">Nom</th>
                            <th class="px-6 py-3 text-left w-3/6">Email</th>
                            <th class="px-6 py-3 text-center w-1/6">Responsable</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm" x-ref="userTable">
                        @foreach($data['utilisateurs'] as $user)
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3">{{ $user['user']->identifiant }}</td>
                                <td class="px-6 py-3">{{ $user['user']->email }}</td>
                                <td class="px-6 py-3 text-center font-semibold text-green-600">
                                    @if($data['responsable'] && $user['user']->id !== auth()->id())
                                        <input
                                            type="checkbox"
                                            :checked="{{ $user['est_responsable'] ? 'true' : 'false' }}"
                                            @change="toggleResponsable({{ $user['user']->id }}, $event)"
                                            class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer"
                                        />
                                    @else
                                        {!! $user['est_responsable'] ? '&#10003;' : '' !!}
                                    @endif
                                </td>
                                <td>
                                    @if($data['responsable'] && $user['user']->id !== auth()->id())
                                        <div x-data="{ open: false }">
                                            <button @click="open = true">❌</button>

                                            <div x-show="open"
                                                 x-transition
                                                 class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
                                                 x-cloak>
                                                <div @click.outside="open = false"
                                                     class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md space-y-6 relative">

                                                    <h2 class="text-xl font-semibold text-gray-800">Confirmer la suppression</h2>
                                                    <p class="text-gray-600">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>

                                                    <form method="POST"
                                                          action="{{ route('deleteUser', ['antenne' => $data['antenne']->id, 'user' => $user['user']->id]) }}"
                                                          class="flex justify-end gap-3">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button"
                                                                @click="open = false"
                                                                class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                                            Annuler
                                                        </button>

                                                        <button type="submit"
                                                                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Partie droite : formulaire d’ajout --}}
                    @if($data['responsable'])
                        <div class="w-full md:w-1/3 bg-gray-50 border border-gray-200 rounded-xl shadow p-5 mt-8 md:mt-0">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">Ajouter un utilisateur</h4>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher un utilisateur</label>
                            <input type="text"
                                   x-model="search"
                                   @input="filterUsers"
                                   @keydown.enter.prevent="handleKeydown($event)"
                                   @keydown.arrow-down.prevent="handleKeydown($event)"
                                   @keydown.arrow-up.prevent="handleKeydown($event)"
                                   placeholder="Tapez le nom..."
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 transition duration-150" />

                            <ul class="mt-4 rounded-lg border border-gray-200 bg-white shadow-md max-h-52 overflow-y-auto divide-y divide-gray-100"
                                x-show="filteredUsers.length > 0"
                                x-cloak
                                x-transition>
                                <template x-for="(user, index) in filteredUsers" :key="user.id">
                                    <li :class="{
                                            'bg-orange-100 text-orange-800': index === highlightedIndex,
                                            'hover:bg-gray-100': index !== highlightedIndex
                                        }"
                                        class="px-4 py-2 cursor-pointer transition"
                                        @mouseenter="highlightedIndex = index"
                                        @mouseleave="highlightedIndex = -1"
                                    >
                                        <form :x-ref="`userForm_${user.id}`" :action="`/antennes/{{ $data['antenne']->id }}/utilisateurs/${user.id}`" method="POST">
                                            <input type="hidden" name="_token" :value="csrfToken">
                                            <button type="submit" class="w-full text-left" x-text="user.identifiant"></button>
                                        </form>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <script>
        const users = <?php echo json_encode($users, 15, 512); ?>; // Liste globale disponible pour Alpine

        function userSelector(antenneId, assignedUserIds) {
            return {
                search: '',
                allUsers: users,
                filteredUsers: [],
                highlightedIndex: -1,
                assignedUserIds: assignedUserIds,
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                filterUsers() {
                    const term = this.search.toLowerCase();
                    this.filteredUsers = this.allUsers.filter(user =>
                        user.identifiant.toLowerCase().includes(term) &&
                        !this.assignedUserIds.includes(user.id)
                    );
                    this.highlightedIndex = 0;
                },

                handleKeydown(event) {
                    if (event.key === 'ArrowDown') {
                        if (this.highlightedIndex < this.filteredUsers.length - 1) {
                            this.highlightedIndex++;
                        }
                    } else if (event.key === 'ArrowUp') {
                        if (this.highlightedIndex > 0) {
                            this.highlightedIndex--;
                        }
                    } else if (event.key === 'Enter') {
                        const user = this.filteredUsers[this.highlightedIndex];
                        if (user && this.$refs[`userForm_${user.id}`]) {
                            this.$refs[`userForm_${user.id}`].submit();
                        }
                    }
                },

                ajouterUser(user) {
                    fetch(`/antennes/${antenneId}/utilisateurs`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ user_id: user.id, antenne_id: antenneId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.assignedUserIds.push(user.id);
                                this.search = '';
                                this.filteredUsers = [];
                                this.highlightedIndex = -1;

                                const tbody = this.$refs.userTable;

                                // On récupère ici si l'utilisateur est responsable et si on peut afficher la checkbox
                                const estResponsable = data.est_responsable || false;
                                const peutModifierResponsable = data.peut_modifier_responsable || false;

                                const newRow = document.createElement('tr');
                                newRow.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150';
                                newRow.innerHTML = `
                            <td class="px-6 py-3 whitespace-nowrap text-gray-900">${user.identifiant}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-600">${user.email}</td>
                            <td class="px-6 py-3 text-center text-green-600 font-bold text-lg">
                                ${
                                    peutModifierResponsable
                                        ? `<input
                                        type="checkbox"
                                        ${estResponsable ? 'checked' : ''}
                                        onchange="userSelector(${antenneId}, []).toggleResponsable(${user.id}, event)"
                                        class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer"
                                    />`
                                        : (estResponsable ? '&#10003;' : '')
                                }
                            </td>
                            <td>
                                    @if($data['responsable'] && $user['user']->id !== auth()->id())
                                        <div x-data="{ open: false }">
                                            <button @click="open = true">❌</button>

                                            <div x-show="open" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.6); display:flex; align-items:center; justify-content:center;">
                                                <div @click.outside="open = false" style="background:white; padding:20px; border-radius:8px; min-width:300px;">
                                                    <form method="POST" action="{{ route('deleteUser', ['antenne' => $data['antenne']->id, 'user' => $user['user']->id]) }}" class="p-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <h1>Êtes-vous sûr de vouloir supprimer l'utilisateur ?</h1>

                                                        <button type="submit">Oui</button>
                                                    </form>
                                                    <button @click="open = false">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                        `;
                                tbody.appendChild(newRow);
                            } else {
                                alert(data.message || "Erreur lors de l'ajout");
                            }
                        });
                },

                toggleResponsable(userId, event) {
                    const checked = event.target.checked;

                    fetch(`/antennes/${antenneId}/utilisateurs/${userId}/toggle-responsable`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ est_responsable: checked })
                    })
                        .then(response => {
                            if (!response.ok) throw response;
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                alert(data.message || "Erreur lors du changement de rôle.");
                                event.target.checked = !checked; // revert case
                            }
                        })
                        .catch(async error => {
                            let message = "Erreur";
                            if (error.json) {
                                const errData = await error.json();
                                message = errData.message || message;
                            }
                            alert(message);
                            event.target.checked = !checked;
                        });
                }
            };
        }
    </script>


    <!-- HTML avec Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>




    <meta name="csrf-token" content="{{ csrf_token() }}">
</x-app-layout>
