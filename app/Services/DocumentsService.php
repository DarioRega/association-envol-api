<?php


namespace App\Services;

use App\Repositories\DocumentsRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentsService
{
    protected $documentsRepository;

    public function __construct(DocumentsRepository $documentsRepository)
    {
        $this->documentsRepository= $documentsRepository;
    }

    public function getSingle($id){
        return $this->documentsRepository->getSingleById($id);
    }

    public function getAll(){
        return $this->documentsRepository->getAll();
    }

    public function getAllTypes(){
        return $this->documentsRepository->getAllTypes();
    }

    public function getOnlyWebsiteRapportPage(){
        $documentsOrderedByTypes = [];
        $relatedIds = $this->getWebsitesRelatedTypesId();
        $relatedDocuments = $this->documentsRepository->getAllWebsiteRelated($relatedIds);

        $sortedDesc = collect($relatedDocuments)->sortByDesc('year_to_classify');

        foreach ($sortedDesc as $document){
            $typeName = $document['type']['name'];
            if(!isset($documentsOrderedByTypes[$typeName])){
                $documentsOrderedByTypes[$typeName] = [];
            }

            array_push($documentsOrderedByTypes[$typeName], $document);
        }
        return $documentsOrderedByTypes;
    }



    public function download($id)
    {
        $rapport  = $this->documentsRepository->getSingleById($id);

        $pbl_path = public_path();
        $file_path = $pbl_path.$rapport->srcUrl;
        $ext = pathinfo($file_path)['extension'];

        $type = File::mimeType($file_path);
        $headers = array(
            'Content-Type' => $type,
        );

        if (file_exists($file_path)) {
            $data['file_path'] = $file_path;
            $data['file_name'] = $rapport->name.'.'.$ext;
            $data['headers'] = $headers;

            return $data;
        } else {
            throw new NotFoundHttpException('Document not found');
        }
    }

    function upload($data){

        if($data['is_external']){
            $data['type'] = $this->documentsRepository->getSingleTypeByName('Externals');
        } else {
            $data['type'] = $this->documentsRepository->getSingleTypeById($data['type_id']);
            $path = Storage::disk('public')->put('documents/' . $data['type']['name'], $data['file']);
            $full_path = '/storage/' . $path;
            $data['srcUrl'] = $full_path;
        }

        return $this->documentsRepository->create($data);
    }

    public function delete($id){
        $rapport = $this->documentsRepository->getSingleById($id);
        Storage::disk('public')->delete($rapport->srcUrl);

        $this->documentsRepository->delete($rapport);
    }

    private function getWebsitesRelatedTypesId(){
        return [
            'rapport_id' => $this->documentsRepository->getSingleTypeByName('Rapports')['id'],
            'nationality_id' => $this->documentsRepository->getSingleTypeByName('Nationalités')['id'],
            'account_id' => $this->documentsRepository->getSingleTypeByName('Comptes')['id'],
            'formation_id' => $this->documentsRepository->getSingleTypeByName('Formations')['id'],
            'bilan_id' => $this->documentsRepository->getSingleTypeByName('Bilans')['id'],
        ];
    }
}
