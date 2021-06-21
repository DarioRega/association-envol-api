<?php


namespace App\Repositories;


use App\Models\Document;

class DocumentsRepository
{

    public function getSingleById($id)
    {
        return Document::findOrFail($id);
    }
}
