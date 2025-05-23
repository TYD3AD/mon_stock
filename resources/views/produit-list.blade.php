<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>

    </x-slot>


    <h1 class="ml-8 mt-12 text-2xl font-bold">{{ __('Stock ') }} {{$categorie}} {{__(' de l\'antenne de ')}} {{ $antennes->first() }}</h1>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr>
                    <th class="px-4 py-2 border">Produit</th>
                    <th class="px-4 py-2 border">Zone</th>
                    <th class="px-4 py-2 border">Quantité</th>
                    <th class="px-4 py-2 border">Date de péremption</th>
                    <th class="px-4 py-2 border w-1/12"></th>
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
                            <div class="flex justify-center space-x-6">
                                <a href="{{ route('produit.edit', $produit) }}"
                                   class="bg-white font-bold rounded h-fit w-8">
                                    <img src="/img/edit.png" alt="" class="w-28 h-fit">
                                </a>
                                <a href="{{ route('produit.delete', $produit) }}"
                                   class="bg-white hover:bg-white text-white font-bold h-8 w-8 rounded content-center">
                                    ❌
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
