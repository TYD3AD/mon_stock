<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>

    </x-slot>
<div class="ml-56 mr-4">
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
<div class="w-full flex flex-row">
    <div class="lg:w-1/12 lg:left-0 md:w-1/12 md:left-0"></div>
    <div class="lg:w-11/12 md:w-11/12 lg:mx-2 md:mx-2 w-full">
        <h1 class="ml-8 mt-12 text-2xl font-bold">{{ __('Stock ') }} {{$categorie}} {{__(' de l\'antenne de ')}} {{ $antennes->first() }}</h1>
        <div class="py-6">
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
                                    <x-danger-button style="background-color: white; justify-content: center !important; align-items: center !important; height: 2rem; width: 2rem; font-weight: bold; font-size: 1rem; text-align: center;"
                                                     {{-- Utilisation de Alpine.js pour ouvrir le modal de confirmation --}}
                                                     {{-- x-data="" permet d'initialiser Alpine.js --}}
                                                     {{-- x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-product-{{ $produit->id }}')" permet d'ouvrir le modal de confirmation --}}
                                                     {{-- Utilisation de Alpine.js pour ouvrir le modal de confirmation --}}
                                                     x-data=""
                                                     x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-product-{{ $produit->id }}')"
                                    >{{ __( '❌' ) }}</x-danger-button>
                                </div>
                            </td>
                        </tr>

                        <x-modal name="confirm-delete-product-{{ $produit->id }}" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            <form method="post" action="{{ route('produit.delete', $produit) }}" class="p-2">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-center text-gray-900">
                                    {{ __('Êtes-vous sûr de vouloir supprimer ce produit ?') }}
                                </h2>

                                <div class="mt-6 flex justify-center mb-6">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Annuler') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ms-3">
                                        {{ __('Supprimer le produit') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
