<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Type;
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

    public function types()
    {
        return response()->json(Type::all());
    }

    public function download($id){
        $rapport = Document::find($id);
        $pbl_path = public_path();
        $file_path = $pbl_path.$rapport->srcUrl;
        $ext = pathinfo($file_path)['extension'];

        $type = File::mimeType($file_path);
        $headers = array(
            'Content-Type' => $type,
        );

        if ( file_exists($file_path ) ) {
            return response()->download($file_path, $rapport->name.'.'.$ext,$headers);
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
        request()->validate([
            'name' => 'required', 'string',
            'type_id' => 'required', 'string',
            'year_to_classify' => 'required', 'integer',
//            'file' => 'required',
//            'file.*' => 'mimes:doc,pdf,docx,txt,xls,'
        ]);

        $name = $request->name;
        $year_to_classify = $request->year_to_classify;

        $document = new Document();
        $document->name = $name;
        $document->year_to_classify = $year_to_classify;

        if($request->hasFile('file')) {
            $type_id = $request->type_id;
            $file = $request->file('file');
            $document->type_id = $type_id;

            $typeDirectory = Type::findOrFail($type_id);

            $path = Storage::disk('public')->put('documents/' . $typeDirectory->name, $file);

            $full_path = '/storage/' . $path;
            $document->srcUrl = $full_path;
        } else {
            $document->is_external = true;
            $document->srcUrl = $request->srcUrl;
        }

        $document->save();
        return response()->json(Document::findOrFail($document->id));
    }


    public function delete($id){
        $rapport = Document::findOrFail($id);

        Storage::disk('public')->delete($rapport->srcUrl);
        $rapport->delete();
    }
}
