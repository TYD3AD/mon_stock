<x-app-layout>
    {{-- Messages succes/erreurs --}}
    <div class="ml-56 my-4 mr-4">
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
    <div class="custom-height-md">
        {{-- Partie principale --}}
        <div class="w-full flex flex-row">

            {{-- Block tableau --}}
            <div class="lg:w-1/12 lg:left-0 md:w-1/12 md:left-0">
            </div>
            <div class="lg:w-11/12 md:w-11/12 w-full">
                <div class="bg-green-300 hidden">
                    <h1 class="ml-8 text-2xl font-bold">Partie filtre</h1>
                </div>
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                            <tr>
                                <th class="px-4 py-2 border w-7/12 text-start">Produit</th>
                                <th class="px-4 py-2 border text-start">Zone</th>
                                <th class="px-4 py-2 border text-start">Quantité</th>
                                <th class="px-4 py-2 border text-start">Date de péremption</th>
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
            </div>
        </div>
</x-app-layout>
