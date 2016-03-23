<?php namespace App\Core\tz\Forms\Settings;

use App\Core\tz\BaseForm;

/**
 * Class DefaultFieldValuesForm
 * @package App\Core\tz\Forms\Settings
 */
class DefaultFieldValuesForm extends BaseForm
{
    /**
     * build Default field values form
     */
    public function buildForm()
    {
        $this
            ->addSelect('default_currency', $this->getCodeList('Currency', 'Organization'), 'Default Currency', $this->addHelpText('activity_defaults-default_currency', false), null, true)
            ->addSelect('default_language', $this->getCodeList('Language', 'Organization'), 'Default Language', $this->addHelpText('activity_defaults-default_language', false), null, true);
    }
}
