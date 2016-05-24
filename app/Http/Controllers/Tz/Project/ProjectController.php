<?php namespace App\Http\Controllers\Tz\Project;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Tz\TanzanianController;
use App\Tz\Aidstream\Services\Project\ProjectService;
use App\Tz\Aidstream\Traits\FormatsProjectFormInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class ProjectController
 * @package App\Http\Controllers\Tz\Project
 */
class ProjectController extends TanzanianController
{
    use FormatsProjectFormInformation;

    /**
     * @var ProjectService
     */
    protected $project;

    /**
     * ProjectController constructor.
     * @param ProjectService $project
     */
    public function __construct(ProjectService $project)
    {
        $this->project = $project;
    }

    /**
     * List all Projects.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $projects    = $this->project->all();
        $statusLabel = ['draft', 'completed', 'verified', 'published'];

        return view('tz.project.index', compact('projects', 'statusLabel'));
    }

    /**
     * Show the form to create a new Project.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $baseForm         = new BaseForm();
        $codeList         = $baseForm->getCodeList('ActivityStatus', 'Activity');
        $sectors          = $baseForm->getCodeList('SectorCategory', 'Activity');
        $recipientRegions = $baseForm->getCodeList('Region', 'Activity');

        return view('tz.project.create', compact('codeList', 'sectors', 'recipientRegions'));
    }

    /**
     * Store a new Project into the database
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        if (!$this->project->create($this->process($request->all()))) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Project could not be saved.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Project successfully saved.']]];
        }

        return redirect()->route('tz.project.index')->withResponse($response);
    }

    /**
     * Show a Project.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $project = $this->project->find($id);

        if (Gate::denies('ownership', $project)) {
            return redirect()->route('project.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $statusLabel      = ['draft', 'completed', 'verified', 'published'];
        $activityWorkflow = $project->activity_workflow;
        $btn_status_label = ['Completed', 'Verified', 'Published'];
        $btn_text         = $activityWorkflow > 2 ? "" : $btn_status_label[$activityWorkflow];

        if ($project['activity_workflow'] == 0) {
            $nextRoute = route('activity.complete', $id);
        } elseif ($project['activity_workflow'] == 1) {
            $nextRoute = route('activity.verify', $id);
        } else {
            $nextRoute = route('activity.publish', $id);
        }

        return view('tz.project.show', compact('project', 'activityResult', 'id', 'statusLabel', 'btn_text', 'activityWorkflow', 'nextRoute'));
    }

    public function edit($id)
    {
        $project = $this->project->find($id);

        return view('tz.project.edit', compact('project'));
    }

    public function update($id)
    {

    }

    /**
     * Delete an existing Project.
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $response = $this->project->delete($id)
            ? ['type' => 'success', 'code' => ['message', 'message' => 'Project successfully deleted.']]
            : ['type' => 'danger', 'code' => ['message', 'message' => 'Project could not be deleted.']];

        return redirect()->route('project.index')->withResponse($response);
    }

    public function upload()
    {

    }

    /**
     * Show the form to change Project Default Field Values.
     * @param $id
     * @return \Illuminate\View\View
     */
    public function changeProjectDefaults($id)
    {
        $project = $this->project->find($id);

        if (Gate::denies('ownership', $project)) {
            return redirect()->route('project.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $project);

        $project = [
            'id'               => $project->id,
            'default_currency' => $project->default_field_values[0]['default_currency'],
            'default_language' => $project->default_field_values[0]['default_language']
        ];

        return view('tz.project.overrideProjectDefaults', compact('project'));
    }

    /**
     * Override Project Default Field Values.
     * @param         $id
     * @param Request $request
     * @return mixed
     */
    public function overrideProjectDefaults($id, Request $request)
    {
        $project = $this->project->find($id);

        if (Gate::denies('ownership', $project)) {
            return redirect()->route('project.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $project);

        $response = $this->project->update($id, $this->processDefaultFieldValues($request->all()))
            ? ['type' => 'success', 'code' => ['message', 'message' => 'Project Defaults successfully overridden.']]
            : ['type' => 'danger', 'code' => ['message', 'message' => 'Could not override Project defaults.']];

        return redirect()->route('project.show', [$id])->withResponse($response);
    }
}
