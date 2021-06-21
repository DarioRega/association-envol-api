<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Type;
use App\Services\DocumentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RapportsController extends Controller
{

    protected $documentService;

    public function __construct(DocumentsService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function show()
    {
        $documentsOrderedByTypes = [];
        $commonType = Type::whereName('Commons')->firstOrFail();
        $allDocuments = Document::with('type')->where('type_id', '!=', $commonType->id)->get();

        $sortedDesc = collect($allDocuments)->sortByDesc('year_to_classify');

        foreach ($sortedDesc as $document){
            $typeName = $document['type']['name'];
            if(!isset($documentsOrderedByTypes[$typeName])){
                $documentsOrderedByTypes[$typeName] = [];
            }

            array_push($documentsOrderedByTypes[$typeName], $document);
        }


        return response()->json($documentsOrderedByTypes);

    }

    public function single($id)
    {
        return Document::findOrFail($id);
    }

    public function types()
    {
        return response()->json(Type::all());
    }

    public function download($id){

        try {
        $data =  $this->documentService->download($id);
            return response()->download($data['file_path'], $data['file_name'],$data['headers']);
        } catch (Exception $e){
            return response(['message' => "Document not found", 'err' => $e->getMessage()], 404);
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
            'type_id' => 'nullable', 'string',
            'year_to_classify' => 'required', 'integer',
            'is_external' => 'nullable',
        ]);

        $data = request()->only([
            'name',
            'type_id',
            'year_to_classify',
            'file',
            'srcUrl',
        ]);
        $data['is_external'] = $request->input('is_external', false);

        $document = $this->documentService->upload($data);

        return response()->json($document);
    }


    public function delete($id){
        $rapport = Document::findOrFail($id);

        Storage::disk('public')->delete($rapport->srcUrl);
        $rapport->delete();
    }
}
