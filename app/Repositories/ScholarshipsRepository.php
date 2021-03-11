<?php


namespace App\Repositories;


use App\Models\Scholarship;

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

    public function linkFiles($data)
    {

    }

}
