<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScholarshipConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;

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
        $email = $this->view('emails.ScholarshipConfirmationMail')->subject('Confirmation de réception de votre demande de bourse en ligne');

        if(isset($this->details['zipPath'])){
            $email->attachFromStorage($this->details['zipPath'], 'Association Envol - Documents attachés à votre demande de bourse.zip', [
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
