<?php namespace App\Http\Controllers\Complete;

use App\Http\Requests\Request;
use App\Services\File\S3\FileManager;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Services\DocumentManager;

/**
 * Class DocumentController
 * @package App\Http\Controllers\Complete
 */
class DocumentController extends Controller
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;
    /**
     * @var mixed
     */
    protected $orgId;

    /**
     * Allowed extensions for documents.
     * @var array
     */
    protected $allowedExtensions = ['doc', 'docx', 'pdf', 'jpeg', 'jpg', 'ppt', 'pptx', 'png', 'xls', 'bmp'];
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @param DocumentManager $documentManager
     * @param FileManager     $fileManager
     */
    function __construct(DocumentManager $documentManager, FileManager $fileManager)
    {
        $this->middleware('auth');
        $this->documentManager = $documentManager;
        $this->orgId           = session('org_id');
        $this->fileManager     = $fileManager;
    }

    /**
     * list organization documents
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $documents = $this->getDocuments();

        return view('documents', compact('documents'));
    }

    /**
     * save document
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            if (!in_array($extension, $this->allowedExtensions)) {
                return ['status' => 'warning', 'message' => 'No such files allowed.'];
            }

            $filename  = str_replace(' ', '-', preg_replace('/\s+/', ' ', $file->getClientOriginalName()));
            $extension = substr($filename, stripos($filename, '.'));
            $filename  = sprintf('%s-%s%s', substr($filename, 0, stripos($filename, '.')), date('Ymdhms'), $extension);
//            $url       = url(sprintf('files/documents/%s', $filename));
//            Storage::put(sprintf('%s/%s', 'documents', $filename), File::get($file));

            $this->fileManager->makeDir(sprintf('%s/%s', 'documents', session('org_id')));
            $filePath = $this->fileManager->getDocumentFilePath($filename);
            $this->fileManager->put($filePath, file_get_contents($file), 'public');
            $url = $this->fileManager->get($filePath);

            $document = $this->documentManager->getDocument($this->orgId, $url, $filename);

            if ($document->exists) {
                return ['status' => 'danger', 'message' => 'Document already exists.'];
            }

            $this->documentManager->store($document);
        } catch (\Exception $e) {
            return ['status' => 'danger', 'message' => 'Failed to upload Document. Error: ' . $e->getMessage()];
        }

        return ['status' => 'success', 'message' => 'Uploaded Successfully.', 'data' => $this->getDocuments()];
    }

    /**
     * return organization documents
     * @return mixed
     */
    public function getDocuments()
    {
        $documents = $this->documentManager->getDocuments($this->orgId);

        return $documents->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $document = $this->documentManager->getDocumentById($id);

        if (Gate::denies('ownership', $document)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $response = ($document->delete()) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'Document']]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => 'Document']]
        ];

        return redirect()->back()->withResponse($response);
    }
}
