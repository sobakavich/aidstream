<?php namespace App\Migration\Elements;


class PolicyMarker
{
    public function format($policyMarker, $policyMarkerNarrative)
    {
        return [
            'vocabulary'    => fetchCode($policyMarker->vocabulary, 'PolicyMarkerVocabulary'),
            'significance'  => fetchCode($policyMarker->significance, 'PolicySignificance'),
            'policy_marker' => fetchCode($policyMarker->code, 'PolicyMarker'),
            'narrative'     => $policyMarkerNarrative
        ];
    }
}
