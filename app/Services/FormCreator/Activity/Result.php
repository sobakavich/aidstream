<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class Result
 * @package App\Services\FormCreator\Activity
 */
class Result
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
     * @var String
     */
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getResult()->getForm();
    }

    /**
     * @param       $activityId
     * @return $this
     * return Activity Result edit form.
     */
    public function createForm($activityId)
    {

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('activity.result.store', [$activityId])
            ]
        )->add('Save', 'submit');
    }

    /**
     * @param array $data
     * @param       $activityId
     * @return $this
     * return Activity Result edit form.
     */
    public function editForm($data, $activityId)
    {
        $modal['result'] = $data->result;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('activity.result.update', [$activityId, $data->id])
            ]
        )->add('Save', 'submit');
    }
}
