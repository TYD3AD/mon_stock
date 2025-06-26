<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController
{
    public function contactAdmin()
    {
        // Logique pour afficher le formulaire de contact à l'administrateur
        return view('contact-admin');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $user = Auth::user();
        $nom = $user?->nom ?? 'Nom inconnu';
        $prenom = $user?->prenom ?? 'Prénom inconnu';
        $name = "{$prenom} {$nom}";

        $files = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();

                // 🔄 Stocke dans storage/app/private/attachments
                $storedPath = $file->storeAs('attachments', $filename);

                // ✅ Chemin absolu vers le fichier stocké
                $files[] = storage_path('app/private/' . $storedPath);
            }
        }

        Mail::to('contact.teddy@rialet.fr')->send(new ContactMessage(
            $name,
            $validated['subject'],
            $validated['message'],
            $files
        ));

        // Supprime les fichiers temporaires après l'envoi
        foreach ($files as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        return back()->with('success', 'Message envoyé avec succès !');
    }


}
