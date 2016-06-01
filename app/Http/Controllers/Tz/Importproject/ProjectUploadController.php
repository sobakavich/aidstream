<?php namespace App\Http\Controllers\Tz\ImportProject;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

/**
 * Class ProjectUploadController
 * @package App\Http\Controllers\Tz\ProjectUpload
 */
class ProjectUploadController extends Controller
{

    /**
     * ProjectUploadController constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function index()
    {
        return view('tz.projectUpload.create');
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {

    }

    /**
     *
     */
    public function downloadProjectTemplate()
    {

    }
}
