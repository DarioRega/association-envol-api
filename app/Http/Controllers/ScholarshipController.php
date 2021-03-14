<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequestMail;
use App\Mail\ScholarshipRequestMail;
use App\Models\Scholarship;
use App\Services\ScholarshipsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;

class ScholarshipController extends Controller
{
    protected $scholarshipsService;
//
    public function __construct(ScholarshipsService $scholarshipsService)
    {
        $this->scholarshipsService = $scholarshipsService;
    }

    public function create(Request $request)
    {
        request()->validate([
            'fullName' => ['required'],
            'email' => ['required', 'email'],
            'files' => ['required'],
        ]);

        $result = ['status' => 200];
        $allowed_extensions = array("jpg", "jpeg", "png", "bmp", "odf", "doc", "docx", "pdf", "txt", "xlt", "xls", "xml");

        $data = $request->only([
            'gender',
            'fullName',
            'email',
            'birthdate',
            'remarks',
            'files'
        ]);
        $data['remarks'] = empty($data['remarks']) ? 'Aucune' : $data['remarks'];
        foreach($data['files'] as $file) {
            $extension = $file->getClientOriginalExtension();
            if(!in_array($extension, $allowed_extensions)){
                $result['status'] = 404;
                $fileName = $file->getClientOriginalName();
                $result['message'] = 'Le fichier <b>'.$fileName.'</b> n\'est pas un fichier valide.<br> Les types de fichiers admis sont:<br>jpg, jpeg, png, bmp, odf, doc, docx, pdf, txt, xlt, xls, xml' ;
                return response()->json(['message' => $result['message']], $result['status']);
            }
        }

        try {
            $scholarshipModel =  $this->scholarshipsService->createScholarship($data);

            $result['data'] = $scholarshipModel;
            $result['data']['files'] =  $this->scholarshipsService->uploadScholarshipFiles($data, $scholarshipModel->id);
        } catch(Exception $e){
            $result = [
                'status' => 400,
                'message' => "Une erreur est survenue lors du traitement de votre bourse, si le problème persiste, veuillez contacter notre secretariat."
            ];
            return response()->json(['message' => $result['message']], $result['status']);
        }
        $result = $this->scholarshipsService->notifyEnvolAndUserNewScholarshipRequest($result);

        $result['message'] = "Demande de bourse envoyée avec succès, nous vous recontacterons prochainement.";
        return response()->json(['message' => $result['message']], $result['status']);
    }


}
