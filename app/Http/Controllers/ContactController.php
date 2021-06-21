<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequestMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Swift_TransportException;
use Config;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        request()->validate([
            'fullName' => ['required'],
            'email' => ['required', 'email'],
            'subject' => ['required'],
            'message' => ['required'],
        ]);

        $genderArray = config('enums.gender');
        $gender = $genderArray[$request->gender];

        $contactRequest = new Contact();
        $contactRequest->gender = $gender ? $gender : 'Inconnu';
        $contactRequest->fullName = $request->fullName;
        $contactRequest->email = $request->email;
        $contactRequest->phoneNumber = $request->phoneNumber;
        $contactRequest->subject = $request->subject;
        $contactRequest->message = $request->message;

        $contactRequest->save();
        $contact = Contact::find($contactRequest->id);
        $genderArray = config('enums.gender');

        try {
            Mail::to(config('env_variables.contact_mail_to'))->send(new ContactRequestMail($contact));
        } catch(Swift_TransportException $transportExp) {
           return response(['message' => "<p>Une erreur est survenue lors de l'envoi, nous en sommes désolés.</p><p>Si le problème presiste veuillez nous contacter directement via notre email.</p>"], 400);
        }
        return response(['message' => "<p>Mail envoyé avec succès, nous reviendrons vers vous dès que possible.</p>"], 200);
    }
}
