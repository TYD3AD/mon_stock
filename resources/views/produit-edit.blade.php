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
                       class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Date de péremption</label>
                <input type="date" name="date_peremption"
                       value="{{ old('date_peremption', $produit->date_peremption->format('Y-m-d')) }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div class="flex items-center">
                <button type="submit"
                        class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Enregistrer
                </button>
                <div class="ml-12">
                    @if(session('error'))
                        <div class="bg-red-500 text-white p-4 rounded-lg">{!! session('error') !!}</div> {{-- Note le `{!! !!}` --}}
                    @elseif(session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-lg">{!! session('success') !!}</div> {{-- Note le `{!! !!}` --}}
                    @endif
                </div>
            </div>
        </form>
    </div>


</x-app-layout>
