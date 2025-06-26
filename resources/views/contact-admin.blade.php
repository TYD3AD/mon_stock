<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact administrateur') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Formulaire de contact administrateur
            </h1>
            <p class="text-gray-700 text-base leading-relaxed mb-2">
                Contactez l'administrateur pour toute question ou bug rencontré sur l'application.
            </p>
            <p class="text-gray-600 text-sm">
                Pour rappel, l'application est en cours de développement. Des bugs ou anomalies peuvent apparaître et gêner l'utilisation.
                Si vous êtes confronté à une anomalie, merci de contacter l'administrateur et d'indiquer le problème rencontré.
            </p>
        </div>

        <form action="{{ route('contact.send') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white shadow rounded-lg p-6">
            @csrf

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Sujet</label>
                <input type="text" name="subject" id="subject" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="message" id="message" rows="6" required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>

            <div>
                <label for="attachments" class="block text-sm font-medium text-gray-700">Pièces jointes (optionnel)</label>
                <input type="file" name="attachments[]" id="attachments" multiple
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100" />
            </div>

            <div>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent
                        rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none
                        focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Envoyer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
