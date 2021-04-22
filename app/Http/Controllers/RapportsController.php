<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Type;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
    public function download($id){
        $rapport = Document::find($id);
        $pbl_path = public_path();
        $file_path = $pbl_path.$rapport->srcUrl;
        $type = File::mimeType($file_path);
        $headers = array(
            'Content-Type' => $type,
//            'Content-Disposition' => 'attachment; filename='.$rapport->name,
        );
        if ( file_exists($file_path ) ) {
//            return Storage::disk('public')->download($rapport->srcUrl, $rapport->name, $headers);
            return response()->download($file_path, $rapport->name,$headers);
        } else {
            return response()->json('NO EXIST'. $file_path);
        }
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
