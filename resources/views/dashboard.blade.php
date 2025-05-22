<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock de l\'antenne de ') }} {{ $antennes->first() }}
        </h2>

    </x-slot>


    <div class="ml-12">
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg" name="messageError">{!! session('error') !!}</div>
        @elseif(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg" name="messageSuccess">{!! session('success') !!}</div>
        @endif

        <script>
            setTimeout(function () {
                const errorMessage = document.querySelector('[name="messageError"]');
                const successMessage = document.querySelector('[name="messageSuccess"]');

                if (errorMessage) errorMessage.style.display = 'none';
                if (successMessage) successMessage.style.display = 'none';
            }, 3000);
        </script>
    </div>

    <div>
        {{-- Bouton ajout de produit --}}
        <div class="flex justify-end py-4 pt-10 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('produit.create') }}"
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 mr-12 rounded">
                Ajout de produits
            </a>
    </div>

    <h1 class="ml-8 text-2xl font-bold">Le matériel de l'antenne</h1>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr>
                    <th class="px-4 py-2 border w-7/12">Produit</th>
                    <th class="px-4 py-2 border">Zone</th>
                    <th class="px-4 py-2 border">Quantité</th>
                    <th class="px-4 py-2 border">Date de péremption</th>
                    <th class="px-4 py-2 border w-1/12">Modification</th>
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
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded">
                                    Modifier
                                </a>
                                <a href="{{ route('produit.delete', $produit) }}"
                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                                    Supprimer
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











