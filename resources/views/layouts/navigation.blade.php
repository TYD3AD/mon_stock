<style>
    .custom-height-md {
        height: calc(100vh - 80px);
    }

    @media (min-width: 768px) {
        .logoStyle {
            width: 15%;
        }
    }

    @media (max-width: 639px) {
        .logoStyle {
            width: 100%;
        }
    }
</style>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 flex h-20 items-center justify-between px-4">
    <!-- Logo + Menu gauche -->
    <div class="flex items-center logoStyle">
        <!-- Bouton hamburger gauche (uniquement visible en mobile) -->
        <div class="lg:hidden p-4">
            <button id="menu-toggle" class="text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Logo et nom -->
        <a href="{{ route('dashboard') }}" class="flex flex-row items-center">
            <img src="/img/logo-protec.svg" alt="Logo Protection Civile" class="h-14 w-14 mx-4 my-2">
            <h1 class="monStock">{{ __('Mon Stock') }}</h1>
        </a>
    </div>

    <!-- Liens de navigation (centrés) -->
    <div class="hidden sm:flex sm:items-center sm:gap-x-4 *:text-lg w-10/12 justify-center">
        <x-nav-link :href="route('produit.create')" :active="request()->routeIs('produit.create')">
            {{ __('Ajout de produits') }}
        </x-nav-link>
        <x-nav-link :href="route('commandes.store')" :active="request()->routeIs('commandes.store')">
            {{ __('Gestion des commandes') }}
        </x-nav-link>
        <x-nav-link :href="route('gestion-antenne.store')" :active="request()->routeIs('gestion-antenne.store')">
            {{ __('Gestion antenne') }}
        </x-nav-link>
    </div>

    <!-- Profil + Hamburger droit -->
    <div class="flex items-center">
        <!-- Dropdown utilisateur (desktop) -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Déconnexion -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                         onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Déconnexion') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Hamburger responsive (droite, mobile) -->
        <div class="sm:hidden">
            <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                          stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                          stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu responsive -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden w-full mt-4 z-10">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Paramètres utilisateur -->
        <div class="pt-4 pb-1 border-t border-gray-200
        bg-gray-100 text-white">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Déconnexion -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Déconnexion') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>


{{--Navigation Bar horizontale--}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('menu-toggle');
        const nav = document.getElementById('side-nav');

        toggle.addEventListener('click', function () {
            nav.classList.toggle('hidden');
        });
    });
</script>

@php
use App\Models\Antenne;
use App\Models\ZoneStock;
use Illuminate\Support\Facades\Auth;
    // Définit les catégories de zones de stock
    $pharmacie = 1;
    $vtu = 2;
    $vpsp = 3;
    // Récupère l'utilisateur connecté
    $user = Auth::user();
    // Récupère les antennes de l'utilisateur connecté
    $antennes = auth()->user()->antennes()->get();
    $antenneP = Antenne::where('id', $user->antenne_id)->first();
    // retire l'antenne principale de la liste des antennes
    $antennes = $antennes->where('id', '!=', $user->antenne_id);
    // récupère les zones de stock des antennes de l'utilisateur
    $zones = ZoneStock::whereIn('antenne_id', auth()->user()->antennes->pluck('id'))->get();
@endphp

    <!-- Container nav complet -->
<div class=" lg:w-1/12 absolute custom-height-md bg-white">
    <!-- Navbar : masquée sur mobile, visible si toggle activé -->
    <nav id="side-nav"
         class="hidden lg:block inset-y-0 left-0 bg-white text-gray-500 p-4 flex flex-col space-y-4 z-50"> {{-- ajout z-50 pour l'empêcher d'être cachée --}}

        <!-- Liens de navigation (centrés) -->
        <div class="lg:hidden sm:flex sm:items-center sm:gap-x-4 *:text-lg w-10/12 justify-center">
            <h4 class="text-lg font-semibold border-b-2 border-orange-500 pb-2 mb-2">Actions</h4>
            <x-nav-link :href="route('produit.create')" :active="request()->routeIs('produit.create')">
                {{ __('Ajout de produits') }}
            </x-nav-link>
            <x-nav-link :href="route('commandes.store')" :active="request()->routeIs('commandes.store')">
                {{ __('Gestion des commandes') }}
            </x-nav-link>
            <x-nav-link :href="route('gestion-antenne.store')" :active="request()->routeIs('gestion-antenne.store')">
                {{ __('Gestion antenne') }}
            </x-nav-link>
        </div>

        <div class="mb-6"> {{-- Added margin-bottom for separation --}}
            <h4 class="text-lg font-semibold border-b-2 border-orange-500 pb-2 mb-2">Antenne
                de {{$antenneP->nom}}</h4> {{-- Styled heading --}}
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
            <div
                class="{{ !$loop->last ? 'border-orange-500 pb-4 mb-4' : '' }}"> {{-- Separator for multiple antennas --}}
                <h4 class="text-lg font-semibold border-b-2 border-orange-500 pb-2 mb-2">Antenne
                    de {{$antenne->nom}}</h4> {{-- Styled heading --}}

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
