<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des antennes') }}
        </h2>
    </x-slot>

    <div class="ml-56 p-6 space-y-10">
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
                                   @keydown="handleKeydown($event)"
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
                                        @click="ajouterUser(user)"
                                        @mouseenter="highlightedIndex = index"
                                        @mouseleave="highlightedIndex = -1"
                                        x-text="user.identifiant">
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
                        if (this.filteredUsers[this.highlightedIndex]) {
                            this.ajouterUser(this.filteredUsers[this.highlightedIndex]);
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

    <meta name="csrf-token" content="{{ csrf_token() }}">
</x-app-layout>
