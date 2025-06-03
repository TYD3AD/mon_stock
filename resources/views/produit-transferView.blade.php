<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transfert de ') }} {{ $produit->typeProduit->nom }}
        </h2>

    </x-slot>


    <div class="max-w-xl mx-auto mt-10">
        <div x-data="transfertForm()" class="max-w-xl mx-auto mt-10">
            <form action="{{ route('produit.transfertUpdate', $produit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Produit</label>
                    {{-- <input type="text" value="{{ $produit->typeProduit->nom }}" class="w-full border rounded px-3 py-2" disabled> --}}
                    <input type="text" disabled value="{{ $produit->typeProduit->nom }}" class="w-full border rounded px-3 py-2 mt-2 bg-gray-200 text-gray-600">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Zone</label>
                    {{-- <input type="text" value="{{ $produit->zoneStock->nom }}" class="w-full border rounded px-3 py-2" disabled> --}}
                    <select name="zone_stock_id"
                            class="w-full border rounded px-3 py-2"
                            x-model="zoneId">
                        <option value="">Sélectionner une zone</option>
                        @foreach($zonesStock as $zone)
                            <option value="{{ $zone->id }}">
                                {{ $zone->nom }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <label class="block mb-1 font-semibold">Quantité</label>
                <div class="mb-4 flex">
                    <input type="number" name="quantite"
                           x-model="quantite"
                           class="w-32 border rounded px-3 py-2"
                           min="1"
                           max="{{ old('quantite', $produit->quantite) }}">

                    <p class="ml-4 text-xl self-center">/{{ old('quantite', $produit->quantite) }}</p>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Date de péremption</label>
                    <input type="date" name="date_peremption"
                           value="{{ old('date_peremption', optional($produit->date_peremption)->format('Y-m-d')) }}"
                           class="w-full border rounded px-3 py-2 mt-2 bg-gray-200 text-gray-600" disabled>
                </div>

                <div class="flex justify-between">

                    <a href="{{ route('dashboard') }}" class="bg-red-500 hover:bg-red-700 text-white text-center font-bold py-2 px-4 rounded mr-4 w-28">
                        Annuler
                    </a>
                    <button
                        type="submit"
                        :class="{
        'bg-gray-400 font-bold cursor-not-allowed pointer-events-none w-28 text-center': !canSubmit(),
        'bg-blue-500 hover:bg-blue-700 font-bold cursor-pointer w-28 text-center': canSubmit()
    }"
                        :disabled="!canSubmit()"
                        class="text-white px-4 py-2 rounded  transition-colors duration-600"
                    >
                        Transférer
                    </button>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>

<script>
    const quantiteInput = document.querySelector('input[name="quantite"]');

    const max = {{ old('quantite', $produit->quantite) }};
    const bouton = document.querySelector('button[type="submit"]');

    // Vérifie que la quantité saisie est un nombre inférieur ou égal à la quantité maximale
    quantiteInput.addEventListener('input', function () {
        if (this.value > max) {
            this.value = max;
        }
    })

    // vérifie que le quantiteInput n'est pas vide
    quantiteInput.addEventListener('input', function () {
        const val = parseInt(this.value);

        // Désactiver si vide, < 1 ou > max
        bouton.disabled = isNaN(val) || val < 1 || val > max;
    });

    // vérifie que le zoneInput n'est pas vide
    zoneInput.addEventListener('input', function () {
        const val = this.value;

        // Désactiver si vide, < 1 ou > max
        bouton.disabled = !!(val === '' || val == null || val.empty);
    });

    // Déclencher une première validation au chargement
    window.addEventListener('DOMContentLoaded', () => {
        quantiteInput.dispatchEvent(new Event('input'));
        zoneInput.dispatchEvent(new Event('input'));
    });

    function transfertForm() {
        return {
            quantite: null,
            zoneId: '', // on déclare bien la propriété zoneId
            max: {{ old('quantite', $produit->quantite) }},

            canSubmit() {
                const val1 = parseInt(this.quantite);
                const val2 = this.zoneId;
                return !isNaN(val1) && val1 >= 1 && val1 <= this.max && val2 !== '';
            }
        }
    }

</script>
