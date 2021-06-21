<?php

namespace App\Http\Controllers;

use App\Services\DocumentsService;
use Illuminate\Http\Request;

class RapportsController extends Controller
{

    protected $documentService;

    public function __construct(DocumentsService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function show(Request $request)
    {
        if($request->has('onlyWebsiteRelated')){
            $documents = $this->documentService->getOnlyWebsiteRapportPage();
        } else {
            $documents = $this->documentService->getAll();
        }
        return response()->json($documents);

    }

    public function single($id)
    {
        return response()->json($this->documentService->getSingle($id));
    }

    public function types()
    {
        return response()->json($this->documentService->getAllTypes());
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
        $env_rapports_key = config('env_variables.rapport_token');

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
        $this->documentService->delete($id);
    }
}
