<?php namespace App\Tz\Aidstream\Repositories\Project;

use App\Tz\Aidstream\Models\Project;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    /**
     * Find a Project with a specific id.
     * @param $id
     * @return Project
     */
    public function find($id);

    /**
     * Get all Projects for an Organization.
     * @return Collection
     */
    public function all();

    /**
     * Create a new Project.
     * @param array $projectDetails
     * @return Project
     */
    public function create(array $projectDetails);

    /**
     * Delete an existing Project.
     * @param $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id);
}
