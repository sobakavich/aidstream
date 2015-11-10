<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class CollaborationType
 * @package App\Services\FormCreator\Activity
 */
class CollaborationType
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var
     */
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getCollaborationType()->getForm();
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return Activity Collaboration Type edit form.
     */
    public function editForm($data, $activityId)
    {
        $model['collaboration_type'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.collaboration-type.update', [$activityId, 0])
            ]
        )->add('Save', 'submit');
    }
}
