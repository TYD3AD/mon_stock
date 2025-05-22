<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock de l\'antenne de ') }} {{ $antennes->first() }}
        </h2>

    </x-slot>

    <div>
        {{-- Bouton ajout de produit --}}
        <div class="flex justify-end py-4 pt-10 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('produit.create') }}"
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 mr-12 rounded">
                Ajout de produits
            </a>
    </div>
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
                        <td class="border px-4 py-2">{{ $produit->date_peremption->format('d/m/Y') }}</td>
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
