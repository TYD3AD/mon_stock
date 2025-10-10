<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock pharmacie de ') }} {{ $antennes->first() }}
        </h2>

    </x-slot>


    <h1 class="ml-8 mt-12 text-2xl font-bold">Le stock pharmacie de l'antenne</h1>
    {{-- Début Section filtres --}}
                    <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-sm mx-3 sm:mx-6 mt-4 sm:mt-6 p-2.5 sm:p-5 w-[95%]  mx-auto">
                        <form method="GET" action="{{ route('produit.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-2 sm:gap-4 items-end">

                            {{-- Recherche --}}
                            <div class="flex flex-col">
                                <label for="search" class="font-semibold text-gray-700 mb-0.5 sm:mb-1 text-xs sm:text-base">🔍 Recherche</label>
                                <input type="text" id="search" name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Nom du produit..."
                                       class="border border-gray-300 rounded-md sm:rounded-lg px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            </div>

                            {{-- Zone --}}
                            <div class="flex flex-col">
                                <label for="zone" class="font-semibold text-gray-700 mb-0.5 sm:mb-1 text-xs sm:text-base">🏷️ Zone de stockage</label>
                                <select id="zone" name="zone"
                                        class="border border-gray-300 rounded-md sm:rounded-lg px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                                    <option value="">Toutes les zones</option>
                                    @foreach ($zones as $zone)
                                        <option value="{{ $zone->id }}" @selected(request('zone') == $zone->id)>
                                            {{ $zone->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Péremption --}}
                            <div class="flex flex-col">
                                <label for="peremption" class="font-semibold text-gray-700 mb-0.5 sm:mb-1 text-xs sm:text-base">📅 État de péremption</label>
                                <select id="peremption" name="peremption"
                                        class="border border-gray-300 rounded-md sm:rounded-lg px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                                    <option value="">Tous les états</option>
                                    <option value="perime" @selected(request('peremption') == 'perime')>Périmé</option>
                                    <option value="tres_proche" @selected(request('peremption') == 'tres_proche')>Très proche</option>
                                    <option value="proche" @selected(request('peremption') == 'proche')>Proche</option>
                                    <option value="correcte" @selected(request('peremption') == 'correcte')>Correcte</option>
                                    <option value="loin" @selected(request('peremption') == 'loin')>Loin</option>
                                    <option value="aucune" @selected(request('peremption') == 'aucune')>Sans date</option>
                                </select>
                            </div>

                            {{-- Bouton --}}
                            <div class="">
                                <button type="submit"
                                        class="bg-blue-600 text-white px-3 sm:px-5 py-1.5 sm:py-2.5 rounded-md sm:rounded-lg text-xs sm:text-base font-medium hover:bg-blue-700 active:bg-blue-800 transition duration-150 ease-in-out shadow-sm w-full md:w-auto">
                                    🧭 Filtrer
                                </button>
                            </div>

                        </form>
                    </div>
                    {{-- Fin Section filtres --}}
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr>
                    <th class="px-4 py-2 border">Produit</th>
                    <th class="px-4 py-2 border">Zone</th>
                    <th class="px-4 py-2 border">Quantité</th>
                    <th class="px-4 py-2 border">Date de péremption</th>
                    <th class="px-4 py-2 border">Modification</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($produits as $produit)
                    <tr class="border-b @include('components.tailwind-class', ['status' => $produit->getStatus()])">
                        <td class="border px-4 py-2">{{ $produit->typeProduit->nom }}</td>
                        <td class="border px-4 py-2">{{ $produit->zoneStock->nom }}</td>
                        <td class="border px-4 py-2">{{ $produit->quantite }}</td>
                        <td class="border px-4 py-2">
                            @if ($produit->date_peremption != null)
                                {{ \Carbon\Carbon::parse($produit->date_peremption)->format('d/m/Y') }}
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <a href="{{ route('produit.edit', $produit) }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded">
                                Modifier
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
