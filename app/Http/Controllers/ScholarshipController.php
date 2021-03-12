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

        //        request()->validate([
//            'file' => 'required',
//            'file.*' => 'mimes:doc,pdf,docx,txt,xls,'
//        ]);
//        $allowed_extensions = array("jpg", "jpeg", "png", "bmp", "odf", "doc", "docx", "pdf", "txt", "xlt", "xls", "xml");

            $data = $request->only([
            'gender',
            'fullName',
            'email',
            'birthdate',
            'remarks',
            'files'
        ]);

        $result = ['status' => 200];
        try {
            $scholarshipModel =  $this->scholarshipsService->createScholarship($data);

            $result['data'] = $scholarshipModel;
            $result['data']['files'] =  $this->scholarshipsService->uploadScholarshipFiles($data, $scholarshipModel->id);
        } catch(Exception $e){
            $result = [
                'status' => 400,
                'error' => "Une erreur est survenue lors du traitement de votre bourse, si le problème persiste, veuillez contacter notre secretariat."
            ];
            return response()->json($result, $result['status']);
        }
        $this->scholarshipsService->notifyEnvolAndUserNewScholarshipRequest($result);

        return response()->json($message = "Demande de bourse envoyée avec succès, nous vous recontacterons prochainement." , $result['status']);
    }

}
