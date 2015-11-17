<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\ResultManager;
use App\Services\FormCreator\Activity\Result as ResultForm;
use App\Services\RequestManager\Activity\Result as ResultRequestManager;
use Illuminate\Http\Request;

/**
 * Class ResultController
 * @package App\Http\Controllers\Complete\Activity
 */
class ResultController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var ResultManager
     */
    protected $resultManager;
    /**
     * @var ResultForm
     */
    protected $resultForm;

    /**
     * @param ResultManager   $resultManager
     * @param ResultForm      $resultForm
     * @param ActivityManager $activityManager
     */
    function __construct(ResultManager $resultManager, ResultForm $resultForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager = $activityManager;
        $this->resultManager   = $resultManager;
        $this->resultForm      = $resultForm;
    }

    /**
     * returns the activity result edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function  index($id)
    {
        $results      = $this->resultManager->getResults($id);
        $activityData = $this->activityManager->getActivityData($id);

        return view('Activity.result.index', compact('results', 'activityData', 'id'));
    }

    /**
     * Show the form for creating result
     * @param $id
     * @return \Illuminate\View\View
     */
    public function  create($id)
    {
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->resultForm->createForm($id);

        return view('Activity.result.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * Store results.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($activityId, Request $request, ResultRequestManager $resultRequestManager)
    {
        $resultData     = $request->all();
        $activityResult = $this->resultManager->getResult(null, $activityId);
        if ($this->resultManager->store($resultData, $activityResult)) {
            return redirect()->to(sprintf('/activity/%s/result', $activityId))->withMessage('Activity result created!');
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing activity result.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $resultId)
    {
        $result       = $this->resultManager->getResult($resultId, $id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->resultForm->editForm($result, $id);

        return view('Activity.result.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates activity result
     * @param                                 $id
     * @param Request                         $request
     * @param ResultRequestManager            $resultRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, $resultId, Request $request, ResultRequestManager $resultRequestManager)
    {
        $resultData     = $request->all();
        $activityResult = $this->resultManager->getResult($resultId, $id);
        if ($this->resultManager->update($resultData, $activityResult)) {
            return redirect()->to(sprintf('/activity/%s/result', $id))->withMessage('Activity Result updated!');
        }

        return redirect()->back();
    }
}
