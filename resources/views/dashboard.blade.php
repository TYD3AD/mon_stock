<x-app-layout>

    @php
        $pharmacie = 1;
        $vtu = 2;
        $vpsp = 3;
    @endphp
    <div class="flex flex-row h-screen">
        <div class="w-1/12 relative">
            <nav class="absolute inset-y-0 left-0 w-full bg-white text-gray-500 p-4 flex flex-col space-y-4"> {{-- Darker red, white text, padding, flex column, spacing --}}
                <div class="mb-6"> {{-- Added margin-bottom for separation --}}
                    <h4 class="text-lg font-semibold border-b-2 border-orange-500 pb-2 mb-2">Antenne de {{$antenneP->nom}}</h4> {{-- Styled heading --}}
                    @if($zones->contains(fn($zone) => $zone->antenne_id === $antenneP->id && $zone->categorie == 1))
                        <div>
                            <a href="{{ route('produits.listAccess', [$antenneP->id, $pharmacie]) }}"
                               class="block py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-100 hover:text-orange-500 transition duration-150 ease-in-out">Pharmacie</a> {{-- Button styling --}}
                        </div>
                    @endif
                    @if($zones->contains(fn($zone) => $zone->antenne_id === $antenneP->id && $zone->categorie == 2))
                        <div>
                            <a href="{{ route('produits.listAccess', [$antenneP->id, $vtu]) }}"
                               class="block py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-100 hover:text-orange-500 transition duration-150 ease-in-out">VTU</a> {{-- Button styling --}}
                        </div>
                    @endif
                    @if($zones->contains(fn($zone) => $zone->antenne_id === $antenneP->id && $zone->categorie == 3))
                        <div>
                            <a href="{{ route('produits.listAccess', [$antenneP->id, $vpsp]) }}"
                               class="block py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-100 hover:text-orange-500 transition duration-150 ease-in-out">VPSP</a> {{-- Button styling --}}
                        </div>
                    @endif
                </div>

                @foreach($antennes as $antenne)
                    <div class="{{ !$loop->last ? 'border-orange-500 pb-4 mb-4' : '' }}"> {{-- Separator for multiple antennas --}}
                        <h4 class="text-lg font-semibold border-b-2 border-orange-500 pb-2 mb-2">Antenne de {{$antenne->nom}}</h4> {{-- Styled heading --}}

                        @if($zones->contains(fn($zone) => $zone->antenne_id === $antenne->id && $zone->categorie == 1))
                            <div>
                                <a href="{{ route('produits.listAccess', [$antenne->id, $pharmacie]) }}"
                                   class="block py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-100 hover:text-orange-500 transition duration-150 ease-in-out">Pharmacie</a> {{-- Button styling --}}
                            </div>
                        @endif
                        @if($zones->contains(fn($zone) => $zone->antenne_id === $antenne->id && $zone->categorie == 2))
                            <div>
                                <a href="{{ route('produits.listAccess', [$antenne->id, $vtu]) }}"
                                   class="block py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-100 hover:text-orange-500 transition duration-150 ease-in-out">VTU</a> {{-- Button styling --}}
                            </div>
                        @endif
                        @if($zones->contains(fn($zone) => $zone->antenne_id === $antenne->id && $zone->categorie == 3))
                            <div>
                                <a href="{{ route('produits.listAccess', [$antenne->id, $vpsp]) }}"
                                   class="block py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-100 hover:text-orange-500 transition duration-150 ease-in-out">VPSP</a> {{-- Button styling --}}
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
        </div>
        <div class="w-11/12"> {{-- Partie principale --}}
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
