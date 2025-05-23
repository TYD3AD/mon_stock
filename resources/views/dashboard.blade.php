<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{--            {{ __('Stock de l\'antenne de ') }} {{ $antennes->first() }}--}}
            {{-- Bouton ajout de produit --}}
            <div class="flex justify-end sm:px-6 lg:px-8">
                <a href="{{ route('produit.create') }}"
                   class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 mr-12 rounded">
                    Ajout de produits
                </a>
            </div>
        </h2>
    </x-slot>
    @php
        $pharmacie = 1;
        $vtu = 2;
        $vpsp = 3;
    @endphp
<div class="flex flex-row">
    <div class="w-1/12 ">{{-- Navbar --}}
        <nav class="w-1/12 top-0 bottom-0 left-0 w-full bg-red-300">
            <div>
                <h4>Antenne de {{$antenneP->nom}}</h4>
                @if($zones->contains(fn($zone) => $zone->antenne_id === $antenneP->id && $zone->categorie == 1))
                    <div>
                        <a href="{{ route('produits.listAccess', [$antenneP->id, $pharmacie]) }}">Pharmacie</a>
                    </div>
                @endif
                @if($zones->contains(fn($zone) => $zone->antenne_id === $antenneP->id && $zone->categorie == 2))
                    <div>
                        <a href="{{ route('produits.listAccess', [$antenneP->id, $vtu]) }}">VTU</a>
                    </div>
                @endif
                @if($zones->contains(fn($zone) => $zone->antenne_id === $antenneP->id && $zone->categorie == 3))
                    <div>
                        <a href="{{ route('produits.listAccess', [$antenneP->id, $vpsp]) }}">VPSP</a>
                    </div>
                @endif
            </div>

            @foreach($antennes as $antenne)
                <div>
                    <h4>Antenne de {{$antenne->nom}}</h4>

                    @if($zones->contains(fn($zone) => $zone->antenne_id === $antenne->id && $zone->categorie == 1))
                        <div>
                            <a href="{{ route('produits.listAccess', [$antenne->id, $pharmacie]) }}">Pharmacie</a>
                        </div>
                    @endif
                    @if($zones->contains(fn($zone) => $zone->antenne_id === $antenne->id && $zone->categorie == 2))
                        <div>
                            <a href="{{ route('produits.listAccess', [$antenne->id, $vtu]) }}">VTU</a>
                        </div>
                    @endif
                    @if($zones->contains(fn($zone) => $zone->antenne_id === $antenne->id && $zone->categorie == 3))
                        <div>
                            <a href="{{ route('produits.listAccess', [$antenne->id, $vpsp]) }}">VPSP</a>
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


            <div class="bg-green-300">
                <h1 class="ml-8 text-2xl font-bold">Partie filtre</h1>
            </div>
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
    </div>
</div>
</x-app-layout>
