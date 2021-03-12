<?php


namespace App\Repositories;


use App\Models\Scholarship;
use App\Models\DocumentsScholarship;

class ScholarshipsRepository
{

    public function save($data)
    {
        $scholarship =  Scholarship::create([
            'gender'=> $data['gender'],
            'fullName' => $data['fullName'],
            'email' => $data['email'],
            'birthdate' => $data['birthdate'],
            'remarks' => $data['remarks'],
        ]);

        return $scholarship->fresh();
    }

    public function createFile($data)
    {

        $scholarshipDocument = DocumentsScholarship::create([
            'name'=> $data['name'],
            'srcUrl' => $data['srcUrl'],
            'mimeType' => $data['mimeType'],
            'scholarship_id' => $data['scholarship_id'],
        ]);

        return $scholarshipDocument->fresh();
    }

}
