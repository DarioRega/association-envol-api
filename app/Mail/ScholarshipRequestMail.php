<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScholarshipRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.ScholarshipRequestMail')->subject('Nouvelle demande de bourse en ligne');

            if(isset($this->details['zipPath'])){
               $email->attachFromStorage($this->details['zipPath'], $this->details['zipName'], [
                    'mime' => 'application/octet-stream',
                ]);
            } else {
                foreach ($this->details['files'] as $file) {
                    $email->attachFromStorage($file['srcUrl'], $file['name'], [
                        'mime' => $file['mimeType'],
                    ]);
                }
            }
        return $email;
    }
}
