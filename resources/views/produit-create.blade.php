<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajout de produits') }}
        </h2>
    </x-slot>

    <div class="ml-12">
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg">{!! session('error') !!}</div> {{-- Note le `{!! !!}` --}}
        @elseif(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg">{!! session('success') !!}</div> {{-- Note le `{!! !!}` --}}
        @endif
    </div>
    <div class="w-full flex flex-row">
        <div class="lg:w-1/12 lg:left-0 md:w-1/12 md:left-0">
        </div>
        <div class="lg:w-11/12 md:w-11/12 lg:mx-2 md:mx-2 w-full">
            <form action="{{ route('produit.store') }}" method="POST" class="w-full">
                @csrf
                <div x-data="produitSelector()" class="w-full mx-auto mt-10 flex flex-col items-center">

                    <!-- Recherche produit -->
                    <label class="block font-semibold mb-2">Rechercher un type de produit</label>
                    <input
                        type="text"
                        x-model="search"
                        @input="filterProduits"
                        @keydown="handleKeydown($event)"
                        placeholder="Tapez le nom d’un produit..."
                        class="w-2/5 border rounded px-3 py-2"
                    />

                    <ul class="border rounded mt-2 bg-white shadow max-h-48 overflow-auto" x-show="filteredProduits.length > 0" tabindex="0" @keydown.arrow-down.prevent.stop="handleKeydown($event)" @keydown.arrow-up.prevent.stop="handleKeydown($event)" @keydown.enter.prevent.stop="handleKeydown($event)">
                        <template x-for="(produit, index) in filteredProduits" :key="produit.id">
                            <li
                                :class="{'bg-blue-300': index === highlightedIndex, 'hover:bg-gray-200': index !== highlightedIndex}"
                                class="px-3 py-2 cursor-pointer"
                                @click="ajouterProduit(produit);"
                                @mouseenter="highlightedIndex = index"
                                @mouseleave="highlightedIndex = -1"
                                x-text="produit.nom"
                            ></li>
                        </template>
                    </ul>

                    <!-- Tableau des produits sélectionnés -->
                    <div class="mt-6 w-full overflow-x-auto">
                        <table class="min-w-[700px] border-collapse border border-gray-300" x-show="selectedProduits.length > 0">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-2 py-1 text-left w-8/12">Produit</th>
                                <th class="border border-gray-300 px-2 py-1 text-left w-1/6">Zone de stockage</th>
                                <th class="border border-gray-300 px-2 py-1 text-left w-52">Quantité</th>
                                <th class="border border-gray-300 px-2 py-1 text-left w-52">Date de péremption</th>
                                <th class="border border-gray-300 px-2 py-1 text-left w-12">Supprimer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="(produit, index) in selectedProduits" :key="index">
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1" x-text="produit.nom"></td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <select
                                            class="border rounded px-2 py-1 w-full"
                                            x-model="produit.zone_stock_id"
                                            :name="`produits[${index}][zone_stock_id]`"
                                        >
                                            <template x-for="zone in zonesStock" :key="zone.id">
                                                <option :value="zone.id" x-text="zone.nom"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input
                                            type="number"
                                            min="0"
                                            class="border rounded px-2 py-1 w-full"
                                            x-model.number="produit.quantite"
                                            :name="`produits[${index}][quantite]`"
                                        >
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input
                                            type="date"
                                            class="border rounded px-2 py-1 w-full"
                                            x-model="produit.date_peremption"
                                            :name="`produits[${index}][date_peremption]`"
                                        >
                                    </td>
                                    <input type="hidden" :name="`produits[${index}][type_produit_id]`" :value="produit.id">
                                    <td class="border border-gray-300 px-2 py-1 text-center">
                                        <button
                                            type="button"
                                            @click="supprimerProduit(index)"
                                            class="text-red-600 font-bold"
                                        >
                                            X
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <button
                        type="submit"
                        :class="{
            'bg-gray-400 cursor-not-allowed pointer-events-none': !canSubmit(),
            'bg-blue-500 hover:bg-blue-600 cursor-pointer': canSubmit()
        }"
                        class="text-white px-4 py-2 rounded mt-4 transition-colors duration-1000"
                    >
                        Enregistrer les produits
                    </button>



                </div>

            </form>
        </div>
    </div>

    <script>
        function produitSelector() {
            return {
                search: '',
                produits: @json($typesProduits),
                zonesStock: @json($zonesStock),
                filteredProduits: [],
                selectedProduits: [],
                highlightedIndex: -1,  // index du produit sélectionné au clavier

                filterProduits() {
                    const terme = this.search.toLowerCase();
                    this.filteredProduits = this.produits.filter(p =>
                        p.nom.toLowerCase().includes(terme)
                    );
                    this.highlightedIndex = -1; // reset quand on tape
                },

                canSubmit() {
                    if (this.selectedProduits.length === 0) return false;
                    // Vérifie que toutes les quantités sont > 0
                    return this.selectedProduits.every(p => p.quantite > 0);
                },

                ajouterProduit(produit) {
                    this.selectedProduits.push({
                        id: produit.id,
                        nom: produit.nom,
                        zone_stock_id: this.zonesStock.length ? this.zonesStock[0].id : null,
                        quantite: 0,
                        date_peremption: '',
                    });
                    this.search = '';
                    this.filteredProduits = [];
                    this.highlightedIndex = -1;
                },

                supprimerProduit(index) {
                    this.selectedProduits.splice(index, 1);
                },

                // Gérer la navigation au clavier
                handleKeydown(event) {
                    if (this.filteredProduits.length === 0) return;

                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        if (this.highlightedIndex < this.filteredProduits.length - 1) {
                            this.highlightedIndex++;
                        } else {
                            this.highlightedIndex = 0;
                        }
                    } else if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        if (this.highlightedIndex > 0) {
                            this.highlightedIndex--;
                        } else {
                            this.highlightedIndex = this.filteredProduits.length - 1;
                        }
                    } else if (event.key === 'Enter') {
                        event.preventDefault();
                        if (this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredProduits.length) {
                            this.ajouterProduit(this.filteredProduits[this.highlightedIndex]);
                        }
                    }
                },
            }
        }



    </script>






</x-app-layout>

