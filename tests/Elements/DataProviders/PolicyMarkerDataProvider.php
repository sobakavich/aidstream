<?php namespace Test\Elements\DataProviders;


trait PolicyMarkerDataProvider
{
    use TestObjectCreator;

    protected function getTestPolicyMarker()
    {
        return $this->createTestObjectWith(
            [
                'id'            => '1',
                '@significance' => '3',
                '@vocabulary'   => '2',
                '@code'         => '1',
                '@xml_lang'     => '',
                'text'          => 'this is a test',
                'activity_id'   => '1',
                'code'          => '1',
                'vocabulary'    => '4',
                'significance'  => '3'
            ]
        );
    }
}
