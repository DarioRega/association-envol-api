<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequestMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Swift_TransportException;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        //        request()->validate([
//            'file' => 'required',
//            'file.*' => 'mimes:doc,pdf,docx,txt,xls,'
//        ]);

        $contactRequest = new Contact();
        $genderArray = config('enums.gender');

        $contactRequest->gender = $genderArray[$request->gender];
        $contactRequest->fullName = $request->fullName;
        $contactRequest->email = $request->email;
        $contactRequest->phoneNumber = $request->phoneNumber;
        $contactRequest->subject = $request->subject;
        $contactRequest->message = $request->message;
        $contactRequest->save();

        $contact = Contact::find($contactRequest->id);

        try {
            Mail::to('dario.regazzoni@outlook.fr')->send(new ContactRequestMail($contact));
        } catch(Swift_TransportException $transportExp) {
           return response("<p>Une erreur est survenue lors de l'envoi, nous en sommes désolés.</p><p>Si le problème presiste veuillez nous contacter directement via notre email.</p>", 400);
        }
        return response("<p>Mail envoyé avec succès, nous reviendrons vers vous dès que possible.</p>", 200);
    }
}
