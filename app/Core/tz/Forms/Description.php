<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;

class Description extends BaseForm
{
    /**
     * builds activity description form
     */
    public function buildForm()
    {

        $this
            ->add('general', 'textarea', ['required' => true])
            ->add('objectives', 'textarea')
            ->add('target_groups', 'textarea');
    }
}
