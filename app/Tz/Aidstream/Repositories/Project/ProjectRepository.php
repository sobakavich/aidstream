<?php namespace App\Tz\Aidstream\Repositories\Project;

use App\Tz\Aidstream\Models\Project;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProjectRepository
 * @package App\Tz\Aidstream\Repositories\Project
 */
class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * ProjectRepository constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return $this->project->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->project->where('organization_id', '=', session('org_id'))->get();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $projectDetails)
    {
        $project = $this->project->newInstance($projectDetails);

        return $project->save();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $project = $this->project->findOrFail($id);

        return $project->delete();
    }
}
