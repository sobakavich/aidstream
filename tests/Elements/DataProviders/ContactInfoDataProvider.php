<?php namespace Test\Elements\DataProviders;


trait ContactInfoDataProvider
{
    use TestObjectCreator;

    /**
     * Initialize empty Test data.
     */
    protected function initializeEmptyTestData()
    {
        $this->typeCode                       = '';
        $this->contactOrgNarrative            = [];
        $this->contactInfoDepartmentNarrative = [];
        $this->contactInfoPersonNarrative     = [];
        $this->contactInfoJobNarrative        = [];
        $this->telephone                      = [];
        $this->email                          = [];
        $this->website                        = [];
        $this->mailingAddress                 = [];
    }

    /**
     * Initialize Test Contact Info Data.
     */
    protected function initializeTestData()
    {
        $this->typeCode                       = '1';
        $this->contactOrgNarrative            = $this->getTestNarratives(['testNarrative1', 'testNarrative12'], ['testLanguage', 'testLanguage2']);
        $this->contactInfoDepartmentNarrative = [];
        $this->contactInfoPersonNarrative     = [];
        $this->contactInfoJobNarrative        = $this->getTestNarratives(['testNarrative1', 'testNarrative12'], ['testLanguage', 'testLanguage2']);
        $this->telephone                      = [];
        $this->email                          = [
            ['email' => 'test@aidstream.com']
        ];
        $this->website                        = [];
        $this->mailingAddress                 = [['narrative' => $this->getTestNarratives(['testNarrative1'], ['testLanguage'])]];
    }

    /**
     * Initialize only some test data.
     */
    protected function initializeSomeTestData()
    {
        $this->typeCode                       = '1';
        $this->contactOrgNarrative            = $this->getTestNarratives(['testNarrative1', 'testNarrative12'], ['testLanguage', 'testLanguage2']);
        $this->contactInfoDepartmentNarrative = $this->getTestNarratives(['testNarrative1', 'testNarrative12'], ['testLanguage', 'testLanguage2']);
        $this->contactInfoPersonNarrative     = $this->getTestNarratives(['testNarrative1', 'testNarrative12'], ['testLanguage', 'testLanguage2']);
        $this->contactInfoJobNarrative        = $this->getTestNarratives(['testNarrative1', 'testNarrative12'], ['testLanguage', 'testLanguage2']);
        $this->telephone                      = [
            ['telephone' => '1234567']
        ];
        $this->email                          = [
            ['email' => 'test@aidstream.com']
        ];
        $this->website                        = [
            ['website' => 'www.test.org']
        ];
        $this->mailingAddress                 = [['narrative' => $this->getTestNarratives(['testNarrative1'], ['testLanguage'])]];
    }
}