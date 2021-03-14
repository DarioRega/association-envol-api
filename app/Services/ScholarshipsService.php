<?php


namespace App\Services;


use App\Mail\ScholarshipConfirmationMail;
use App\Mail\ScholarshipRequestMail;
use App\Repositories\ScholarshipsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ScholarshipsService
{
    protected $scholarshipsRepository;

    public function __construct(ScholarshipsRepository $scholarshipsRepository)
    {
        $this->scholarshipsRepository= $scholarshipsRepository;
    }

    public function createScholarship($data)
    {
        DB::beginTransaction();
        $genderArray = config('enums.gender');
        $data['gender'] = $genderArray[$data['gender']];
        $age = self::getAgeFromBirthdate($data['birthdate']);
        try {
            $scholarshipModel = $this->scholarshipsRepository->save($data);
        } catch (Exception $e) {
            DB::rollBack();
//            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();
        $scholarshipModel['age'] = $age;
        return $scholarshipModel;
    }

    public function uploadScholarshipFiles($data, $scholarshipId): array
    {
        $createdFiles = [];
        $fileData = [];
        $fileData['scholarship_id'] = $scholarshipId;
        foreach($data['files'] as $file){

            $dirName = self::generateUserDirectory($data['fullName'], $scholarshipId);
            $baseDirectory = self::getBaseDirectory();
            $fileData['scholarship_id'] = $scholarshipId;
            $fileData['name'] = $file->getClientOriginalName();
            $fileData['mimeType'] = $file->getMimeType();
            $fileData['srcUrl'] = Storage::disk('local')->put($baseDirectory.'/'.$dirName, $file);
            try {
                array_push($createdFiles, $this->scholarshipsRepository->createFile($fileData));
            } catch (Exception $e) {
//                Log::error($e->getMessage());
            }
        }
        return $createdFiles;
    }


    public function notifyEnvolAndUserNewScholarshipRequest($result): array
    {

        $zipResult = self::generateZip($result['data']['files'], $result['data']['fullName'], $result['data']['id']);

        if (isset($zipResult['zipPath'])) {
            $result['data']['zipPath'] = $zipResult['zipPath'];
            $result['data']['zipName'] = $zipResult['zipName'];
        } else {
//            Log::warning('Zip file generation is not working');
        }

        try {
            Mail::to('dario.regazzoni@outlook.fr')->send(new ScholarshipRequestMail($result['data']));
            $result['message'] = "Demande de bourse envoyée avec succès, nous vous recontacterons prochainement.";
        } catch (\Swift_TransportException $e) {
//            Log::warning($e->getMessage());
            $result = [
                'status' => 400,
                'message' => "Une erreur est survenue durant l'envoi du mail de confirmation. Cependant votre demande de bourse a été téléchargée.<br> Veuillez contacter notre secretariat en leur indiquant que votre demande de bourse est en statut <b>202</b>."
            ];
        }

        try {
            self::notifyUserSuccessScholarshipRequest($result['data']);
        } catch (\Swift_TransportException $e) {
//            Log::warning($e->getMessage());
            $result = [
                'status' => 417,
                'message' => "Une erreur est survenue durant l'envoi du mail de confirmation pour votre boîte mail. Cependant votre demande de bourse a été prise en compte. nous vous recontacterons prochainement."
            ];
        }
        return $result;
    }

    public function notifyUserSuccessScholarshipRequest($data){
        Mail::to($data['email'])->send(new ScholarshipConfirmationMail($data));
    }

    public function generateZip($files, $userFullName, $scholarshipId): array
    {
        $zip = new ZipArchive;

        $dirPath = self::getBaseDirectory().'/'.self::generateUserDirectory($userFullName, $scholarshipId);
        $zipFileName = $scholarshipId.'_'.self::slugify($userFullName).'.zip';
        $storagePath = storage_path().'/app';

        if($zip->open($storagePath.'/'.$dirPath.'/'.$zipFileName, ZipArchive::CREATE) === TRUE){
           foreach($files as $file) {
                $zip->addFile($storagePath.'/'.$file['srcUrl'], $file['name']);
            }
        $zip->close();
        }
        if(file_exists($storagePath.'/'.$dirPath.'/'.$zipFileName)) {
            return ['zipPath' => $dirPath.'/'.$zipFileName, 'zipName' => $zipFileName];
        }
        return [];
    }

    public function slugify($string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public function generateUserDirectory($name, $scholarshipId): string
    {
        $slugifiedName = $this->slugify($name);
        return $scholarshipId.'_'.$slugifiedName;
    }

    public function getBaseDirectory(): string
    {
        $actualYear = date('Y');
        return 'demandes-de-bourses/'.$actualYear;
    }

    public function getAgeFromBirthdate($rawBirthdate){
        $birthdate = '';
        if(strpos($rawBirthdate, '.') == true){
            $formattedRawBirthdate = str_replace('.', '-', $rawBirthdate);
            $birthdate = date_create($formattedRawBirthdate);
        } elseif (strpos($rawBirthdate, '/') == true){
            $formattedRawBirthdate = str_replace('/', '-', $rawBirthdate);
            $birthdate = date_create($formattedRawBirthdate);
        } elseif (strpos($rawBirthdate, '-') == true){
            $birthdate = date_create($rawBirthdate);
        }
        if(!$birthdate){
            return '';
        }
        $today = date_create('now');
        $age = date_diff($birthdate, $today)->y;

        return $age;

    }

}
