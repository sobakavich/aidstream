<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;

class Activities extends BaseForm
{
    /**
     * builds activity form
     */
    public function buildForm()
    {
        $this->addCollection('activity', 'Activity', '', [], null, 'tz');
    }
}
