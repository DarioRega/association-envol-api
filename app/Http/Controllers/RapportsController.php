<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RapportsController extends Controller
{
    public function show()
    {
        $documentsOrderedByTypes = [];
        $allDocuments = Document::with('type')->get();

        foreach ($allDocuments as $document){
            $typeName = $document['type']['name'];
            if(!isset($documentsOrderedByTypes[$typeName])){
                $documentsOrderedByTypes[$typeName] = [];
            }

            array_push($documentsOrderedByTypes[$typeName], $document);
        }

        return response()->json($documentsOrderedByTypes);

    }

    public function upload(Request $request)
    {

        $access_token = $request->accessToken;
        $env_rapports_key = config('rapports-key.token');

        if(!$access_token || $access_token != $env_rapports_key){
            return response()->json('Unauthorized',401);
        }
//        request()->validate([
//            'file' => 'required',
//            'file.*' => 'mimes:doc,pdf,docx,txt,xls,'
//        ]);

        $name = $request->name;

        $document = new Document();
        $document->name = $name;

        if($request->hasFile('file')) {
            $typeId = $request->typeId;
            $file = $request->file('file');
            $document->type_id = $typeId;

            $typeDirectory = Type::find($typeId);
            $path = Storage::disk('public')->put('documents/' . $typeDirectory->name, $file);
            $full_path = '/storage/' . $path;

            $document->srcUrl = $full_path;
        } else {
            $document->is_external = true;
            $document->srcUrl = $request->srcUrl;
        }
        $document->save();

        return response()->json(Document::find($document->id));
    }
}
