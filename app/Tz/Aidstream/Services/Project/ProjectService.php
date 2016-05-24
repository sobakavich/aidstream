<?php namespace App\Tz\Aidstream\Services\Project;

use App\Tz\Aidstream\Models\Project;
use App\Tz\Aidstream\Repositories\Project\ProjectRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class ProjectService
 * @package App\Tz\Aidstream\Services\Project
 */
class ProjectService
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $project;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ProjectService constructor.
     * @param ProjectRepositoryInterface $project
     * @param LoggerInterface            $logger
     */
    public function __construct(ProjectRepositoryInterface $project, LoggerInterface $logger)
    {
        $this->project = $project;
        $this->logger  = $logger;
    }

    /**
     * Find a Project with a specific id.
     * @param $id
     * @return Project
     */
    public function find($id)
    {
        return $this->project->find($id);
    }

    /**
     * Get all Projects for an Organization.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->project->all();
    }

    /**
     * Create a new Project.
     * @param array $projectDetails
     * @return bool|null
     */
    public function create(array $projectDetails)
    {
        try {
            $projectDetails['organization_id'] = session('org_id');
            $this->project->create($projectDetails);

            $this->logger->info(
                'Project successfully created.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Project could not created due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Delete an existing Project.
     * @param $id
     * @return bool|null
     */
    public function delete($id)
    {
        try {
            $this->project->delete($id);

            $this->logger->info(
                sprintf('Project (id: %s) successfully deleted.', $id),
                [
                    'byUser'          => auth()->user()->getNameAttribute(),
                    'organization_id' => session('org_id')
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error while deleting Project (id: %s) due to %s.', $id, $exception->getMessage()),
                [
                    'byUser'          => auth()->user()->getNameAttribute(),
                    'organization_id' => session('org_id'),
                    'trace'           => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    public function update($id, $defaultFieldValues)
    {
        try {
            $project = $this->project->find($id);

            $project->update($defaultFieldValues);

            $this->logger->info(
                'Successfully updated the Default Field Values.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error updating the Default Field Values due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }

    }
}
