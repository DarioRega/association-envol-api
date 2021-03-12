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
        $age = self::getAgeFromBirthdate($data['birthdate']);
        try {
            $scholarshipModel = $this->scholarshipsRepository->save($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();
        $scholarshipModel['age'] = $age;
        return $scholarshipModel;
    }

    public function uploadScholarshipFiles($data, $scholarshipId): array
    {
        DB::beginTransaction();

        $createdFiles = [];
        $fileData = [];
        $fileData['scholarship_id'] = $scholarshipId;
        foreach($data['files'] as $file){
            $dirName = self::generateUserDirectory($data['fullName']);
            $baseDirectory = self::getBaseDirectory();
            $fileData['scholarship_id'] = $scholarshipId;
            $fileData['name'] = $file->getClientOriginalName();

            $fileData['srcUrl'] = Storage::disk('local')->put($baseDirectory.'/'.$dirName, $file);
            try {
                array_push($createdFiles, $this->scholarshipsRepository->createFile($fileData));
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
            }
        }
        return $createdFiles;

        DB::commit();

    }

    public function slugify($string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public function generateUserDirectory($name): string
    {
        $slugifiedName = $this->slugify($name);
        $date = date('d-m-Y__H-i-s');
        return $slugifiedName.'__'.$date;
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
