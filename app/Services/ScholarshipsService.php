<?php


namespace App\Services;


use App\Repositories\ScholarshipsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $data['age'] = self::getAgeFromBirthdate($data['birthdate']);
        try {
            $scholarshipModel = $this->scholarshipsRepository->save($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();
//
        return $scholarshipModel;

    }

    public function uploadScholarshipFiles($data, $scholarshipId){
        $createdFiles = [];
        $fileData = [];
        $fileData['scholarship_id'] = $scholarshipId;
        foreach($data['files'] as $file){
            $dirName = self::generateUserDirectory($data['fullName']);
            $baseDirectory = self::getBaseDirectory();
            $fileData['scholarship_id'] = $scholarshipId;
            $fileData['name'] = $file->getClientOriginalName();

            $fileData['srcUrl'] = Storage::disk('local')->put($baseDirectory.'/'.$dirName, $file);

            array_push($createdFiles, $this->scholarshipsRepository->createFile($fileData));
        }
        return $createdFiles;
    }

    public function slugify($string){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public function generateUserDirectory($name){
        $slugifiedName = $this->slugify($name);
        $date = date('d-m-Y__H-i-s');
        return $slugifiedName.'__'.$date;
    }

    public function getBaseDirectory(){
        $actualYear = date('Y');
        return 'demandes-de-bourses/'.$actualYear;
    }

    public function getAgeFromBirthdate($rawBirthdate){
        $birthdate = '';

        if(strpos($rawBirthdate, '.') !== false){
            $format = str_replace('-', '.', $rawBirthdate);
             $birthdate = date_create($rawBirthdate);
        } elseif (strpos($rawBirthdate, '/') !== false){
            $format = str_replace('-', '/', $rawBirthdate);
            $birthdate = date_create($rawBirthdate);
        } elseif (strpos($rawBirthdate, '-') !== false){
            $birthdate = date_create($rawBirthdate);
        }
        if(!$birthdate){
            return '';
        }
        $today =

    }

}
