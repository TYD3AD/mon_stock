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

    <form action="{{ route('produit.store') }}" method="POST" @submit.prevent="submitForm">
        @csrf
        <div x-data="produitSelector()" class="w-full mx-auto mt-10 flex flex-col items-center">

                <!-- Recherche produit -->
                <label class="block font-semibold mb-2">Rechercher un type de produit</label>
                <input
                    type="text"
                    x-model="search"
                    @input="filterProduits"
                    placeholder="Tapez le nom d’un produit..."
                    class="w-2/5 border rounded px-3 py-2"
                >

                <ul class="border rounded mt-2 bg-white shadow max-h-48 overflow-auto" x-show="filteredProduits.length > 0">
                    <template x-for="produit in filteredProduits" :key="produit.id">
                        <li
                            class="px-3 py-2 hover:bg-gray-200 cursor-pointer"
                            @click="ajouterProduit(produit)"
                            x-text="produit.nom"
                        ></li>
                    </template>
                </ul>

                <!-- Tableau des produits sélectionnés -->
                <table class="mt-6 w-11/12 border-collapse border border-gray-300" x-show="selectedProduits.length > 0">
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

                            <!-- Input caché produit_id indispensable -->
                            <input type="hidden" :name="`produits[${index}][produit_id]`" :value="produit.id">


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
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">
                    Enregistrer les produits
                </button>
            </div>

    </form>

    <script>
        function produitSelector() {
            return {
                search: '',
                produits: @json($typesProduits),
                zonesStock: @json($zonesStock),
                filteredProduits: [],
                selectedProduits: [],

                filterProduits() {
                    const terme = this.search.toLowerCase();
                    this.filteredProduits = this.produits.filter(p =>
                        p.nom.toLowerCase().includes(terme)
                    );
                },

                ajouterProduit(produit) {
                    // Ajouter une nouvelle ligne dans selectedProduits
                    this.selectedProduits.push({
                        id: produit.id,
                        nom: produit.nom,
                        zone_stock_id: this.zonesStock.length ? this.zonesStock[0].id : null,
                        quantite: 0,
                        date_peremption: '',
                    });

                    this.search = '';
                    this.filteredProduits = [];
                },

                supprimerProduit(index) {
                    this.selectedProduits.splice(index, 1);
                }
            }
        }
    </script>






</x-app-layout>

