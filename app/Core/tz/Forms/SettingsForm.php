<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;
use Illuminate\Database\DatabaseManager;

/**
 * Class SettingsForm
 * @package App\Core\tz\Forms
 */
class SettingsForm extends BaseForm
{
    protected $versions;

    /**
     * @param DatabaseManager $databaseManager
     */
    function __construct(DatabaseManager $databaseManager)
    {
        $db_versions = $databaseManager->table('versions')->get();
        $versions    = [];
        foreach ($db_versions as $ver) {
            $versions[$ver->version] = $ver->version;
        }
        $this->versions = $versions;
    }

    public function buildForm()
    {
        $this
            ->addCollection('reporting_organization_info', 'Settings\ReportingOrganizationInfoForm')
            ->addCollection('registry_info', 'Settings\RegistryInfoForm')
            ->addCollection('default_field_values', 'Settings\DefaultFieldValuesForm', '', [], null, 'tz')
            ->add(
                'Save',
                'submit',
                [
                    'attr' => ['class' => 'btn btn-submit btn-form']
                ]
            )->add('Cancel', 'static', [
                'tag'     => 'a',
                'label'   => false,
                'value'   => 'Cancel',
                'attr'    => [
                    'class' => 'btn btn-cancel',
                    'href'  => route('activity.index')
                ],
                'wrapper' => false
            ]);

    }
}
