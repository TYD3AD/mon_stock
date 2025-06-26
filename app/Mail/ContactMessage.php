<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $name;        // Ajouté ici
    public $subjectLine; // Sujet du mail (sans nom)
    public $content;
    public $files;

    public function __construct($name, $subjectLine, $content, $files = [])
    {
        $this->name = $name;                     // On stocke le nom
        $this->subjectLine = $subjectLine;
        $this->content = $content;
        $this->files = $files;

        // Compose le sujet complet ici
        $this->subject("{$this->name} vous a envoyé un message : {$this->subjectLine}");
    }

    public function build()
    {
        $email = $this->view('emails.contact');

        foreach ($this->files as $filePath) {
            $email->attach($filePath);
        }

        return $email;
    }
}
