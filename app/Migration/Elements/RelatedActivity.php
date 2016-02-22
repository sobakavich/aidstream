<?php namespace App\Migration\Elements;


class RelatedActivity
{
    public function format($relatedActivity)
    {
        return [
            'relationship_type'   => fetchCode($relatedActivity->type, 'RelatedActivityType'),
            'activity_identifier' => $relatedActivity->text
        ];
    }
}
