<?php namespace App\Http\Controllers;

use App\Models\Organization\Organization;
//use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var WhoIsUsingController
     */
    protected $organizationCount;

    function __construct(WhoIsUsingController $organizationCount)
    {
        $this->organizationCount = $organizationCount;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Filesystem $filesystem)
    {
        $organizationCount = $this->organizationCount->initializeOrganizationQueryBuilder()->get()->count();

        return view('home', compact('organizationCount'));
    }
}
