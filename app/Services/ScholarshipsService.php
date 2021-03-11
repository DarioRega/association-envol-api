<?php


namespace App\Services;


use App\Repositories\ScholarshipsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function uploadScholarshipFiles($data, $scholarshipId = 2){
//        print_r($data);
//        foreach($data['file'] as $file){
//            echo $file;
//        }
//            $dirName = $this->slugify($request->fullName).'__'.time();
//
//            $path = Storage::disk('local')->put($typeDirectory->name, $file);
//            $full_path = '/storage/' . $path;
//
//            $document->srcUrl = $full_path;
//
//        }
    }

    public function slugify($string){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

}
