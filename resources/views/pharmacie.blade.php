<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock pharmacie de ') }} {{ $antenne->nom }}
        </h2>

    </x-slot>



    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr>
                    <th class="px-4 py-2 border">Produit</th>
                    <th class="px-4 py-2 border">Zone</th>
                    <th class="px-4 py-2 border">Quantité</th>
                    <th class="px-4 py-2 border">Date de péremption</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($stocks as $stock)
                    <tr class="border-b @include('components.tailwind-class', ['status' => $stock->getStatus()])">
                        <td class="border px-4 py-2">{{ $stock->produit->nom }}</td>
                        <td class="border px-4 py-2">{{ $stock->zoneStock->nom }}</td>
                        <td class="border px-4 py-2">{{ $stock->quantite }}</td>
                        <td class="border px-4 py-2">{{ $stock->date_peremption->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
