<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock pharmacie de ') }} {{ $antenne->nom }}
        </h2>

    </x-slot>


    <h1 class="ml-8 mt-12 text-2xl font-bold">Le stock pharmacie de l'antenne</h1>
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
