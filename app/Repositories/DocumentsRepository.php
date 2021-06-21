<?php


namespace App\Repositories;


use App\Models\Document;
use App\Models\Type;

class DocumentsRepository
{

    public function getSingleById($id)
    {
        return Document::findOrFail($id);
    }

    public function getAll(){
        return Document::all();
    }

    public function getAllWebsiteRelated($data){
        return Document::with('type')
            ->where('type_id', '=', $data['rapport_id'])
            ->orWhere('type_id', '=', $data['nationality_id'])
            ->orWhere('type_id', '=', $data['account_id'])
            ->orWhere('type_id', '=', $data['formation_id'])
            ->get();
    }

    public function create($data)
    {
        $document = new Document();
        $document->name = $data['name'];
        $document->year_to_classify = $data['year_to_classify'];
        $document->type_id = $data['type']['id'];
        $document->srcUrl = $data['srcUrl'];
        $document->is_external = $data['is_external'] || false;

        $document->save();

        return $document::find($document->id);
    }

    /*
     Types
     */
    public function getSingleTypeById($id)
    {
        return Type::findOrFail($id);
    }

    public function getSingleTypeByName($name)
    {
        return Type::whereName($name)->firstOrFail();
    }
}
