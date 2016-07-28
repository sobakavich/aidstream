<?php namespace App\Services\FormCreator\Activity;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class ImportResult
 * @package App\Services\FormCreator\Activity
 */
class ImportResult
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     * @param Version     $version
     */
    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = $version->getActivityElement()->getImportResultForm();
    }

    /**
     * Creates the result csv upload form
     * @return $this
     */
    public function createForm($activityId)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('import-result.list-results', $activityId)
            ]
        )->add('Upload', 'submit', ['attr' => ['class' => 'btn pull-left']]);
    }
}
