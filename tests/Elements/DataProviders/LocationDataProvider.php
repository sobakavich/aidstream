<?php namespace Test\Elements\DataProviders;


trait LocationDataProvider
{
    use TestObjectCreator;

    protected function getTestReference($empty = false)
    {
        return $this->createTestObjectWith(['id' => '1', 'ref' => 'Test Reference'])->ref;
    }

    protected function getTestLocationReach()
    {
        return $this->createTestObjectWith(['Code' => '1'])->Code;
    }

    protected function getTestLocationId()
    {
        return [
            'vocabulary' => $this->createTestObjectWith(['Code' => 'AA'])->Code,
            'code'       => $this->createTestObjectWith(['code' => 'test Code', 'vocabulary' => '2'])->code
        ];
    }

    protected function getTestAdministrativeData()
    {
        $object = $this->createTestObjectWith(['code' => '1', 'level' => '1', 'vocabulary' => 'test']);

        return [
            [
                'vocabulary' => fetchCode($object->vocabulary, 'GeographicVocabulary', ''),
                'code'       => $object->code,
                'level'      => $object->level
            ]
        ];
    }

    protected function getTestSrsName()
    {
        return $this->createTestObjectWith(['srsName' => 'http://www.test.org', 'id' => '1', 'location_id' => '12'])->srsName;
    }

    protected function getTestExactnessCode()
    {
        return fetchCode($this->createTestObjectWith(['code' => '1'])->code, 'GeographicExactness', '');
    }

    protected function getTestLocationClassCode()
    {
        return fetchCode($this->createTestObjectWith(['code' => '1'])->code, 'GeographicLocationClass', '');
    }

    protected function getTestFeatureDesignationCode()
    {
        return fetchCode($this->createTestObjectWith(['code' => '2'])->code, 'LocationType', '');
    }

    protected function getTestPositionData()
    {
        return [
            'latitude' => $this->createTestObjectWith(['latitude' => '12.12', 'longitude' => '21.21'])->latitude,
            'longitude' => $this->createTestObjectWith(['latitude' => '12.12', 'longitude' => '21.21'])->longitude
        ];
    }
}
