<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequestMail;
use App\Models\Scholarship;
use App\Services\ScholarshipsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;

class ScholarshipController extends Controller
{
//    protected $scholarshipsService;
//
//    public function __construct(ScholarshipsService $scholarshipsService)
//    {
//        $this->scholarshipsService = $scholarshipsService;
//    }

    public function create(Request $request)
    {
        //        request()->validate([
//            'file' => 'required',
//            'file.*' => 'mimes:doc,pdf,docx,txt,xls,'
//        ]);
//        $data = $request->only([
//            'gender',
//            'fullName',
//            'email',
//            'birthdate',
//            'remarks',
//        ]);
//        foreach($request->files as $file) {
//            echo $file->getClientOriginalName();
//        }
        return ['email' => $request->all()];











//        return $request->email;
//        $data = [];
//        return $request->all();
//        echo $request->email;
//        print_r($data);

//        print_r($request->all());
//        $result = ['status' => 200];

//            $linkedFiles =  $this->scholarshipsService->uploadScholarshipFiles($data, 2);
//        try {
//            $scholarshipModel =  $this->scholarshipsService->createScholarship($data);
//            $linkedFiles =  $this->scholarshipsService->uploadScholarshipFiles($data, $scholarshipModel->id);


//            $result['data'] = $scholarshipModel;
//        } catch(Exception $e){
//            $result = [
//                'status' => 500,
//                'error' => $e->getMessage()
//            ];
//        }
//        return response()->json($result, $result['status']);
//        foreach($request->file('file') as $file){
//            $dirName = $this->slugify($request->fullName).'__'.time();
//
//            $path = Storage::disk('local')->put($typeDirectory->name, $file);
//            $full_path = '/storage/' . $path;
//
//            $document->srcUrl = $full_path;
//
//        }


//
//        try {
//            Mail::to('dario.regazzoni@outlook.fr')->send(new ContactRequestMail($contact));
//        } catch(Swift_TransportException $transportExp) {
//            return response("<p>Une erreur est survenue lors de l'envoi, nous en sommes désolés.</p><p>Si le problème presiste veuillez nous contacter directement via notre email.</p>", 400);
//        }
//        return response("<p>Mail envoyé avec succès, nous reviendrons vers vous dès que possible.</p>", 200);
    }



}
