<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modification de ') }} {{ $produit->typeProduit->nom }}
        </h2>

    </x-slot>


    <div class="max-w-xl mx-auto mt-10">
        <form action="{{ route('produit.update', $produit->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div x-data="{ quantite: {{ old('quantite', $produit->quantite ?? 0) }} }">

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Produit</label>
                    {{-- <input type="text" value="{{ $produit->typeProduit->nom }}" class="w-full border rounded px-3 py-2" disabled> --}}
                    <select name="type_produit_id" class="w-full border rounded px-3 py-2">
                        <option value="">Sélectionner un produit</option>
                        @foreach($typeProduits as $type)
                            <option value="{{ $type->id }}" {{ $produit->type_produit_id == $type->id ? 'selected' : '' }}>
                                {{ $type->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Zone</label>
                    {{-- <input type="text" value="{{ $produit->zoneStock->nom }}" class="w-full border rounded px-3 py-2" disabled> --}}
                    <select name="zone_stock_id" class="w-full border rounded px-3 py-2">
                        <option value="">Sélectionner une zone</option>
                        @foreach($zonesStock as $zone)
                            <option value="{{ $zone->id }}" {{ $produit->zone_stock_id == $zone->id ? 'selected' : '' }}>
                                {{ $zone->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Quantité</label>
                    <input type="number" name="quantite" value="{{ old('quantite', $produit->quantite) }}"
                           class="w-full border rounded px-3 py-2" min="0" x-model.number="quantite">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Date de péremption</label>
                    <input type="date" name="date_peremption"
                           value="{{ old('date_peremption', optional($produit->date_peremption)->format('Y-m-d')) }}"
                           class="w-full border rounded px-3 py-2">
                </div>

            <div class="flex justify-between">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white text-center font-bold py-2 px-4 rounded w-44">
                    Enregistrer
                </button>
                <a href="{{ route('produit.transferView', $produit) }}" class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded mr-4 w-44">Transférer le stock</a>
                <div class="flex justify-between">
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white text-center font-bold py-2 px-4 rounded w-44">
                        Enregistrer
                    </button>
                    <a href="{{ route('produit.transferView', $produit) }}" class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded mr-4 w-44">Transférer le stock</a>
                </div>
            </div>
        </form>
    </div>


</x-app-layout>
